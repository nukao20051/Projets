<?php

namespace App\Controller;

use App\Entity\Sample;
use App\Form\SampleType;
use App\Repository\SampleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SampleController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/sample/{id}', name: 'app_sample', requirements: ['id' => '\d+'])]
    public function index(
        SampleRepository $sampleRepository,
        int $id,
        #[MapQueryParameter] ?string $expirationStart = null,
        #[MapQueryParameter] ?string $expirationEnd = null,
    ): Response {
        $expirationStart = $expirationStart ? new \DateTime($expirationStart) : new \DateTime();

        $expirationEnd = $expirationEnd ? new \DateTime($expirationEnd) : (clone $expirationStart)->modify('+2 years');

        $samples = $sampleRepository->searchByMedication($id, $expirationStart, $expirationEnd);

        return $this->render('sample/index.html.twig', [
            'samples' => $samples,
            'expirationStart' => $expirationStart->format('Y-m-d'),
            'expirationEnd' => $expirationEnd->format('Y-m-d'),
            'medicationId' => $id,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/sample/{id}/update', requirements: ['id' => '\d+'])]
    public function update(
        Sample $sample,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $form = $this->createForm(SampleType::class, $sample);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sample = $form->getData();
            $entityManager->flush();

            return $this->redirectToRoute('app_sample', ['id' => $sample->getMedication()->getId()]);
        }

        return $this->render('sample/update.html.twig',
            [
                'sample' => $sample,
                'form' => $form,
            ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/sample/create')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $sample = new Sample();
        $form = $this->createForm(SampleType::class, $sample, ['action' => 'create']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repeatCount = $form->get('repeatCount')->getData();

            for ($i = 0; $i < $repeatCount; ++$i) {
                $duplicate = clone $sample;
                $entityManager->persist($duplicate);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_sample', ['id' => $sample->getMedication()->getId()]);
        }

        return $this->render('sample/create.html.twig', ['form' => $form]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/sample/{id}/delete', requirements: ['id' => '\d+'])]
    public function delete(Sample $sample,
        EntityManagerInterface $entityManager,
        Request $request): Response
    {
        $form = $this->createFormBuilder($sample)
            ->add('delete', SubmitType::class)
            ->add('cancel', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getClickedButton() === $form->get('delete')) {
                $entityManager->remove($sample);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_sample', ['id' => $sample->getMedication()->getId()]);
        }

        return $this->render('sample/delete.html.twig', ['sample' => $sample, 'form' => $form]);
    }
}
