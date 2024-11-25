<?php

namespace App\Form;

use App\Entity\Size;
use App\Entity\SweatShirts;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $product = $options['product'];
        $builder
            
        ->add('size', EntityType::class,[
                'class' => Size::class,
                'label' => 'Taille:',
                'required' => true,
                'multiple' => false,
                'choices' => $product->getSize()
                ]);


            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'product' => []
        ]);


    }
}
