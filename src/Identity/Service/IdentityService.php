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

use Surfnet\StepupMiddlewareClient\Exception\AccessDeniedToResourceException;
use Surfnet\StepupMiddlewareClient\Exception\MalformedResponseException;
use Surfnet\StepupMiddlewareClient\Exception\ResourceReadException;
use Surfnet\StepupMiddlewareClient\Identity\Service\IdentityService as LibraryIdentityService;
use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidResponseException;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\Identity;
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
     * @param string $institution
     * @return null|Identity
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws InvalidResponseException When the API responded with invalid data.
     * @throws ResourceReadException When the API doesn't respond with the resource.
     * @throws MalformedResponseException When the API doesn't respond with a proper response.
     */
    public function get($id, $institution)
    {
        $data = $this->service->get($id, $institution);

        if ($data === null) {
            return null;
        }

        $identity = Identity::fromData($data);

        $violations = $this->validator->validate($identity);

        if (count($violations) > 0) {
            $message = sprintf("Identity '%s' retrieved from the Middleware is invalid", $id);
            throw InvalidResponseException::withViolations($message, $violations);
        }

        return $identity;
    }
}