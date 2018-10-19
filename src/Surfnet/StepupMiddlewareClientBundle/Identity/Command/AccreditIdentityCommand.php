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

namespace Surfnet\StepupMiddlewareClientBundle\Identity\Command;

use Surfnet\StepupMiddlewareClientBundle\Command\AbstractCommand;
use Symfony\Component\Validator\Constraints as Assert;

class AccreditIdentityCommand extends AbstractCommand
{
    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     *
     * @var string
     */
    public $actorInstitution;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     *
     * @var string
     */
    public $identityId;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     *
     * @var string
     */
    public $institution;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Choice(choices={"ra", "raa"})
     *
     * @var string
     */
    public $role;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     *
     * @var string
     */
    public $location;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     *
     * @var string
     */
    public $contactInformation;

    /**
     * @return array
     */
    public function serialise()
    {
        return [
            'actor_institution'   => $this->actorInstitution,
            'identity_id'         => $this->identityId,
            'institution'         => $this->institution,
            'role'                => $this->role,
            'location'            => $this->location,
            'contact_information' => $this->contactInformation
        ];
    }
}
