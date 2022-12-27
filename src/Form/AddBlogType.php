<?php

namespace App\Form;

use App\Entity\Blog;
use App\Entity\Date;
use App\Repository\DateRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AddBlogType extends AbstractType
{
    private $dateRepository;

    public function __construct(DateRepository $dateRepository)
    {
        $this->dateRepository = $dateRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Le titre de l\'article'],
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'placeholder' => 'La description de l\'article', 'style' => 'height: 100px;'],
            ])
            ->add('image', VichImageType::class, [
                'label' => 'Ajouter une image',
                'required' => true,
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'input-group-text'],
                'allow_delete' => true,
                'delete_label' => 'delete_label',
                'download_label' => 'download_label',
                'download_uri' => true,
                'image_uri' => true,
                'asset_helper' => true,
            ])
            ->add('date', EntityType::class, [
                'label' => 'Lier un évènement',
                'class' => Date::class,
                'choice_label' => 'title',
                'choices' => $this->dateRepository->trieDateQueryBuilder(),
                'attr' => ['class' => 'form-select', 'aria-label' => 'L\'évènement de l\'article', 'style' => 'cursor: pointer;'],
                'empty_data' => 'test'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Créer l\'article de blog'],
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
}
