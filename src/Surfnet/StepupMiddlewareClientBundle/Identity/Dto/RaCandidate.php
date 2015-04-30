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

namespace Surfnet\StepupMiddlewareClientBundle\Identity\Dto;

use Surfnet\StepupMiddlewareClientBundle\Dto\Dto;

class RaCandidate implements Dto
{
    /**
     * @var string
     */
    public $identityId;

    /**
     * @var string
     */
    public $institution;

    /**
     * @var string
     */
    public $commonName;

    /**
     * @var string
     */
    public $email;

    /**
     * @param array $data
     * @return static
     */
    public static function fromData(array $data)
    {
        $raCandidate              = new self();
        $raCandidate->identityId  = $data['identity_id'];
        $raCandidate->institution = $data['institution'];
        $raCandidate->commonName  = $data['common_name'];
        $raCandidate->email       = $data['email'];
    }
}
