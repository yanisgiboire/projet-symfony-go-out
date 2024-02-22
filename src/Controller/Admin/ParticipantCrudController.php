<?php

namespace App\Controller\Admin;

use App\Entity\Participant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ParticipantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Participant::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('surname'),
            TextField::new('firstName'),
            TextField::new('email'),
            TextField::new('phoneNumber'),
            BooleanField::new('active'),
            AssociationField::new('site', 'Site')
                ->setFormTypeOption('choice_label', 'name')
                ->setFormTypeOption('by_reference', false),
            AssociationField::new('user')
                ->setFormTypeOption('choice_label', 'username')
                ->setFormTypeOption('by_reference', false),

        ];
    }

}
