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

namespace Surfnet\StepupMiddlewareClientBundle\Service;

use Psr\Log\LoggerInterface;
use Surfnet\StepupMiddlewareClient\Exception\CommandExecutionFailedException;
use Surfnet\StepupMiddlewareClient\Service\CommandService as LibraryCommandService;
use Surfnet\StepupMiddlewareClient\Service\ExecutionResult;
use Surfnet\StepupMiddlewareClientBundle\Command\Command;
use Surfnet\StepupMiddlewareClientBundle\Command\Metadata;
use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidArgumentException;

class CommandService
{
    /**
     * @var LibraryCommandService
     */
    private $commandService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LibraryCommandService $commandService
     * @param LoggerInterface $logger
     */
    public function __construct(LibraryCommandService $commandService, LoggerInterface $logger)
    {
        $this->commandService = $commandService;
        $this->logger = $logger;
    }

    /**
     * @param Command $command
     * @param Metadata|null $metadata
     * @return ExecutionResult
     */
    public function execute(Command $command, Metadata $metadata = null)
    {
        $commandName = $this->getCommandName($command);
        $payload = $command->serialise();
        $metadataPayload = $metadata ? $metadata->serialise() : [];

        $this->logger->info(
            sprintf("Executing command '%s'", $commandName),
            ['payload' => $payload, 'metadata' => $metadata]
        );

        try {
            $result = $this->commandService->execute($commandName, $payload, $metadataPayload);

            if ($result->isSuccessful()) {
                $this->logger->info(sprintf(
                    "Command '%s' was processed successfully by '%s'",
                    $result->getUuid(),
                    $result->getProcessedBy()
                ));
            } else {
                $this->logger->warning(sprintf('Command could not be executed (%s)', join('; ', $result->getErrors())));
            }
        } catch (CommandExecutionFailedException $e) {
            $this->logger->error(sprintf('Command could not be executed (%s)', $e->getMessage()), ['exception' => $e]);

            return new ExecutionResult(null, null, [$e->getMessage()]);
        }
    }

    /**
     * @param Command $command
     * @return string
     */
    private function getCommandName(Command $command)
    {
        if (!preg_match('~(\\w+)\\\\Command\\\\((\\w+\\\\)*\\w+)Command$~', get_class($command), $commandNameParts)) {
            throw new InvalidArgumentException(
                "Given command's class name cannot be expressed using command name notation."
            );
        }

        $commandName = sprintf('%s:%s', $commandNameParts[1], str_replace('\\', '.', $commandNameParts[2]));

        return $commandName;
    }
}