<?php

namespace App\Form;

use App\Entity\GoOut;
use App\Entity\Participant;
use App\Entity\Place;
use App\Entity\Site;
use App\Entity\Status;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GoOutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('startDateTime', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('duration', null, [
                'attr' => [
                    'min' => 1,
                    'max' => 24,
                ],
            ])
            ->add('limitDateInscription', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('maxNbInscriptions', NumberType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 100,
                ],
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'rows' => 5,
                ],
            ])
            ->add('place', EntityType::class, [
                'class' => Place::class,
                'choice_label' => 'name',

            ])
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GoOut::class,
        ]);
    }
}
