<?php

namespace App\Controller;

use App\Form\ReservationTourType;
use Symfony\Component\Security\Core\Security;
use App\Entity\TourPackage;
use App\Entity\ReservationTour;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class TourPackageController extends AbstractController
{
    use TargetPathTrait;

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/tour/package", name="app_tour_package")
     */
    public function index(): Response
    {
        return $this->render('tourPackage/index.html.twig', [
            'controller_name' => 'TourPackageController',
        ]);
    }

    /**
     * @Route("/list", name="tourpackage_list")
     */
    public function list(): Response
    {
        $tourPackages = $this->getDoctrine()->getRepository(TourPackage::class)->findAll();
    
        return $this->render('tourPackage/list.html.twig', [
            'tourPackages' => $tourPackages,
        ]);
    }

    /**
     * @Route("/tour/package/{id}/reserve", name="reserve_tourpackage")
     */
    public function reserve(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();
        if (!$user) {
            $this->saveTargetPath($request->getSession(), 'main', $this->generateUrl('reserve_tourpackage', ['id' => $id]));
            $this->addFlash('error', 'You must be logged in to make a reservation.');
            return $this->redirectToRoute('app_login');
        }
    
        $tourPackage = $entityManager->getRepository(TourPackage::class)->find($id);
    
        if (!$tourPackage) {
            $this->addFlash('error', 'Tour package not found.');
            return $this->redirectToRoute('tourpackage_list');
        }
    
        $reservation = new ReservationTour();
        $reservation->setTourPackage($tourPackage);
        $reservation->setNbrPersonne(1); // Set a default value for number of persons
    
        $form = $this->createForm(ReservationTourType::class, $reservation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $startDate = $form->get('dateDepart')->getData();
            $endDate = $form->get('dateFin')->getData();
            $nbrPersonne = $form->get('nbrPersonne')->getData();
            $total = $tourPackage->getPrix() * $nbrPersonne;
    
            // Set reservation details
            $reservation->setDateDepart($startDate);
            $reservation->setDateFin($endDate);
            $reservation->setNbrPersonne($nbrPersonne);
            $reservation->setTotal($total);
            $reservation->setClient($user);
    
            // Persist reservation
            $entityManager->persist($reservation);
            $entityManager->flush();
    
            // Redirect to payment form with reservation ID
            return $this->redirectToRoute('payment_new', ['reservationId' => $reservation->getId()]);
        }
    
        return $this->render('tourPackage/reserve.html.twig', [
            'form' => $form->createView(),
            'tourPackage' => $tourPackage,
        ]);
    }

    /**
     * @Route("/tourmodifier", name="client_reservation")
     */
    public function clientReservation(EntityManagerInterface $entityManager): Response
    {
        $client = $this->getUser();
        $reservations = $entityManager->getRepository(ReservationTour::class)->findBy(['client' => $client]);
    
        return $this->render('tourPackage/client_reservations.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * @Route("/Reservation/edit/{id}", name="modif_reservationtour", methods={"GET", "POST"})
     */
    public function edit(Request $request, ReservationTour $reservationTour, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ReservationTourType::class, $reservationTour);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Recalculate the total price
            $nbrPersonne = $form->get('nbrPersonne')->getData();
            $total = $reservationTour->getTourPackage()->getPrix() * $nbrPersonne;
            $reservationTour->setTotal($total);
    
            // Save the changes
            $em->flush();
    
            $this->addFlash('success', 'ReservationTour updated successfully');
    
            return $this->redirectToRoute('client_reservation');
        }
    
        return $this->render('tourPackage/edittour.html.twig', [
            'form' => $form->createView(),
            'reservationTour' => $reservationTour,
        ]);
    }
    /**
     * @Route("/reservationTour/delete/{id}", name="delete_reservationTour", methods={"POST"})
     */
    public function deleteReservationTour(Request $request, ReservationTour $reservationTour, EntityManagerInterface $em, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $token = $request->request->get('_token');
    
        if (!$csrfTokenManager->isTokenValid(new CsrfToken('delete' . $reservationTour->getId(), $token))) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }
    
        $em->remove($reservationTour);
        $em->flush();
    
        $this->addFlash('success', 'ReservationTour deleted successfully');
    
        return $this->redirectToRoute('client_reservation');
    }}