<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    #[Route('/about', name: 'app_about')]
    public function index(): Response
    {
        return $this->render('about/index.html.twig', [
        ]);
    }

    #[Route('/legal-notice', name: 'app_about_legal_notice')]
    public function legalNotice(): Response
    {
        return $this->render('about/legal-notice.html.twig', [
        ]);
    }

    #[Route('/privacy-policy', name: 'app_about_privacy_policy')]
    public function privacyPolicy(): Response
    {
        return $this->render('about/privacy-policy.html.twig', [
        ]);
    }

    #[Route('/terms-and-condition', name: 'app_about_terms_of_service')]
    public function termsOfService(): Response
    {
        return $this->render('about/terms-and-condition.html.twig', [
        ]);
    }

    #[Route('/cookie-policy', name: 'app_about_cookie_policy')]
    public function cookiePolicy(): Response
    {
        return $this->render('about/cookie-policy.html.twig', [
        ]);
    }

    #[Route('/faq', name: 'app_about_faq')]
    public function faq(): Response
    {
        return $this->render('about/faq.html.twig', [
        ]);
    }
}
