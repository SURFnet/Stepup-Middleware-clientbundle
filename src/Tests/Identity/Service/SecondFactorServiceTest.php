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

namespace Surfnet\StepupMiddlewareClient\Tests\Identity\Service;

use Mockery as m;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\SecondFactor;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\UnverifiedSecondFactor;
use Surfnet\StepupMiddlewareClientBundle\Identity\Service\SecondFactorService;

class SecondFactorServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testItFindsSecondFactorsByIdentity()
    {
        $identityId = 'a';

        $secondFactorData = [
            [
                "id" => "769a6649-b3e8-4dd4-8715-2941f947a016",
                "type" => "yubikey",
                "second_factor_identifier" => "ccccccbtbhnh"
            ]
        ];
        $libraryService = m::mock('Surfnet\StepupMiddlewareClient\Identity\Service\SecondFactorService')
            ->shouldReceive('findByIdentity')->with($identityId)->once()->andReturn($secondFactorData)
            ->getMock();
        $violations = m::mock('Symfony\Component\Validator\ConstraintViolationListInterface')
            ->shouldReceive('count')->with()->once()->andReturn(0)
            ->getMock();
        $validator = m::mock('Symfony\Component\Validator\Validator\ValidatorInterface')
            ->shouldReceive('validate')->once()->andReturn($violations)
            ->getMock();

        $service = new SecondFactorService($libraryService, $validator);
        $secondFactors = $service->findByIdentity($identityId);

        /** @var SecondFactor[] $expectedSecondFactors */
        $expectedSecondFactors = [new SecondFactor()];
        $expectedSecondFactors[0]->id = $secondFactorData[0]['id'];
        $expectedSecondFactors[0]->type = $secondFactorData[0]['type'];
        $expectedSecondFactors[0]->secondFactorIdentifier = $secondFactorData[0]['second_factor_identifier'];

        $this->assertEquals($expectedSecondFactors, $secondFactors);
    }

    public function testItFindsUnverifiedSecondFactorsByIdentity()
    {
        $identityId = 'a';

        $secondFactorData = [
            'collection' => ['total_items' => 1, 'page' => 1, 'page_size' => 25],
            'items' => [
                [
                    "id" => "769a6649-b3e8-4dd4-8715-2941f947a016",
                    "type" => "yubikey",
                    "second_factor_identifier" => "ccccccbtbhnh",
                ]
            ],
        ];
        $libraryService = m::mock('Surfnet\StepupMiddlewareClient\Identity\Service\SecondFactorService')
            ->shouldReceive('findUnverifiedByIdentity')->with($identityId)->once()->andReturn($secondFactorData)
            ->getMock();
        $violations = m::mock('Symfony\Component\Validator\ConstraintViolationListInterface')
            ->shouldReceive('count')->with()->once()->andReturn(0)
            ->getMock();
        $validator = m::mock('Symfony\Component\Validator\Validator\ValidatorInterface')
            ->shouldReceive('validate')->once()->andReturn($violations)
            ->getMock();

        $service = new SecondFactorService($libraryService, $validator);
        $secondFactors = $service->findUnverifiedByIdentity($identityId);

        $expectedSecondFactor = new UnverifiedSecondFactor();
        $expectedSecondFactor->id = $secondFactorData['items'][0]['id'];
        $expectedSecondFactor->type = $secondFactorData['items'][0]['type'];
        $expectedSecondFactor->secondFactorIdentifier = $secondFactorData['items'][0]['second_factor_identifier'];

        $this->assertEquals([$expectedSecondFactor], $secondFactors->getElements());
    }
}
