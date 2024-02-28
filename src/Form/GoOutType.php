<?php

namespace App\Form;

use App\Entity\GoOut;
use App\Entity\Place;
use App\Entity\Site;
use App\Validator\Constraints\LimitDateInscription;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GoOutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la sortie',
                'attr' => ['placeholder' => 'ex: Brique rouge'],
            ])
            ->add('startDateTime', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['min' => (new \DateTime())->format('Y-m-d\TH:i'),],
                'label' => 'Date et heure de début',
            ])
            ->add('duration', NumberType::class, [
                'label' => 'Durée (en minutes)',
                'attr' => ['placeholder' => 'ex: 30'],
            ])
            ->add('limitDateInscription', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d\TH:i'),
                    'max' => '{{ data.form.startDateTime.value }}',
                ],
                'constraints' => [
                    new LimitDateInscription(),
                ],
                'label' => 'Date limite d\'inscription',
            ])
            ->add('maxNbInscriptions', NumberType::class, [
                'label' => 'Nombre maximum d\'inscriptions',
                'attr' => ['placeholder' => 'ex: 10'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'ex: Venez nombreux pour une sortie inoubliable !'],
            ])

            ->add('place', EntityType::class, [
                'class' => Place::class,
                'choice_label' => 'name',
                'label' => 'Lieu',
                'attr' => ['class' => 'place'],
            ])
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'name',
                'label' => 'Site',
                'attr' => ['class' => 'site'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GoOut::class,
        ]);
    }
}
