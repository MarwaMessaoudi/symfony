<?php

namespace App\Controller;
use App\Entity\Hotel;
use Symfony\Component\Security\Core\Security;
use App\Entity\TourPackage;

use App\Entity\ReservationHotel;
use App\Form\ReservationHotelFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class HotelController extends AbstractController
{


    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }



    /**
     * @Route("/hotel", name="app_hotel")
     */
    public function index(): Response
    {
        return $this->render('hotel/index.html.twig', [
            'controller_name' => 'HotelController',
        ]);
    }

 
 


 /**
     * @Route("/accueil", name="hotel_list")
     */
    public function list(): Response
{
    $hotels = $this->getDoctrine()->getRepository(Hotel::class)->findAll();

    return $this->render('hotel/hotel_list.html.twig', [
        'hotels' => $hotels,
    ]);
}
    
      /**
 * @Route("/hotel/{id}/reserve", name="reserve_hotel", methods={"GET", "POST"})
 */
public function reserve(Request $request, Hotel $hotel, EntityManagerInterface $entityManager): Response
{
    $user = $this->security->getUser();
    if (!$user) {
        $this->addFlash('error', 'You must be logged in to make a reservation.');
        return $this->redirectToRoute('app_login');
    }

    $reservation = new ReservationHotel();
    $reservation->setHotel($hotel);
    $reservation->setClient($user);

    $form = $this->createForm(ReservationHotelFormType::class, $reservation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $totalPrice = $reservation->getNbrChambre() * $hotel->getPrix();
        $reservation->setTotal($totalPrice);

        $entityManager->persist($reservation);
        $entityManager->flush();

        $this->addFlash('success', 'Hôtel réservé avec succès.');

        return $this->redirectToRoute('client_reservation');
    }

    return $this->render('hotel/reserve.html.twig', [
        'form' => $form->createView(),
        'hotel' => $hotel,
    ]);
}



/**
     * @Route("/modifier", name="client_reservation")
     */
    public function clientReservation(): Response
    {
        $client = $this->getUser(); // Supposons que le client est connecté
        $reservation = $this->getDoctrine()->getRepository(ReservationHotel::class)->findBy(['client' => $client]);

        return $this->render('hotel/reservation_modifier.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * @Route("/reservation/modifier/{id}", name="edit_reservation", methods={"GET", "POST"})
     */
    public function editReservation(Request $request, ReservationHotel $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationHotelFormType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Réservation modifiée avec succès.');

            return $this->redirectToRoute('client_reservation'); // Redirige vers la page de réservations du client
        }

        return $this->render('hotel/edit_reservation.html.twig', [
            'form' => $form->createView(),
            'reservation' => $reservation,
        ]);
    }



 /**
     * @Route("/supprimer", name="client_reservations")
     */
    public function clientReservations(): Response
    {
        $client = $this->getUser(); // Supposons que le client est connecté
        $reservations = $this->getDoctrine()->getRepository(ReservationHotel::class)->findBy(['client' => $client]);

          return $this->render('hotel/client_reservationss.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * @Route("/reservation/{id}/delete", name="delete_reservation", methods={"POST"})
     */
    public function deleteReservation(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $reservation = $entityManager->getRepository(ReservationHotel::class)->find($id);

        if (!$reservation) {
            throw $this->createNotFoundException('La réservation n\'existe pas.');
        }

        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();

            $this->addFlash('success', 'Réservation supprimée avec succès.');
        }

        return $this->redirectToRoute('client_reservation'); // Redirige vers la page de réservations du client
    }


}
    
