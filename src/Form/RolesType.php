<?php


namespace App\Form;


use App\Entity\User;
use App\Service\AllRolesDataProvider\AllRolesDataProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RolesType extends AbstractType
{

    private $roles;

    public function __construct(AllRolesDataProvider $allRoles)
    {
            $this->roles = $allRoles->getRoles();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('roles', ChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'choices' => $this->roles,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}