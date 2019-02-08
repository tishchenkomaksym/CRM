<?php

namespace App\Form;

use App\Entity\PhpDeveloperLevelTest;
use App\Entity\PhpDeveloperLevelTestPassed;
use App\Service\UserInformationService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhpDeveloperLevelTestPassedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $phpDeveloperLevelTestPassed = $builder->getData();
        $choices = [];
        if ($phpDeveloperLevelTestPassed instanceof PhpDeveloperLevelTestPassed) {
            $choices = UserInformationService::getPhpDeveloperNotPassedTests($phpDeveloperLevelTestPassed->getUser());
        }
        $builder
            ->add(
                'phpDeveloperLevelTest',
                EntityType::class,
                [
                    'class' => PhpDeveloperLevelTest::class,
                    'choice_label' => 'title',
                    'choices' => $choices
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                                   'data_class' => PhpDeveloperLevelTestPassed::class,
                               ]
        );
    }
}
