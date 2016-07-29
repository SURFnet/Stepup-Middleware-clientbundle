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

namespace Surfnet\StepupMiddlewareClientBundle\Configuration\Dto;

use Surfnet\StepupMiddlewareClientBundle\Dto\Dto;
use Symfony\Component\Validator\Constraints as Assert;

class InstitutionConfigurationOptions implements Dto
{
    /**
     * @Assert\NotBlank(message="middleware_client.dto.configuration.use_ra_locations.must_not_be_blank")
     * @Assert\Type(type="boolean", message="middleware_client.dto.configuration.use_ra_locations.must_be_boolean")
     */
    public $useRaLocations;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.configuration.show_raa_contact_information.must_not_be_blank")
     * @Assert\Type(type="boolean", message="middleware_client.dto.configuration.show_raa_contact_information.must_be_boolean")
     */
    public $showRaaContactInformation;

    /**
     * @param array $data
     * @return static
     */
    public static function fromData(array $data)
    {
        $institutionConfigurationOptions                            = new self();
        $institutionConfigurationOptions->useRaLocations            = $data['use_ra_locations'];
        $institutionConfigurationOptions->showRaaContactInformation = $data['show_raa_contact_information'];

        return $institutionConfigurationOptions;
    }
}
