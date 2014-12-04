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
use Surfnet\StepupMiddlewareClient\Identity\Dto\UnverifiedSecondFactorSearchQuery;
use Surfnet\StepupMiddlewareClient\Identity\Dto\VerifiedSecondFactorSearchQuery;
use Surfnet\StepupMiddlewareClient\Identity\Service\SecondFactorService as LibrarySecondFactorService;
use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidResponseException;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\UnverifiedSecondFactorCollection;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\VerifiedSecondFactorCollection;
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
     * @param UnverifiedSecondFactorSearchQuery $query
     * @return UnverifiedSecondFactorCollection
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws InvalidResponseException When the API responded with invalid data.
     * @throws ResourceReadException When the API doesn't respond with the resource.
     * @throws MalformedResponseException When the API doesn't respond with a proper response.
     */
    public function searchUnverified(UnverifiedSecondFactorSearchQuery $query)
    {
        $data = $this->service->searchUnverified($query);

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

    /**
     * @param VerifiedSecondFactorSearchQuery $query
     * @return VerifiedSecondFactorCollection
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws InvalidResponseException When the API responded with invalid data.
     * @throws ResourceReadException When the API doesn't respond with the resource.
     * @throws MalformedResponseException When the API doesn't respond with a proper response.
     */
    public function searchVerified(VerifiedSecondFactorSearchQuery $query)
    {
        $data = $this->service->searchVerified($query);

        if ($data === null) {
            return null;
        }

        $secondFactors = VerifiedSecondFactorCollection::fromData($data);
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
