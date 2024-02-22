<?php

namespace App\Form;

use App\Entity\GoOut;
use App\Entity\Participant;
use App\Entity\ParticipantGoOut;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantGoOutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('participant', EntityType::class, [
                'class' => Participant::class,
'choice_label' => 'id',
            ])
            ->add('goOut', EntityType::class, [
                'class' => GoOut::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ParticipantGoOut::class,
        ]);
    }
}
