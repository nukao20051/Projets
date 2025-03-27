<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Sample;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class OrderController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/order', name: 'app_order')]
    public function index(
        OrderRepository $orderRepository,
        Request $request,
        Security $security,
    ): Response {
        $id = $request->get('id', 0);
        $byName = $request->get('byName', '');
        $filter = $request->get('filter', '');
        $orderState = $request->get('orderState', '');

        $orders = $orderRepository->search($id, $byName, $filter, $orderState, $security);

        return $this->render('order/index.html.twig', [
            'orders' => $orders,
            'id' => $id,
            'byName' => $byName,
            'filter' => $filter,
            'orderState' => $orderState,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/order/{id}', name: 'app_order_show', requirements: ['id' => '\d+'])]
    public function show(
        Order $order,
        Security $security,
    ): Response {
        if ($order->getPerson() !== $security->getUser() && !($security->isGranted('ROLE_ADMIN') || $security->isGranted('ROLE_MANAGER'))) {
            $this->addFlash('error', 'Vous ne pouvez pas afficher cette commande, elle ne vous appartient pas');

            return $this->redirectToRoute('app_order');
        }

        return $this->render('order/show.html.twig',
            [
                'order' => $order,
                'samples' => $order->getSamples(),
                'address' => $order->getAddress(),
            ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/order/cart', name: 'app_cart_order')]
    public function orderCart(SessionInterface $session, EntityManagerInterface $entityManager, Request $request): Response
    {
        $cart = $session->get('cart', []);
        if (empty($cart)) {
            return $this->redirectToRoute('app_medication');
        }
        $order = new Order();
        $order->setPerson($this->getUser());
        $order->setOrderState('En préparation');
        $deliveryDate = (new \DateTime('now'))->modify('+5 day');
        $order->setDeliveryDate($deliveryDate);
        $totalPrice = 0;
        foreach ($cart as $sampleId) {
            $sample = $entityManager->getRepository(Sample::class)->find($sampleId);
            $order->addSample($sample);
            $totalPrice += $sample->getMedication()->getPrice();
        }
        if ($this->getUser()->getMedicalOffice()) {
            $totalPrice = $totalPrice * 0.95;
        }

        $order->setTotalPrice($totalPrice);
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            $entityManager->flush();
            $entityManager->persist($order);
            $session->remove('cart');

            return $this->render('order/confirmation.html.twig', ['price' => $totalPrice, 'date' => $deliveryDate]);
        }

        return $this->render('order/form.html.twig', ['form' => $form, 'price' => $totalPrice, 'date' => $deliveryDate]);
    }
}
