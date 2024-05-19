<?php

namespace App\Controller;
use App\Entity\ReservationTour;
use App\Entity\ReservationHotel;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Payement; // Updated entity
use App\Form\PaymentType; // Form should already be imported correctly
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class PaymentController extends AbstractController
{
    /**
     * @Route("/payment/new", name="payment_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservationId = $request->query->get('reservationId');
        $reservationType = $request->query->get('type');

        if ($reservationType === 'hotel') {
            $reservation = $entityManager->getRepository(ReservationHotel::class)->find($reservationId);
        } else if ($reservationType === 'tour') {
            $reservation = $entityManager->getRepository(ReservationTour::class)->find($reservationId);
        } else {
            $this->addFlash('error', 'Invalid reservation type.');
            return $this->redirectToRoute('home');
        }

        if (!$reservation) {
            $this->addFlash('error', 'Reservation not found.');
            return $this->redirectToRoute('home');
        }

        $payment = new Payement();
        if ($reservationType === 'hotel') {
            $payment->setPayementHotel($reservation);
            $payment->setPrixTotal($reservation->getTotal());
        } else {
            $payment->setPeyementTour($reservation);
            $payment->setPrixTotal($reservation->getTotal());
        }

        $form = $this->createForm(PaymentType::class, $payment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($payment);
            $entityManager->flush();

            return $this->redirectToRoute('payment_success');
        }

        return $this->render('payement/new.html.twig', [
            'form' => $form->createView(),
            'reservation' => $reservation,
        ]);
    }

    /**
     * @Route("/payment/success", name="payment_success")
     */
    public function success(): Response
    {
        return $this->render('payment/success.html.twig');
    }
}