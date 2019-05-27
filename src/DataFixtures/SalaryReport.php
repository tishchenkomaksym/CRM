<?php

namespace App\DataFixtures;

use App\Entity\SalaryReportInfo;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SalaryReport extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $salaryReport = new SalaryReportInfo();

        $dateTime = new DateTime();
        $dateTime->setDate(2019, 02, 22);
        $salaryReport->setCreateDate(\DateTimeImmutable::createFromMutable($dateTime));
        $manager->persist($salaryReport);
        $manager->flush();
    }
}
