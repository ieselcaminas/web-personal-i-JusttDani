<?php

namespace App\Form;

use App\Entity\Profesor;
use App\Entity\Instituto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProfesorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('telefono')
            ->add('email', EmailType::class, array('label' => 'Correo electrónico'))
            ->add('instituto', EntityType::class, [
                'class' => Instituto::class,
                'choice_label' => 'nombre',
            ])
            ->add('save', SubmitType::class, array('label' => 'Enviar'));
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profesor::class,
        ]);
    }
}
