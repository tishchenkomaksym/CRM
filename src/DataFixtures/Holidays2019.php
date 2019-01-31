<?php

namespace App\DataFixtures;

use App\Entity\Holiday;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class Holidays2019 extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $format = 'Y-m-d';
        $holiday = new Holiday();
        $holiday->setTitle('Новый год');
        $date = \DateTime::createFromFormat($format, '2019-01-01');
        $holiday->setDate($date);
        $manager->persist($holiday);
        $holiday = new Holiday();
        $holiday->setTitle('Рождество Христово');
        $date = \DateTime::createFromFormat($format, '2019-01-07');
        $holiday->setDate($date);
        $manager->persist($holiday);
        $holiday = new Holiday();
        $holiday->setTitle('Международный женский день');
        $date = \DateTime::createFromFormat($format, '2019-03-08');
        $holiday->setDate($date);
        $manager->persist($holiday);
        $holiday = new Holiday();
        $holiday->setTitle('Пасха');
        $date = \DateTime::createFromFormat($format, '2019-04-29');
        $holiday->setDate($date);
        $manager->persist($holiday);
        $holiday = new Holiday();
        $holiday->setTitle('Троица');
        $date = \DateTime::createFromFormat($format, '2019-06-17');
        $holiday->setDate($date);
        $manager->persist($holiday);
        $holiday = new Holiday();
        $holiday->setTitle('День Конституции Украины');
        $date = \DateTime::createFromFormat($format, '2019-06-28');
        $holiday->setDate($date);
        $manager->persist($holiday);
        $holiday = new Holiday();
        $holiday->setTitle('День независимости Украины');
        $date = \DateTime::createFromFormat($format, '2019-08-26');
        $holiday->setDate($date);
        $manager->persist($holiday);
        $manager->flush();
    }
}
