<?php


namespace App\Form\SDT;


use App\Constants\FormType;
use App\Entity\Sdt;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserSdtType extends AbstractType
{
    private $actingPeople = [];

    public function __construct(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();
        foreach ($users as $user) {
            $name = $user->getName();
            $this->actingPeople[$name] = $name;
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @throws Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $constraints = 'constraints';
        $builder
            ->add(
                'count',
                IntegerType::class,
                [
                    FormType::LABEL => 'Amount of SDT days',
                    'attr' => ['min' => 1],
                    $constraints => [new NotBlank(),]
                ]
            )
            ->add(
                'createDate',
                DateType::class,
                [
                    'widget' => 'single_text',
                    $constraints => [new NotBlank(),],
                    FormType::LABEL => 'Starting date of your SDT',
                ]
            )
            ->add(
                'acting',
                ChoiceType::class,
                [
                    FormType::LABEL => 'Person who will be in charge instead of you for this period',
                    'choices' => $this->actingPeople,
                    $constraints => [new NotBlank(),]
                ]
            )
            ->add(
                'atOwnExpense'
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Sdt::class,
                'required' => true
            ]
        );
    }
}