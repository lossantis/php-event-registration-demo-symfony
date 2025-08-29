<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Registration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Your name',
                'required' => true,
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Work email',
                'required' => true,
                'constraints' => [new Assert\NotBlank(), new Assert\Email()],
            ])
            ->add('plusOne', CheckboxType::class, [
                'label' => 'Bringing a plus one?',
                'required' => false,
            ])
            ->add('numberOfKids', IntegerType::class, [
                'label' => 'Number of kids',
                'required' => false,
                'empty_data' => '0',
                'attr' => ['min' => 0],
                'constraints' => [new Assert\PositiveOrZero()],
            ])
            ->add('numberOfVegetarians', IntegerType::class, [
                'label' => 'Number of vegetarians',
                'required' => false,
                'empty_data' => '0',
                'attr' => ['min' => 0],
                'constraints' => [
                    new Assert\PositiveOrZero(),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Register',
                'attr' => ['class' => 'btn btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Registration::class,
        ]);
    }
}
