<?php

namespace App\Controller;

use App\Entity\Reviews;
use App\Form\ReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ReviewController extends AbstractController
{
    /**
     * @Route("/review/new", name="review_new", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function new(Request $request): Response
    {
        // Récupérer l'utilisateur connecté (le client)
        $client = $this->getUser();

        if (!$client) {
            throw $this->createAccessDeniedException('Client non trouvé.');
        }

        // Créer une nouvelle instance de Reviews
        $review = new Reviews();

        // Créer le formulaire en passant l'ID du client associé
        $form = $this->createForm(ReviewType::class, $review);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer la review dans la base de données
            $review->setNomClient($client->getUsername()); // Affecter le nom du client à la review
            $review->setClient($client); // Affecter le client à la review

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($review);
            $entityManager->flush();

            // Rediriger vers une page de succès après la création de la review
            return $this->redirectToRoute('review_success');
        }

        // Afficher le formulaire de création de review
     //   $this->addFlash('success', 'Your feedback was submitted successfully.');

        return $this->render('review/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/review/success", name="review_success", methods={"GET"})
     */
    public function reviewSuccess(): Response
    {
        // Afficher une page de succès après la création de la review
        return $this->render('review/sucess.html.twig');
    }
}