<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Person;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AddressController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/address', name: 'app_address')]
    public function index(AddressRepository $addressRepository, Request $request): Response
    {
        /** @var Person $user */
        $user = $this->getUser();
        $addresses = $addressRepository->findByUser($user);

        $returnTo = $request->query->get('return_to');

        return $this->render('address/index.html.twig', [
            'addresses' => $addresses,
            'return_url' => $returnTo,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/address/create', name: 'app_address_create')]
    public function create(
        EntityManagerInterface $entityManager,
        Request $request,
        Security $security,
    ): Response {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        $returnTo = $request->query->get('return_to');

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setPerson($security->getUser());
            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute('app_address', ['return_to' => $returnTo]);
        }

        return $this->render('address/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/address/{id}/update', name: 'app_address_update')]
    public function update(
        Address $address,
        EntityManagerInterface $entityManager,
        Request $request,
    ): Response {
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_address');
        }

        return $this->render('address/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/address/{id}/delete', name: 'app_address_delete')]
    public function delete(
        Address $address,
        EntityManagerInterface $entityManager,
    ): Response {
        if ($address->getOrders()->isEmpty()) {
            $entityManager->remove($address);
            $entityManager->flush();
        } else {
            $this->addFlash('warning', 'Cette adresse est liée à une commande. Il est impossible de la supprimer');
        }

        return $this->redirectToRoute('app_address');
    }
}
