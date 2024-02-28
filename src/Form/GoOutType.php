<?php

namespace App\Form;

use App\Entity\GoOut;
use App\Entity\Participant;
use App\Entity\Place;
use App\Entity\Site;
use App\Entity\Status;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Validator\Constraints\LimitDateInscription;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
class GoOutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('startDateTime', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['min' => (new \DateTime())->format('Y-m-d\TH:i')],
            ])
            ->add('duration')
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
            ])
            ->add('maxNbInscriptions')
            ->add('description', null, [
                'attr' => ['rows' => 5],
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
