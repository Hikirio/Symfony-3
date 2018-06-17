<?php

namespace BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',null,['attr' => array(
               'class' => 'form-control')])
            ->add('description',null,['attr' => array(
                'class' => 'form-control')])
            ->add('slug',null,['attr' => array(
                'class' => 'form-control')])
            ->add('createdAt',DateType::class, ['widget' => 'single_text','attr' => array(
                'class' => 'form-control')])
            ->add('updatedAt',DateType::class, ['widget' => 'single_text','attr' => array(
                'class' => 'form-control')])
            ->add('content',TextareaType::class,['attr' => array(
                'class' => 'form-control')])
            ->add('users',null,['attr' => array(
                'class' => 'form-control')])
            ->add('categories',null,['attr' => array(
                'class' => 'form-control')])
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Article'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'blogbundle_blog';
    }


}
