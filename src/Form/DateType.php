<?php

namespace App\Form;

use App\Entity\Blog;
use App\Entity\Date;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateStart', DateType::class)
            ->add('dateEnd', DateType::class, [
                'required' => false
            ])
            ->add('title')
            ->add('blog', EntityType::class, [
                'class' => Blog::class,
                'choice_label' => 'title',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Date::class,
        ]);
    }
}
