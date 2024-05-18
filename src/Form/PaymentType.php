<?php

namespace App\Form;

use App\Entity\Payement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\ReservationFlight; // Import de l'entité Classroom
use App\Entity\ReservationHotel; // Import de l'entité Classroom
use App\Entity\ReservationTour; // Import de l'entité Classroom

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NomPrenom')
            ->add('adresse')
            ->add('numtel')
            ->add('methodePayement')
            ->add('numCarte')
            ->add('codeSecurite')
            ->add('prixTotal')
            ->add('payement_hotel_id', EntityType::class, [
                'class' => ReservationHotel::class,
                'choice_label' => 'name',
                'label' => 'Salle de classe',
                'placeholder' => 'Sélectionnez une salle de classe',
            ])     
                ->add('payement_flight_id', EntityType::class, [
                'class' => ReservationFlight::class,
                'choice_label' => 'name',
                'label' => 'Salle de classe',
                'placeholder' => 'Sélectionnez une salle de classe',
            ])
                      ->add('peyement_tour_id', EntityType::class, [
                'class' => ReservationTour::class,
                'choice_label' => 'name',
                'label' => 'Salle de classe',
                'placeholder' => 'Sélectionnez une salle de classe',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payement::class,
        ]);
    }
}