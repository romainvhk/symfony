<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Tag;


class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('clientName')
            ->add('startDate')
            ->add('checkpointDate')
            ->add('deliveryDate')
            // permet d'insérer des données dans les inputs
            ->add('tags', EntityType::class, [   
                'class' => Tag::class,
                'choice_label' => function(Tag $element) {
                    return "{$element->getName()} (id {$element->getId()})";
                },
                
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
