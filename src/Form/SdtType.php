<?php

namespace App\Form;

use App\Entity\Sdt;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SdtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('count', IntegerType::class,['label' => 'Count of dates'])
            ->add('createDate', DateType::class, ['widget' => 'single_text','label' => 'Date then your SDT starts'])
            ->add('acting', TextType::class, ['label' => 'Person who will change you for this period'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sdt::class,
        ]);
    }
}
