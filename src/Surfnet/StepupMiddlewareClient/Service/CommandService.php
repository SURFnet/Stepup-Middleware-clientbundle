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

namespace Surfnet\StepupMiddlewareClient\Service;

use GuzzleHttp\ClientInterface;
use Rhumsaa\Uuid\Uuid;
use Surfnet\StepupMiddlewareClient\Exception\CommandExecutionFailedException;
use Surfnet\StepupMiddlewareClient\Exception\InvalidArgumentException;

class CommandService
{
    /**
     * @var ClientInterface
     */
    private $guzzleClient;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @param ClientInterface $guzzleClient A Guzzle client preconfigured with the command URL.
     * @param string $username
     * @param string $password
     */
    public function __construct(ClientInterface $guzzleClient, $username, $password)
    {
        if (!is_string($username)) {
            InvalidArgumentException::invalidType('string', 'username', $username);
        }

        if (!is_string($password)) {
            InvalidArgumentException::invalidType('string', 'password', $password);
        }

        $this->guzzleClient = $guzzleClient;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param string $commandName
     * @param string $uuid
     * @param array $payload
     * @param array $metadata
     * @return ExecutionResult
     * @throws CommandExecutionFailedException
     */
    public function execute($commandName, $uuid, array $payload, array $metadata = [])
    {
        $this->assertIsValidCommandName($commandName);
        InvalidArgumentException::invalidType('string', 'uuid', $uuid);

        $command = [
            'name' => $commandName,
            'uuid' => $uuid,
            'payload' => $payload,
        ];

        $body = ['command' => $command];

        if (count($metadata) > 0) {
            $body['meta'] = $metadata;
        }

        $requestOptions = [
            'json'       => $body,
            'exceptions' => false,
            'auth'       => [$this->username, $this->password, 'basic'],
            'headers'    => ['Accept' => 'application/json'],
        ];
        $httpResponse = $this->guzzleClient->post(null, $requestOptions);

        try {
            $response = $httpResponse->json();
        } catch (\RuntimeException $e) {
            throw new CommandExecutionFailedException(
                'Server response could not be decoded as it isn\'t valid JSON.',
                0,
                $e
            );
        }

        return $httpResponse->getStatusCode() == 200
            ? $this->createSuccessfulExecuteResult($uuid, $response)
            : $this->createFailedExecuteResult($uuid, $response);
    }

    /**
     * @param mixed $commandName
     * @throws InvalidArgumentException
     */
    private function assertIsValidCommandName($commandName)
    {
        if (!is_string($commandName)) {
            InvalidArgumentException::invalidType('string', 'command', $commandName);
        }

        if (!preg_match('~^[a-z0-9_]+:([a-z0-9_].)*[a-z0-9_]+$~i', $commandName)) {
            throw new InvalidArgumentException(
                'Command must be formatted AggregateRoot:Command or AggregateRoot:Name.Space.Command'
            );
        }
    }

    /**
     * @param string $uuid
     * @param mixed $response
     * @return ExecutionResult
     */
    private function createSuccessfulExecuteResult($uuid, $response)
    {
        if (!isset($response['command'])) {
            throw new CommandExecutionFailedException('Unexpected response format: command key missing.');
        }

        if ($response['command'] !== $uuid) {
            throw new CommandExecutionFailedException("Unexpected response: returned command UUID doesn't match ours.");
        }

        if (!isset($response['processed_by'])) {
            throw new CommandExecutionFailedException('Unexpected response format: processed_by key missing.');
        }

        if (!is_string($response['processed_by'])) {
            throw new CommandExecutionFailedException('Unexpected response format: processed_by not a string.');
        }

        return new ExecutionResult($uuid, $response['processed_by'], []);
    }

    /**
     * @param string $uuid
     * @param mixed $response
     * @return ExecutionResult
     */
    private function createFailedExecuteResult($uuid, $response)
    {
        if (!isset($response['errors'])) {
            throw new CommandExecutionFailedException('Unexpected response format: errors key missing.');
        }

        if (!is_array($response['errors'])) {
            throw new CommandExecutionFailedException('Unexpected response format: errors not an array.');
        }

        return new ExecutionResult($uuid, null, $response['errors']);
    }
}
