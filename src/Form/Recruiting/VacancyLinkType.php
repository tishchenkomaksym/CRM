<?php

namespace App\Form\Recruiting;

use App\Entity\VacancyLink;
use http\Url;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VacancyLinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('link', UrlType::class,[
                    'attr' => ['placeholder' => 'Вставьте ссылку на вакансию']
                ]
            )
            ->add('letterText', null, [
                    'attr' => ['placeholder' => 'Вставьте текст рассылки']
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VacancyLink::class,
        ]);
    }
}
