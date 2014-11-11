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
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\Identity;
use Surfnet\StepupMiddlewareClientBundle\Identity\Service\IdentityService;

class IdentityServiceTests extends \PHPUnit_Framework_TestCase
{
    public function testItGetsAnIdentity()
    {
        $id = 'a';
        $nameId = 'b';

        $libraryService = m::mock('Surfnet\StepupMiddlewareClient\Identity\Service\IdentityService')
            ->shouldReceive('get')->with($id)->once()->andReturn(['id' => $id, 'name_id' => $nameId])
            ->getMock();
        $violations = m::mock('Symfony\Component\Validator\ConstraintViolationListInterface')
            ->shouldReceive('count')->with()->once()->andReturn(0)
            ->getMock();
        $validator = m::mock('Symfony\Component\Validator\Validator\ValidatorInterface')
            ->shouldReceive('validate')->once()->andReturn($violations)
            ->getMock();

        $service = new IdentityService($libraryService, $validator);
        $identity = $service->get($id);

        $expectedIdentity = new Identity();
        $expectedIdentity->id = $id;
        $expectedIdentity->nameId = $nameId;

        $this->assertEquals($expectedIdentity, $identity);
    }

    public function testItValidatesTheIdentity()
    {
        $this->setExpectedException('Surfnet\StepupMiddlewareClientBundle\Exception\InvalidResponseException');

        $id = 'a';
        $nameId = 'b';

        $libraryService = m::mock('Surfnet\StepupMiddlewareClient\Identity\Service\IdentityService')
            ->shouldReceive('get')->with($id)->once()->andReturn(['id' => $id, 'name_id' => $nameId])
            ->getMock();
        $violations = m::mock('Symfony\Component\Validator\ConstraintViolationListInterface')
            ->shouldReceive('count')->with()->once()->andReturn(1)
            ->getMock();
        $validator = m::mock('Symfony\Component\Validator\Validator\ValidatorInterface')
            ->shouldReceive('validate')->once()->andReturn($violations)
            ->getMock();

        $service = new IdentityService($libraryService, $validator);
        $service->get($id);
    }
}
