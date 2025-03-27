<?php

namespace App\Controller;

use App\Entity\Medication;
use App\Entity\Sample;
use App\Form\MedicationType;
use App\Repository\MedicationRepository;
use App\Repository\SampleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MedicationController extends AbstractController
{
    #[Route('/medication', name: 'app_medication')]
    public function index(
        MedicationRepository $medicationRepository,
        Request $request,
    ): Response {
        $search = $request->get('search', '');
        $filter = $request->get('filter', '');
        $inStock = $request->get('inStock', '');

        $medications = $medicationRepository->search($search, $filter, $inStock);
        $stockExpired = $medicationRepository->expiredStock();

        return $this->render('medication/index.html.twig', [
            'medications' => $medications,
            'search' => $search,
            'filter' => $filter,
            'inStock' => $inStock,
            'stockExpired' => $stockExpired,
        ]);
    }

    #[Route('/medication/{id}/add_to_cart', name: 'app_medication_add_to_cart')]
    public function addToCart(
        Medication $medication,
        SessionInterface $session,
        EntityManagerInterface $entityManager,
        SampleRepository $sampleRepository,
    ): Response {
        $cart = $session->get('cart', []);
        $samples = $sampleRepository->findBy(['medication' => $medication->getId()]);
        if (count($samples) > 0) {
            $closestSample = null;
            $closestExpiration = null;
            $now = new \DateTime();
            foreach ($samples as $sample) {
                if (!in_array($sample->getId(), $cart) && $sample->getExpiration() > $now && null === $sample->getOrder()) {
                    if (null === $closestExpiration || $sample->getExpiration() < $closestExpiration) {
                        $closestSample = $sample;
                        $closestExpiration = $sample->getExpiration();
                    }
                }
            }
            if (null !== $closestSample) {
                $cart[] = $closestSample->getId();
                $session->set('cart', $cart);
            } else {
                $this->addFlash('warning', 'Aucun échantillon disponible pour ce médicament.');
            }
        } else {
            $this->addFlash('warning', 'Aucun échantillon trouvé pour ce médicament.');
        }

        return $this->redirectToRoute('app_medication');
    }

    #[Route('/cart', name: 'app_cart_show')]
    public function showCart(SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $cart = $session->get('cart', []);
        $samples = [];
        foreach ($cart as $sampleId) {
            $samples[] = $entityManager->getRepository(Sample::class)->find($sampleId);
        }

        return $this->render('medication/cart.html.twig', [
            'samples' => $samples,
        ]);
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove')]
    public function removeSampleFromCart(
        $id,
        SessionInterface $session,
        EntityManagerInterface $entityManager,
    ): Response {
        $cart = $session->get('cart', []);
        $cart = array_diff($cart, [$id]);
        $session->set('cart', array_values($cart));

        return $this->redirectToRoute('app_cart_show');
    }

    #[Route('/cart/clear', name: 'app_cart_clear')]
    public function clearCart(SessionInterface $session): Response
    {
        $session->remove('cart');

        return $this->redirectToRoute('app_cart_show');
    }

    #[Route('/medication/{id}', name: 'app_medication_show', requirements: ['id' => '\d+'])]
    public function show(Medication $medication, Request $request, MedicationRepository $medicationRepository): Response
    {
        $expired = $medicationRepository->expiredStockById($medication->getId())[0]['stockExpired'] ?? 0;
        $stock = $medicationRepository->samplesWithoutOrder($medication->getId())[0]['stock'] - $expired;
        $inStock = 0;
        if (isset($stock) and $stock > 0) {
            $inStock = 1;
        }

        return $this->render('medication/show.html.twig', [
            'medication' => $medication,
            'inStock' => $inStock,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/medication/create', name: 'app_medication_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $medication = new Medication();
        $form = $this->createForm(MedicationType::class, $medication);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('img')->getData();
            if ($file) {
                $blob = file_get_contents($file->getPathname());
                $medication->setImg($blob);
            }
            $entityManager->persist($medication);
            $entityManager->flush();

            return $this->redirectToRoute('app_medication', [
                'id' => $medication->getId(),
            ]);
        }

        return $this->render('medication/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/medication/{id}/update', name: 'app_medication_update', requirements: ['id' => '\d+'])]
    public function update(
        Medication $medication,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $form = $this->createForm(MedicationType::class, $medication);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('img')->getData();
            if ($file) {
                $blob = file_get_contents($file->getPathname());
                $medication->setImg($blob);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_medication_show', [
                'id' => $medication->getId(),
            ]);
        }

        return $this->render('medication/update.html.twig', [
            'form' => $form,
            'medication' => $medication,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/medication/{id}/delete', name: 'app_medication_delete', requirements: ['id' => '\d+'])]
    public function delete(
        Medication $medication,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class, [
                'label' => 'Supprimer',
            ])
            ->add('cancel', SubmitType::class, [
                'label' => 'Annuler',
                'attr' => ['class' => 'btn btn-secondary'],
            ])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('delete')->isClicked()) {
                $entityManager->remove($medication);
                $entityManager->flush();

                return $this->redirectToRoute('app_medication');
            }
            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('app_medication_show', ['id' => $medication->getId()]);
            }
        }

        return $this->render('medication/delete.html.twig', [
            'form' => $form->createView(),
            'medication' => $medication,
        ]);
    }
}
