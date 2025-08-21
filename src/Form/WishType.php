<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Wish;
use App\Repository\CategoryRepository;
use App\Repository\WishRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label' => 'Nom',
                'required' => true,
            ])
            ->add('description', TextareaType::class,[
                'label' => 'Description',
                'required' => false,
            ])
            ->add('author', TextType::class,[
                'label' => 'Auteur',
                'required' => true,
            ])
            ->add('categories', EntityType::class, [
                'placeholder' => '--Choose a category--',
                'multiple' => true,
                'expanded' => true,
                'class' => Category::class,
                'choice_label' => 'name',

                'query_builder' => function (CategoryRepository $repo ) {
                    return $repo->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');

                }
            ])
            ->add('submit', SubmitType::class);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
