<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 26.02.2019
 * Time: 13:45
 */

namespace App\Service\Sdt\Update;

use App\Entity\Sdt;
use App\Entity\SdtArchive;
use App\Service\Sdt\MessageBuilderInterface;
use App\Service\SdtArchive\SdtArchiveBuilderFromSdt;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Exception;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseUpdateStrategy
{
    /**
     * @var MessageBuilderInterface
     */
    private $messageBuilder;
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    private $entity;
    /**
     * @var AbstractController
     */
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var Sdt
     */
    private $oldEntity;

    public function __construct(
        Swift_Mailer $mailer,
        Sdt $newEntity,
        Sdt $oldEntity,
        MessageBuilderInterface $messageBuilder,
        ObjectManager $entityManager
    ) {
        $this->mailer = $mailer;
        $this->entity = $newEntity;
        $this->entityManager = $entityManager;
        $this->messageBuilder = $messageBuilder;
        $this->oldEntity = $oldEntity;
    }

    public function sendEmail(): int
    {
        return $this->mailer->send($this->messageBuilder->build());
    }

    /**
     * @return Sdt
     * @throws Exception
     */
    public function update(): Sdt
    {
        $entityManager = $this->entityManager;
        $entityManager->flush();
        return $this->entity;
    }

    /**
     *
     * @throws Exception
     */
    public function createArchive(): SdtArchive
    {
        $archive = new SdtArchive();
        $builder = new SdtArchiveBuilderFromSdt();
        $archive = $builder->build($this->oldEntity, $archive);
        $this->entityManager->persist($archive);
        $this->entityManager->flush();
        return $archive;
    }
}
