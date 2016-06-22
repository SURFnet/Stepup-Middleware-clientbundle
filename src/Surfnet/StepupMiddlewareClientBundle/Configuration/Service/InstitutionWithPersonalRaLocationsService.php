<?php

namespace Surfnet\StepupMiddlewareClientBundle\Configuration\Service;

use Surfnet\StepupMiddlewareClient\Configuration\Service\InstitutionWithPersonalRaLocationsService
    as LibraryInstitutionWithPersonalRaLocationsService;

class InstitutionWithPersonalRaLocationsService
{
    /**
     * @var LibraryInstitutionWithPersonalRaLocationsService
     */
    private $service;

    public function __construct(LibraryInstitutionWithPersonalRaLocationsService $service)
    {
        $this->service = $service;
    }

    /**
     * @param string $institution
     * @return bool
     */
    public function institutionShowsRaLocations($institution)
    {
        return $this->service->institutionShowsRaLocations($institution);
    }
}
