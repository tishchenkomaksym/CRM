<?php

namespace App\Form;

use App\Entity\PhpDeveloperRiseRequest;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhpDeveloperRiseRequestType extends AbstractType
{
    /**
     * @var User
     */
    private $manager;

    /**
     * @param User $manager
     */
    public function setManager(User $manager): void
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = [];
        $relations = $this->manager->getPhpManagerDeveloperRelations();
        foreach ($relations as $relation) {
            $choices[] = $relation->getPhpDeveloper();
        }

        $builder
            ->add('approved')
//            ->add('createdDate')
            ->add(
                'phpDeveloper',
                EntityType::class,
                [
                    'class' => User::class,
                    'choice_label' => 'email',
                    'choices' => $choices
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PhpDeveloperRiseRequest::class,
        ]);
    }
}
