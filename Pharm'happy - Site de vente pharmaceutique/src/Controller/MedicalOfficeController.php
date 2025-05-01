<?php

namespace App\Controller;

use App\Repository\AddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MedicalOfficeController extends AbstractController
{
    #[Route('/medical_office', name: 'app_medical_office')]
    public function index(): Response
    {
        return $this->render('medical_office/index.html.twig');
    }

    #[Route('/send-email', name: 'app_mo_send_email')]
    public function sendEmail(Request $request, MailerInterface $mailer, Security $security, AddressRepository $addressRepository): Response
    {
        if ($security->isGranted('ROLE_PHARMACY')) {
            $this->addFlash('error', 'Vous êtes déjà affilié à une pharmacie');

            return $this->redirectToRoute('app_person_show', ['id' => $security->getUser()->getId()]);
        }

        $fullName = $request->request->get('fullName');
        $pharmacyName = $request->request->get('pharmacyName');
        $pharmacyAddress = $request->request->get('pharmacyAddress');
        $message = $request->request->get('message');
        $files = $request->files->get('attachments');

        $addresses = $addressRepository->findBy(['person' => $security->getUser()]);

        $adminEmail = 'elias.richarme@etudiant.univ-reims.fr';

        $email = (new Email())
            ->from('no-reply@example.com')
            ->to($adminEmail)
            ->subject("Nouveau message de $fullName pour $pharmacyName")
            ->text("Nom complet : $fullName\nNom de la pharmacie : $pharmacyName\nAdresse de la pharmacie : $pharmacyAddress\n\nMessage :\n$message");

        if (!empty($files)) {
            foreach ($files as $file) {
                if ($file->isValid()) {
                    try {
                        $email->attachFromPath($file->getPathname(), $file->getClientOriginalName());
                    } catch (FileException $e) {
                        return new Response('Erreur lors de la gestion des pièces jointes : '.$e->getMessage(), 500);
                    }
                }
            }
        }

        try {
            $mailer->send($email);

            return $this->render('medical_office/index.html.twig', ['addresses' => $addresses]);
        } catch (\Exception $e) {
            return new Response('Erreur lors de l\'envoi de l\'email : '.$e->getMessage(), 500);
        }
    }

    #[Route('/confirmation', name: 'app_confirmation', methods: ['POST'])]
    public function confirmation(Request $request): Response
    {
        return $this->render('medical_office/confirmation.html.twig');
    }
}
