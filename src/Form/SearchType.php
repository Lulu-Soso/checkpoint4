<?php

namespace App\Form;

use App\Classe\Search;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    // Créer le formulaire
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('string', TextType::class, [
                'label' => false,
                'required' => false, // non car on peut simplement chercher des catégories
                'attr' => [
                    'placeholder' => 'Votre recherche ...',
                    'class' => 'form-control-sm' // réduire la taille de l'input avec la class de bootstrap
                ]
            ])
            ->add('categories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'multiple' => true, // pouvoir sélectionner plusieurs valeurs
                'expanded' => true // permet d'avoir une vue en checkbox, pour pouvoir sélectionner plusieurs valeurs
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Filtrer',
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
            ]);
    }

    // fonction qui permet de configurer des options
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET', // Je veux que les données du formulaire transite par l'url, permet de copier-coller et de partager
            'crsf_protection' => false // On le désactive car pas besoin de sécurité important, pas besoin de crypting
        ]);
    }

    public function getBlockPrefix()
    {
        // On retourne rien pour éviter d'avoir un tableau avec des valeurs dedans 
        // et d'avoir des préfixes au nom de la class dans l'url
        return '';
    }
}
