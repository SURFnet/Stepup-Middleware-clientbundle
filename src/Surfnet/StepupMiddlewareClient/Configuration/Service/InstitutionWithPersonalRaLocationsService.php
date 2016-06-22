<?php

namespace Surfnet\StepupMiddlewareClient\Configuration\Service;

use Surfnet\StepupMiddlewareClient\Service\ApiService;

class InstitutionWithPersonalRaLocationsService
{
    /**
     * @var ApiService
     */
    private $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * @param string $institution
     * @return bool
     */
    public function institutionHasPersonalRaLocations($institution)
    {
        return $this->apiService->read('/has-personal-ra-locations/%s', [$institution]);
    }
}
