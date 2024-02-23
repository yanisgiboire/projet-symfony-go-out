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

class GoOutCancel extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            // je veux afficher des champs mais non modifiables


            ->add('name', null, [
                'disabled' => true,
            ])
            ->add('reason');


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GoOut::class,
        ]);
    }
}
