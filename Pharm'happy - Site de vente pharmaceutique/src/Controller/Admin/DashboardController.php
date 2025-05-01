<?php

namespace App\Controller\Admin;

use App\Entity\Address;
use App\Entity\MedicalOffice;
use App\Entity\Medication;
use App\Entity\Order;
use App\Entity\Person;
use App\Entity\Sample;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sae3 01');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Personne', 'fas fa-list', Person::class);
        yield MenuItem::linkToCrud('Échantillon', 'fas fa-list', Sample::class);
        yield MenuItem::linkToCrud('Commande', 'fas fa-list', Order::class);
        yield MenuItem::linkToCrud('Médicaments', 'fas fa-list', Medication::class);
        yield MenuItem::linkToCrud('Pharmacie', 'fas fa-list', MedicalOffice::class);
        yield MenuItem::linkToCrud('Adresse', 'fas fa-list', Address::class);
    }
}
