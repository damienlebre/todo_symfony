<?php

namespace App\Form;

use App\Entity\Category;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' =>[
                    new NotBlank([
                        'message' =>'Entrez un nom pour la nouvelle Catégorie'
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le titre de la catégorie doit contenir au moins {{ limit }} caractéres.',
                        'max' => 70,
                        'maxMessage' => 'Le titre de la catégorie doit contenir au maximum {{ limit }} caractéres.'
                    ])
                ]
            ])
            ->add('image')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
