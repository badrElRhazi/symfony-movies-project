<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MovieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'attr' => array(
                    'class' => 'bg-transparent block border-b-2 
                        w-full h-20 text-6xl outline-none',
                    'placeholder' => 'Enter Title...',   
                ),
                'label' => false
            ])
            ->add('releaseYear', IntegerType::class,  [
                'attr' => [
                    'class' => 'bg-transparent block mt-10 border-b-2 w-full h-20 text-6xl outline-none',
                    'placeholder' => 'Enter Release Year...',
                    'min' => 1900, // Set the minimum value to 1900
                    'max' => date('Y') + 1, // Set the maximum value to the current year + 1
                    'value' => 1900, // Set the initial value to 1900
                ],
                'label' => false,
                'constraints' => [
                    new Range([
                        'min' => 1900,
                        'max' => date('Y') + 1,
                        'notInRangeMessage' => 'Please enter a release year between {{ min }} and {{ max }}.',
                    ]),
                ],
            ])

            ->add('description', TextareaType::class,[
                    'attr' => array(
                        'class' => 'bg-transparent block mt-10 border-b-2 
                            w-full h-60 text-6xl outline-none',
                        'placeholder' => 'Enter Description',
                        
                    ),
                    'label' => false
                ])
            ->add('imagePath', FileType::class,[
                'attr' => array(
                    'class' => 'py-10'                    
                ),
                'label' => false
            ])
            //->add('actors')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
