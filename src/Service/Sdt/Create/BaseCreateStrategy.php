<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 26.02.2019
 * Time: 13:45
 */

namespace App\Service\Sdt\Create;

use App\Entity\Sdt;
use App\Service\Sdt\Entity\BaseBuilder;
use App\Service\Sdt\MessageBuilderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Swift_Mailer;

class BaseCreateStrategy
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;


    private $entity;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var MessageBuilderInterface
     */
    private $messageBuilder;

    public function __construct(
        Swift_Mailer $mailer,
        Sdt $entity,
        MessageBuilderInterface $messageBuilder,
        ObjectManager $entityManager
    ) {
        $this->mailer = $mailer;
        $this->entity = $entity;
        $this->entityManager = $entityManager;
        $this->messageBuilder = $messageBuilder;
    }

    public function sendEmail(): int
    {
        return $this->mailer->send($this->messageBuilder->build());
    }

    /**
     * @return Sdt
     * @throws \Exception
     */
    public function create(): Sdt
    {
        $sdt = BaseBuilder::build($this->entity);
        $entityManager = $this->entityManager;
        $entityManager->persist($sdt);
        $entityManager->flush();
        return $sdt;
    }
}
