<?php

/**
 * Copyright 2016 SURFnet B.V.
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

use PHPUnit_Framework_TestCase as TestCase;
use Surfnet\StepupMiddlewareClient\Configuration\Service\InstitutionConfigurationOptionsService as LibraryInstitutionConfigurationOptionsService;
use Surfnet\StepupMiddlewareClientBundle\Configuration\Dto\InstitutionConfigurationOptions;
use Surfnet\StepupMiddlewareClientBundle\Configuration\Service\InstitutionConfigurationOptionsService;
use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidResponseException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InstitutionConfigurationOptionsServiceTest extends TestCase
{
    /**
     * @group institution-configuration
     */
    public function testQueriedInstitutionConfigurationOptionsAreConvertedToADtoCorrectly()
    {
        $institution = 'surfnet.nl';

        $expectedInstitutionConfigurationOptions = new InstitutionConfigurationOptions();
        $expectedInstitutionConfigurationOptions->useRaLocations = true;
        $expectedInstitutionConfigurationOptions->showRaaContactInformation = false;

        $validResponseData = [
            'institution'                  => $institution,
            'use_ra_locations'             => true,
            'show_raa_contact_information' => false,
        ];

        $libraryService = Mockery::mock(LibraryInstitutionConfigurationOptionsService::class);
        $libraryService->shouldReceive('getInstitutionConfigurationOptionsFor')
            ->with($institution)
            ->once()
            ->andReturn($validResponseData);

        $violations = Mockery::mock(ConstraintViolationListInterface::class);
        $violations->shouldReceive('count')
            ->once()
            ->andReturn(0);

        $validator = Mockery::mock(ValidatorInterface::class);
        $validator->shouldReceive('validate')
            ->once()
            ->andReturn($violations);
        
        $service = new InstitutionConfigurationOptionsService($libraryService, $validator);
        $actualInstitutionConfigurationOptions = $service->getInstitutionConfigurationOptionsFor($institution);

        $this->assertEquals($expectedInstitutionConfigurationOptions, $actualInstitutionConfigurationOptions);
    }

    /**
     * @group institution-configuration
     *
     * @dataProvider nonBooleanProvider
     */
    public function testInstitutionConfigurationOptionsWithANonBooleanUseRaLocationsOptionAreInvalid($nonBoolean)
    {
        $this->setExpectedException(InvalidResponseException::class);

        $institution = 'surfnet.nl';

        $invalidResponseData = [
            'institution'                  => $institution,
            'use_ra_locations'             => true,
            'show_raa_contact_information' => $nonBoolean,
        ];

        $libraryService = Mockery::mock(LibraryInstitutionConfigurationOptionsService::class);
        $libraryService->shouldReceive('getInstitutionConfigurationOptionsFor')
            ->with($institution)
            ->once()
            ->andReturn($invalidResponseData);

        $violations = Mockery::mock(ConstraintViolationListInterface::class);
        $violations->shouldReceive('count')
            ->once()
            ->andReturn(1);

        $violations->shouldReceive('getIterator')
            ->once()
            ->andReturn(new ArrayIterator);

        $validator = Mockery::mock(ValidatorInterface::class);
        $validator->shouldReceive('validate')
            ->once()
            ->andReturn($violations);

        $service = new InstitutionConfigurationOptionsService($libraryService, $validator);
        $service->getInstitutionConfigurationOptionsFor($institution);
    }

    /**
     * @group institution-configuration
     *
     * @dataProvider nonBooleanProvider
     */
    public function testInstitutionConfigurationOptionsWithANonBooleanShowRaaContactInformationOptionAreInvalid($nonBoolean)
    {
        $this->setExpectedException(InvalidResponseException::class);

        $institution = 'surfnet.nl';

        $invalidResponseData = [
            'institution'                  => $institution,
            'use_ra_locations'             => $nonBoolean,
            'show_raa_contact_information' => true,
        ];

        $libraryService = Mockery::mock(LibraryInstitutionConfigurationOptionsService::class);
        $libraryService->shouldReceive('getInstitutionConfigurationOptionsFor')
            ->with($institution)
            ->once()
            ->andReturn($invalidResponseData);

        $violations = Mockery::mock(ConstraintViolationListInterface::class);
        $violations->shouldReceive('count')
            ->once()
            ->andReturn(1);

        $violations->shouldReceive('getIterator')
            ->once()
            ->andReturn(new ArrayIterator);

        $validator = Mockery::mock(ValidatorInterface::class);
        $validator->shouldReceive('validate')
            ->once()
            ->andReturn($violations);

        $service = new InstitutionConfigurationOptionsService($libraryService, $validator);
        $service->getInstitutionConfigurationOptionsFor($institution);
    }

    public function nonBooleanProvider()
    {
        return [
            'null'    => [null],
            'array'   => [[]],
            'string'  => [''],
            'integer' => [1],
            'float'   => [1.23],
            'object'  => [new stdClass],
        ];
    }
}
