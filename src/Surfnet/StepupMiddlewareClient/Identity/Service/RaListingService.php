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

namespace Surfnet\StepupMiddlewareClient\Identity\Service;

use Surfnet\StepupMiddlewareClient\Identity\Dto\RaListingSearchQuery;
use Surfnet\StepupMiddlewareClient\Service\ApiService;

class RaListingService
{
    /**
     * @var ApiService
     */
    private $apiService;

    /**
     * @param ApiService $apiService
     */
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * @param string $id The RA's identity ID.
     * @param string $institution The institution.
     * @param string $actorInstitution The institution of the actor.
     * @param string $actorId The identity id of the actor.
     * @return null|array
     * @throws AccessDeniedToResourceException When the consumer isn't authorised to access given resource.
     * @throws ResourceReadException When the server doesn't respond with the resource.
     * @throws MalformedResponseException When the server doesn't respond with (well-formed) JSON.
     */
    public function get($id, $institution, $actorInstitution, $actorId)
    {
        return $this->apiService->read('ra-listing/%s/%s?actorId=%s&actorInstitution=%s', [$id, $institution, $actorId, $actorInstitution]);
    }

    /**
     * @param RaListingSearchQuery $searchQuery
     * @return mixed|null
     */
    public function search(RaListingSearchQuery $searchQuery)
    {
        return $this->apiService->read('ra-listing' . $searchQuery->toHttpQuery());
    }
}
