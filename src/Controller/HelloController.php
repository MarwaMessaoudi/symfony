<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\HotelRepository;
use App\Repository\TourPackageRepository;
use App\Entity\Reviews;
use App\Form\ReviewType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class HelloController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET", "POST"})
     */
    public function index(HotelRepository $hotelRepository, TourPackageRepository $tourPackageRepository, Request $request, Security $security): Response
    {    
        $review = new Reviews();
        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the current user
            $client = $security->getUser();
            if ($client) {
                $review->setNomClient($client->getUsername());
                $review->setClient($client);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($review);
                $entityManager->flush();

                // Add a success message
                $this->addFlash('success', 'Your feedback was submitted successfully.');

                // Redirect to avoid form resubmission
                return $this->redirectToRoute('home');
            } else {
                throw $this->createAccessDeniedException('Client non trouvé.');
            }
        }

        // Retrieve hotels from the repository
        $hotels = $hotelRepository->findAll();

        // Retrieve tour packages from the repository
        $tourPackages = $tourPackageRepository->findAll();

        // Combine the results or pass them to your view
        return $this->render('hello/index.html.twig', [
            'form' => $form->createView(),
            'hotels' => $hotels, // Pass hotels to the template
            'tourPackages' => $tourPackages, // Pass tour packages to the template
        ]);
    }
}
