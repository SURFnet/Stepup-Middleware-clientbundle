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

use Surfnet\StepupMiddlewareClient\Identity\Dto\RaCandidateSearchQuery;
use Surfnet\StepupMiddlewareClient\Identity\Service\RaCandidateService as LibraryRaCandidateService;
use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidResponseException;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\RaCandidateCollection;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RaCandidateService
{
    private $libraryService;

    private $validator;

    public function __construct(LibraryRaCandidateService $libraryService, ValidatorInterface $validator)
    {
        $this->libraryService = $libraryService;
        $this->validator      = $validator;
    }

    /**
     * @param RaCandidateSearchQuery $query
     * @return RaCandidateCollection
     */
    public function search(RaCandidateSearchQuery $query)
    {
        $data = $this->libraryService->search($query);

        if ($data === null) {
            throw new InvalidResponseException(
                'Received a "null" as data when searching for RaCandidates, is the library service set up correctly?'
            );
        }

        $collection = RaCandidateCollection::fromData($data);

        $violations = $this->validator->validate($collection);

        if (count($violations)) {
            throw InvalidResponseException::withViolations('One or more RaCandidates are not valid', $violations);
        }

        return $collection;
    }
}
