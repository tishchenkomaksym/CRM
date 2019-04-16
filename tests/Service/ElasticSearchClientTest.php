<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 05.03.2019
 * Time: 15:39
 */

namespace App\Service;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ElasticSearchClientTest extends KernelTestCase
{
    /**
     * @var MockObject|ClientBuilder
     */
    private $clientBuilder;

    protected function setUp()
    {
        $this->clientBuilder = $this->createMock(ClientBuilder::class);
    }

    /**
     * @dataProvider dataProviderGetTimePerComponent
     * @param $value
     */
    public function testGetTimePerComponent($value)
    {
        $this->clientBuilder->expects($this->once())
                      ->method('setHosts')
                      ->willReturn($this->clientBuilder);

        $client = $this->createMock(Client::class);
        $returnValue['aggregations']['effectiveTime']['value'] = $value;
        $client->expects($this->once())
               ->method('search')
               ->willReturn($returnValue);
        $this->clientBuilder->expects($this->once())
                      ->method('build')
                      ->willReturn($client);
        $client = new ElasticSearchClient($this->clientBuilder, 'qwe');
        $this->assertEquals($value, $client->getTimePerComponent('php', 'ivan.melnichuk'));
    }

    /**
     * @dataProvider dataProviderGetTimePerComponent
     * @param $value
     * @throws \Exception
     */
    public function testGetTimeFromDateToDate($value)
    {
        $this->clientBuilder->expects($this->once())
                            ->method('setHosts')
                            ->willReturn($this->clientBuilder);

        $client = $this->createMock(Client::class);
        $returnValue['aggregations']['time']['value'] = $value;
        $client->expects($this->once())
               ->method('search')
               ->willReturn($returnValue);
        $this->clientBuilder->expects($this->once())
                            ->method('build')
                            ->willReturn($client);
        $client = new ElasticSearchClient($this->clientBuilder, 'qwe');
        $this->assertEquals($value, $client->getTimeFromDateToDate(new \DateTime(), new \DateTime(), 'ivan.melnichuk'));
    }

    /**
     * @dataProvider dataProviderGetTimePerComponent
     * @param $value
     * @throws \Exception
     */
    public function testGetEffectiveTimePerUser($value)
    {
        $this->clientBuilder->expects($this->once())
                            ->method('setHosts')
                            ->willReturn($this->clientBuilder);

        $client = $this->createMock(Client::class);
        $returnValue['aggregations']['effectiveTime']['value'] = $value;
        $client->expects($this->once())
               ->method('search')
               ->willReturn($returnValue);
        $this->clientBuilder->expects($this->once())
                            ->method('build')
                            ->willReturn($client);
        $client = new ElasticSearchClient($this->clientBuilder, 'qwe');
        $this->assertEquals($value, $client->getEffectiveTimePerUserDate('ivan.melnichuk', new \DateTime()));
    }

    public function dataProviderGetTimePerComponent(): array
    {
        return [
            [0],
            [132.22000004723668],
            [null]
        ];
    }

//
//    public function testGetWorkLogTimePerDateRange()
//    {
//
//    }


}
