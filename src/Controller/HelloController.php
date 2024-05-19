<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\HotelRepository;
use App\Repository\TourPackageRepository;

class HelloController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(HotelRepository $hotelRepository, TourPackageRepository $tourPackageRepository): Response
    {
        // Retrieve hotels from the repository
        $hotels = $hotelRepository->findAll();

        // Retrieve tour packages from the repository
        $tourPackages = $tourPackageRepository->findAll();

        // Execute the actions of HotelController and TourPackageController
        $responseHotel = $this->forward('App\Controller\HotelController::yourHotelAction');
        $responseSecond = $this->forward('App\Controller\TourPackageController::yourTourPackageAction');

        // Combine the results or pass them to your view
        $combinedResult = [
            'hotelData' => $responseHotel->getContent(),
            'tourPackageData' => $responseSecond->getContent(),
        ];

        return $this->render('hello/index.html.twig', [
            'hotels' => $hotels, // Pass hotels to the template
            'tourPackages' => $tourPackages, // Pass tour packages to the template
        ]);
    }
}