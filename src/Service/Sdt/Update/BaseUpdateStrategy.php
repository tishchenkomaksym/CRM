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
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseUpdateStrategy
{
    /**
     * @var MessageBuilderInterface
     */
    private $messageBuilder;
    /**
     * @var \Swift_Mailer
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
     * @throws \Exception
     */
    public function update(): Sdt
    {
        $entityManager = $this->entityManager;
        $entityManager->flush();
        return $this->entity;
    }

    /**
     *
     * @throws \Exception
     */
    public function createArchive(): void
    {
        $archive = new SdtArchive();
        $archive->setReportDate($this->oldEntity->getReportDate());
        $archive->setUser($this->oldEntity->getUser());
        $archive->setCount($this->oldEntity->getCount());
        $createDate = $this->oldEntity->getCreateDate();
        if ($createDate && $createDate instanceof \DateTime) {
            $archive->setCreateDate(\DateTimeImmutable::createFromMutable($createDate));
        } else {
            $archive->setCreateDate(new \DateTimeImmutable());
        }
        $archive->setActing($this->oldEntity->getActing());
        $this->entityManager->persist($archive);
        $this->entityManager->flush();
    }
}
