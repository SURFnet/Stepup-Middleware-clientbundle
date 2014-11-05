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

namespace Surfnet\StepupMiddlewareClientBundle\Tests\Service;

use Mockery as m;
use Surfnet\StepupMiddlewareClientBundle\Command\Command;
use Surfnet\StepupMiddlewareClientBundle\Command\Metadata;
use Surfnet\StepupMiddlewareClientBundle\Service\CommandService;
use Surfnet\StepupMiddlewareClientBundle\Tests\Service\Fixtures\Root\Command\CauseCommand;
use Surfnet\StepupMiddlewareClientBundle\Tests\Service\Fixtures\Root\Command\Name\Spaced\ZigCommand;
use Surfnet\StepupMiddlewareClientBundle\Tests\Service\Fixtures\Root\Command\WeatherMetadata;

class CommandServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider commands
     * @param string $expectedCommandName
     * @param array $expectedPayload
     * @param array $expectedMetadataPayload
     * @param Command $command
     * @param Metadata|null $metadata
     */
    public function testItExecutesCommands(
        $expectedCommandName,
        $expectedPayload,
        $expectedMetadataPayload,
        Command $command,
        Metadata $metadata = null
    ) {
        $result = m::mock('Surfnet\StepupMiddlewareClient\Service\ExecutionResult')
            ->shouldReceive('isSuccessful')->andReturn(true)
            ->shouldReceive('getUuid')->andReturn('uu-id')
            ->shouldReceive('getProcessedBy')->andReturn('mw-01')
            ->getMock();
        $commandService = m::mock('Surfnet\StepupMiddlewareClient\Service\CommandService')
            ->shouldReceive('execute')->once()->with($expectedCommandName, self::spy($sentUuid), $expectedPayload, $expectedMetadataPayload)->andReturn($result)
            ->getMock();

        $service = new CommandService($commandService, m::mock('Psr\Log\LoggerInterface')->shouldIgnoreMissing());
        $service->execute($command, $metadata);

        $this->assertNotEmpty($command->UUID, 'UUID wasn\'t set during command execution');
        $this->assertInternalType('string', $command->UUID, 'UUID set is not a string');
        $this->assertEquals($sentUuid, $command->UUID, 'UUID set doesn\'t match the UUID sent');
    }

    public function commands()
    {
        return [
            'Non-nested command' => ['Root:Cause', [1], [], new CauseCommand([1])],
            'Nested command' => ['Root:Name.Spaced.Zig', ['all' => 'base'], [], new ZigCommand(['all' => 'base'])],
            'Non-nested command w/ metadata' => ['Root:Cause', [1], ['millibars' => 918.8], new CauseCommand([1]), new WeatherMetadata(918.8)],
        ];
    }

    private static function spy(&$spiedValue)
    {
        return m::on(function ($value) use (&$spiedValue) {
            $spiedValue = $value;

            return true;
        });
    }
}
