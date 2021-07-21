<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }


    public function configureFields(string $pageName): iterable
    {
        // retourne les différents types d'affichage d'input des classes d'EasyAdmin
        return [
            TextField::new('name'),

            SlugField::new('slug')
                ->setTargetFieldName('name'),

            ImageField::new('illustration')
                ->setBasePath('uploads/') // Où est-ce qu'on veut mettre les fichiers
                ->setUploadDir('public/uploads/') // Il faut indiquer le chemin complet
                ->setUploadedFileNamePattern('[randomhash].[extension]') // encoder le nom du fichier image par un nombre aléatoire, une chaine de caractère
                ->setRequired(false),

            TextField::new('subtitle'),
            TextareaField::new('description'),
            MoneyField::new('price')->setCurrency('EUR'), // preciser le type de  money
            AssociationField::new('category')

        ];
    }
}
