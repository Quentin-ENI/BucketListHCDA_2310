<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Wish;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de votre souhait'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de votre souhait'
            ])
            ->add('thumbnail', HiddenType::class)
            ->add('thumbnailFile', FileType::class, [
                'label' => 'Image',
                'required' => false,
                 'mapped' => false,
                 'constraints' => [
                     new Image([
                         'maxSize' => '1024k',
                         'maxSizeMessage' => 'La taille de l\'image est trop grande',
                         'mimeTypes' => [
                             'image/jpg',
                             'image/png'
                         ],
                         'mimeTypesMessage' => 'Le format de l\'image n\'est pas valide'
                     ])
                 ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => '-- Sélectionnez la catégorie --'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter un Souhait'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
