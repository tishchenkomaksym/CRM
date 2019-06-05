<?php


namespace App\Form\SDT;


use Exception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Security;

class TomSdtType extends AbstractType
{
    /**
     * @var UserSdtType
     */
    private $userSdtType;
    /**
     * @var Security
     */
    private $security;

    public function __construct(UserSdtType $userSdtType, Security $security)
    {
        $this->userSdtType = $userSdtType;
        $this->security = $security;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @throws Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->userSdtType->buildForm($builder, $options);
        if ($this->security->isGranted('ROLE_TOM')) {
            $builder->add('user');
        }
    }
}