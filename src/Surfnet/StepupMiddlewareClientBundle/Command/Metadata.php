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

namespace Surfnet\StepupMiddlewareClientBundle\Command;

use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidArgumentException;

final class Metadata
{
    /**
     * @var string
     */
    private $actorId;

    /**
     * @var string
     */
    private $actorInstitution;

    /**
     * @param string $actorId
     * @param string $actorInstitution
     */
    public function __construct($actorId, $actorInstitution)
    {
        if (!is_string($actorId)) {
            throw InvalidArgumentException::invalidType('string', 'actorId', $actorId);
        }

        if (!is_string($actorInstitution)) {
            throw InvalidArgumentException::invalidType('string', 'actorInstitution', $actorInstitution);
        }

        $this->actorId = $actorId;
        $this->actorInstitution = $actorInstitution;
    }

    /**
     * @return array
     */
    public function serialise()
    {
        return [
            'actor_id' => $this->actorId,
            'actor_institution' => $this->actorInstitution,
        ];
    }
}
