<?php


namespace App\Form\Recruiting;


use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransferCandidateToEmployeeType extends AbstractType
{

    /**
     * @var User
     */
    private $users = [];

    public function __construct(UserRepository $userRepository)
    {
        foreach($userRepository->todayListUsers() as $user){
            $this->users[$user->getEmail()] = $user->getEmail();
        }

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',ChoiceType::class, [
                    'placeholder' => 'Select User',
                    'choices' => $this->users
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}