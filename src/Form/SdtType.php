<?php

namespace App\Form;

use App\Constants\FormType;
use App\Entity\Sdt;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SdtType extends AbstractType
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
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'count',
                IntegerType::class,
                [
                    FormType::LABEL => 'Count of dates',
                    'attr' => ['min' => 1],
                ]
            )
            ->add(
                'createDate',
                DateType::class,
                [
                    'widget' => 'single_text',
                    FormType::LABEL => 'Date then your SDT starts',
                    'attr' => ['value' => (new DateTime())->format('Y-m-d')]
                ]
            )
            ->add(
                'acting',
                ChoiceType::class,
                [FormType::LABEL => 'Person who will change you for this period', 'choices' => $this->actingPeople]
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
            ]
        );
    }
}
