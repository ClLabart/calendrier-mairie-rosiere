<?php

namespace App\Form;

use App\Entity\Date;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateCalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'label_attr' => ['class' => 'm-3'],
                //'attr' => ['class' => 'form-control']
            ])
            ->add('dateStart', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
                'input' => 'datetime_immutable',
                'label_attr' => ['class' => 'm-3'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('dateEnd', DateType::class, [
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'required' => false,
                'label_attr' => ['class' => 'm-3'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('Enregistrer', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary']
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
