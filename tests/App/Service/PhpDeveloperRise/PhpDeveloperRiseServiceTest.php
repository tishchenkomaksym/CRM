<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 11.02.2019
 * Time: 12:53
 */

namespace App\Service\PhpDeveloperRise;

use App\Entity\PhpDeveloperLevel;
use App\Entity\PhpDeveloperRiseRequest;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PhpDeveloperRiseServiceTest extends KernelTestCase
{
    protected $rise;
    /**
     * @var ObjectManager
     */
    protected $entityManager;
    protected $user;
    protected $riseRequest;

    /**
     * @throws \Exception
     */
    protected function setUp()
    {
        parent::setUp();
        $kernel = self::bootKernel();
        // return the output, don't use if you used NullOutput()


        $this->entityManager = $kernel->getContainer()
                                      ->get('doctrine')
                                      ->getManager();
        $this->user = $this->refreshUser();
        $this->riseRequest = $riseRequest = new PhpDeveloperRiseRequest();
        $riseRequest->setPhpDeveloper($this->user);
        $riseRequest->setApproved(true);
        $riseRequest->setCreatedDate(new \DateTime());
        $this->entityManager->persist($riseRequest);
        $this->entityManager->flush();
    }

    /**
     * @throws Exception\WrongPhpDeveloperConfiguration
     */
    public function testProcessRiseUp()
    {
        $updatedUser = PhpDeveloperRiseService::processRiseUp($this->riseRequest);
        $this->assertEquals(
            'PHP Junior Level 2',
            $updatedUser->getPhpDeveloperLevelRelation()->getPhpDeveloperLevel()->getTitle()
        );

    }

    public function tearDown()
    {
        $this->refreshUser();
        if (!empty($this->riseRequest)) {
            $this->entityManager->remove($this->riseRequest);
        }
        $this->entityManager->flush();
    }

    private function refreshUser()
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var PhpDeveloperLevel $phpDeveloperLevel */
        $phpDeveloperLevel = $this->entityManager->getRepository(PhpDeveloperLevel::class)->findOneBy(
            ['title' => 'PHP Junior Level 1']
        );
        /** @var User $user */
        $user = $userRepository->findOneBy(['email' => 'junior1@onyx.com']);

        $user->getPhpDeveloperLevelRelation()->setPhpDeveloperLevel($phpDeveloperLevel);
        $this->entityManager->flush();
        return $user;
    }
}
