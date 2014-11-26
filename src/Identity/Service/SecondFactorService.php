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
use Surfnet\StepupMiddlewareClient\Identity\Service\SecondFactorService as LibrarySecondFactorService;
use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidResponseException;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\SecondFactor;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\UnverifiedSecondFactorCollection;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecondFactorService
{
    /**
     * @var LibrarySecondFactorService
     */
    private $service;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param LibrarySecondFactorService $service
     * @param ValidatorInterface $validator
     */
    public function __construct(LibrarySecondFactorService $service, ValidatorInterface $validator)
    {
        $this->service = $service;
        $this->validator = $validator;
    }

    /**
     * @param string $identityId
     * @return SecondFactor[]
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws InvalidResponseException When the API responded with invalid data.
     * @throws ResourceReadException When the API doesn't respond with the resource.
     * @throws MalformedResponseException When the API doesn't respond with a proper response.
     */
    public function findByIdentity($identityId)
    {
        $data = $this->service->findByIdentity($identityId);

        if ($data === null) {
            return null;
        }

        $secondFactors = array_map('Surfnet\StepupMiddlewareClientBundle\Identity\Dto\SecondFactor::fromData', $data);

        $violations = $this->validator->validate($secondFactors);

        if (count($violations) > 0) {
            throw InvalidResponseException::withViolations(
                "One or more second factors retrieved from the Middleware were invalid",
                $violations
            );
        }

        return $secondFactors;
    }

    /**
     * @param string $identityId
     * @return UnverifiedSecondFactorCollection
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws InvalidResponseException When the API responded with invalid data.
     * @throws ResourceReadException When the API doesn't respond with the resource.
     * @throws MalformedResponseException When the API doesn't respond with a proper response.
     */
    public function findUnverifiedByIdentity($identityId)
    {
        $data = $this->service->findUnverifiedByIdentity($identityId);

        if ($data === null) {
            return null;
        }

        $secondFactors = UnverifiedSecondFactorCollection::fromData($data);
        $violations = $this->validator->validate($secondFactors);

        if (count($violations) > 0) {
            throw InvalidResponseException::withViolations(
                "One or more second factors retrieved from the Middleware were invalid",
                $violations
            );
        }

        return $secondFactors;
    }
}
