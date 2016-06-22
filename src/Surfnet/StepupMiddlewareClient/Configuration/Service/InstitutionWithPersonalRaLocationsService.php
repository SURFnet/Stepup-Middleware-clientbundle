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
    public function institutionShowsRaLocations($institution)
    {
        return $this->apiService->read('/shows-ra-locations/%s', [$institution]);
    }
}
