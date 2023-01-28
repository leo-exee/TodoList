<?php

namespace App\Form;

use App\Entity\Action;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control', 'placeholder' => '...', 'maxlength' => '255']
            ])
            ->add('description',TextareaType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('status', ChoiceType::class,[
                'choices'  => [
                    'To do' => 'To do',
                    'In progress' => 'In progress',
                    'Completed' => 'Completed',
                ],
                'attr' => ['class' => 'form-control'],
                'required' => true
            ])
            ->add('picture', FileType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Action::class,
        ]);
    }
}
