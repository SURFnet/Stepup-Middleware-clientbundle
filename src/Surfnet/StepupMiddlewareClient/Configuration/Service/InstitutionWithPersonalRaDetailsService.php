<?php

namespace Surfnet\StepupMiddlewareClient\Configuration\Service;

use Surfnet\StepupMiddlewareClient\Service\ApiService;

class InstitutionWithPersonalRaDetailsService
{
    /**
     * @var ApiService
     */
    private $apiService;

    /**
     * @param string $institution
     * @return mixed|null
     */
    public function institutionHasPersonalRaDetails($institution)
    {
        return $this->apiService->read('/has-personal-ra-locations/%s', [$institution]);
    }
}
