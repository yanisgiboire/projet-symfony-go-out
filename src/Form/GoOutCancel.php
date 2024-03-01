<?php
 
namespace App\Form;
 
use App\Entity\GoOut;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
 
class GoOutCancel extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
 
            // je veux afficher des champs mais non modifiables
 
            ->add('reason', null, [
                'label' => "Motif de l'annulation :",
            ]);
 
    }
 
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GoOut::class,
        ]);
    }
}