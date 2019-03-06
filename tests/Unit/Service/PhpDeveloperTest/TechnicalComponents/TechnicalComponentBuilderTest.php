<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 06.03.2019
 * Time: 10:11
 */

namespace App\Service\PhpDeveloperTest\TechnicalComponents;

use App\Entity\PhpDeveloperLevelTest;
use App\Entity\PhpDeveloperLevelTestTechnicalComponent;
use App\Entity\User;
use App\Service\ElasticSearchClient;
use App\Service\PhpDeveloperTest\PhpDeveloperTestBuilderException;
use PHPUnit\Framework\TestCase;

class TechnicalComponentBuilderTest extends TestCase
{

    /**
     * @throws \App\Service\PhpDeveloperTest\PhpDeveloperTestBuilderException
     */
    public function testBuild(): void
    {
        $elasticMock = $this->createMock(ElasticSearchClient::class);
        $elasticMock->expects($this->once())->method('getTimePerComponent')->willReturn(132);
        $builder = New TechnicalComponentBuilder($elasticMock);
        $technicalComponent = new PhpDeveloperLevelTestTechnicalComponent();
        $technicalComponent->setRequiredHours(10);
        $technicalComponent->setJiraName('jiraName');
        $technicalComponent->setName('name');
        $test = new PhpDeveloperLevelTest();
        $test->addTestTechnicalComponent($technicalComponent);

        $assertArray = [(new TechnicalComponent())->setName('name')->setSpendHours(132)->setRequiredHours(10)];
        $this->assertEquals($assertArray, $builder->build((new User())->setEmail('test'), $test));
    }

    /**
     * @throws \App\Service\PhpDeveloperTest\PhpDeveloperTestBuilderException
     */
    public function testBuildUserException(): void
    {
        $elasticMock = $this->createMock(ElasticSearchClient::class);
        $elasticMock->expects($this->never())->method('getTimePerComponent')->willReturn(132);
        $builder = New TechnicalComponentBuilder($elasticMock);
        $technicalComponent = new PhpDeveloperLevelTestTechnicalComponent();
        $technicalComponent->setRequiredHours(10);
        $technicalComponent->setJiraName('jiraName');
        $technicalComponent->setName('name');
        $test = new PhpDeveloperLevelTest();
        $test->addTestTechnicalComponent($technicalComponent);
        $this->expectException(PhpDeveloperTestBuilderException::class);
        $builder->build(new User(), $test);
    }

    /**
     * @throws \App\Service\PhpDeveloperTest\PhpDeveloperTestBuilderException
     */
    public function testBuildComponentException(): void
    {
        $elasticMock = $this->createMock(ElasticSearchClient::class);
        $elasticMock->expects($this->never())->method('getTimePerComponent')->willReturn(132);
        $builder = New TechnicalComponentBuilder($elasticMock);
        $technicalComponent = new PhpDeveloperLevelTestTechnicalComponent();
        $technicalComponent->setRequiredHours(10);
        $technicalComponent->setName('name');
        $test = new PhpDeveloperLevelTest();
        $test->addTestTechnicalComponent($technicalComponent);
        $this->expectException(PhpDeveloperTestBuilderException::class);
        $builder->build((new User())->setEmail('qwe'), $test);
    }
}
