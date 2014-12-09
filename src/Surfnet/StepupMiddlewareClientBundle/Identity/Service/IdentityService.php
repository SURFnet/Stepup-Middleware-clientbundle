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

namespace Surfnet\StepupMiddlewareClientBundle\Identity\Service;

use Surfnet\StepupMiddlewareClient\Identity\Dto\IdentitySearchQuery;
use Surfnet\StepupMiddlewareClient\Identity\Service\IdentityService as LibraryIdentityService;
use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidResponseException;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\Identity;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\IdentityCollection;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\RegistrationAuthorityCredentials;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Provides access to the Middleware API resources.
 */
class IdentityService
{
    /**
     * @var LibraryIdentityService
     */
    private $service;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param LibraryIdentityService $service
     * @param ValidatorInterface $validator
     */
    public function __construct(LibraryIdentityService $service, ValidatorInterface $validator)
    {
        $this->service = $service;
        $this->validator = $validator;
    }

    /**
     * @param string $id
     * @return null|Identity
     */
    public function get($id)
    {
        $data = $this->service->get($id);

        if ($data === null) {
            return null;
        }

        $identity = Identity::fromData($data);

        $message = sprintf("Identity '%s' retrieved from the Middleware is invalid", $id);
        $this->assertIsValid($identity, $message);

        return $identity;
    }

    /**
     * @param IdentitySearchQuery $searchQuery
     * @return \Surfnet\StepupMiddlewareClientBundle\Identity\Dto\IdentityCollection
     */
    public function search(IdentitySearchQuery $searchQuery)
    {
        $data = $this->service->search($searchQuery);

        $collection = IdentityCollection::fromData($data);

        $this->assertIsValid($collection, 'Invalid elements received in collection');

        return $collection;
    }

    /**
     * @param Identity $identity
     * @return RegistrationAuthorityCredentials
     */
    public function getRegistrationAuthorityCredentials(Identity $identity)
    {
        $data = $this->service->getRegistrationAuthorityCredentials($identity->id);

        // 404 Not Found is a valid case.
        if (!$data) {
            return null;
        }

        $credentials = RegistrationAuthorityCredentials::fromData($data);

        $message = sprintf('Registration Authority Credentials for Identity[%s] are invalid', $identity->id);
        $this->assertIsValid($credentials, $message);

        return $credentials;
    }

    /**
     * @param object      $value
     * @param null|string $message
     */
    private function assertIsValid($value, $message = null)
    {
        $violations = $this->validator->validate($value);

        $message = $message ?: 'Invalid Response Received';

        if (count($violations) > 0) {
            throw InvalidResponseException::withViolations($message, $violations);
        }
    }
}
