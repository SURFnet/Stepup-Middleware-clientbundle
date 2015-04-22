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

use Surfnet\StepupMiddlewareClient\Identity\Dto\RaSearchQuery;
use Surfnet\StepupMiddlewareClient\Service\ApiService;

class RaService
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
     * @param string $institution
     * @return array|null
     */
    public function listRas($institution)
    {
        return $this->apiService->read('registration-authority?institution=%s', [$institution]);
    }

    /**
     * @param RaSearchQuery $searchQuery
     * @return mixed|null
     */
    public function search(RaSearchQuery $searchQuery)
    {
        return $this->apiService->read('registration-authority'. $searchQuery->toHttpQuery());
    }
}
