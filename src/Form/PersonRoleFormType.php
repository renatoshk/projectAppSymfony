<?php

namespace App\Form;

use App\Entity\ProjectMember;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonRoleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('projectRole', ChoiceType::class,[
                  'placeholder' => 'Choose an option',
                   'choices' => [
                        'manager'=>'manager',
                        'admin'=> 'admin',
                        'tester'=>'tester',
                   ],
            ])
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProjectMember::class,
        ]);
    }
}
