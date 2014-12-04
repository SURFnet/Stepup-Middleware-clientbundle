<?php

/**
 * Copyright 2014 SURFnet bv
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Surfnet\StepupMiddlewareClient\Tests\Service;

use Mockery as m;
use Surfnet\StepupMiddlewareClient\Service\ApiService;

class ApiModelServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testItResources()
    {
        $data = 'My first resource';
        $response = m::mock('GuzzleHttp\Message\ResponseInterface')
            ->shouldReceive('json')->andReturn($data)
            ->shouldReceive('getStatusCode')->andReturn('200')
            ->getMock();
        $guzzle = m::mock('GuzzleHttp\ClientInterface')
            ->shouldReceive('get')->with('/resource', m::any())->once()->andReturn($response)
            ->getMock();

        $service = new ApiService($guzzle);

        $this->assertEquals($data, $service->read('/resource'));
    }

    public function testItFormatsResourceParameters()
    {
        $response = m::mock('GuzzleHttp\Message\ResponseInterface')
            ->shouldReceive('json')->andReturn('My first resource')
            ->shouldReceive('getStatusCode')->andReturn('200')
            ->getMock();
        $guzzle = m::mock('GuzzleHttp\ClientInterface')
            ->shouldReceive('get')->with('/resource/John%2FDoe', m::any())->once()->andReturn($response)
            ->getMock();

        $service = new ApiService($guzzle);
        $service->read('/resource/%s', ['John/Doe']);
    }

    public function testItThrowsWhenMalformedJsonIsReturned()
    {
        $this->setExpectedException('Surfnet\StepupMiddlewareClient\Exception\MalformedResponseException');

        $response = m::mock('GuzzleHttp\Message\ResponseInterface')
            ->shouldReceive('json')->andThrow(new \RuntimeException)
            ->shouldReceive('getStatusCode')->andReturn('200')
            ->getMock();
        $guzzle = m::mock('GuzzleHttp\ClientInterface')
            ->shouldReceive('get')->with('/resource/John%2FDoe', m::any())->once()->andReturn($response)
            ->getMock();

        $service = new ApiService($guzzle);
        $service->read('/resource/%s', ['John/Doe']);
    }

    public function testItReturnsNullWhenTheResourceDoesntExist()
    {
        $response = m::mock('GuzzleHttp\Message\ResponseInterface')
            ->shouldReceive('json')->andReturn(['errors' => ["Requested identity doesn't exist"]])
            ->shouldReceive('getStatusCode')->andReturn('404')
            ->getMock();
        $guzzle = m::mock('GuzzleHttp\ClientInterface')
            ->shouldReceive('get')->with('/identity/abc', m::any())->once()->andReturn($response)
            ->getMock();

        $service = new ApiService($guzzle);

        $this->assertNull($service->read('/identity/abc'), "Resource doesn't exist, yet a non-null value was returned");
    }

    public function testItThrowsWhenTheConsumerIsntAuthorisedToAccessTheResource()
    {
        $this->setExpectedException('Surfnet\StepupMiddlewareClient\Exception\AccessDeniedToResourceException');

        $response = m::mock('GuzzleHttp\Message\ResponseInterface')
            ->shouldReceive('json')->andReturn(['errors' => ["You are not authorised to access this identity"]])
            ->shouldReceive('getStatusCode')->andReturn('403')
            ->getMock();
        $guzzle = m::mock('GuzzleHttp\ClientInterface')
            ->shouldReceive('get')->with('/identity/abc', m::any())->once()->andReturn($response)
            ->getMock();

        $service = new ApiService($guzzle);
        $service->read('/identity/abc');
    }
}
