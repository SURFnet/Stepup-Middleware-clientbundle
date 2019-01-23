<?php

/**
 * Copyright 2019 SURFnet B.V.
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

namespace Surfnet\StepupMiddlewareClient\Identity\Dto;

use Assert;
use Surfnet\StepupMiddlewareClient\Dto\HttpQuery;

class ProfileSearchQuery implements HttpQuery
{
    /**
     * @var string
     */
    private $identityId;

    /**
     * @var string
     */
    private $actorId;

    /**
     * @param string $identityId
     * @param string $actorId
     */
    public function __construct($identityId, $actorId)
    {
        $this->identityId = $identityId;
        $this->actorId = $actorId;
    }

    /**
     * @return string
     */
    public function getIdentityId()
    {
        return $this->identityId;
    }

    /**
     * @param string $actorId
     * @return ProfileSearchQuery
     */
    public function setActorId($actorId)
    {
        $this->assertNonEmptyString($actorId, 'institution');

        $this->actorId = $actorId;

        return $this;
    }

    /**
     * @param string $identityId
     * @return ProfileSearchQuery
     */
    public function setIdentityId($identityId)
    {
        $this->assertNonEmptyString($identityId, 'institutionId');

        $this->identityId = $identityId;

        return $this;
    }

    private function assertNonEmptyString($value, $name)
    {
        $message = sprintf(
            '"%s" must be a non-empty string, "%s" given',
            $name,
            (is_object($value) ? get_class($value) : gettype($value))
        );

        Assert\that($value)->string($message)->notEmpty($message);
    }

    /**
     * @return string
     */
    public function toHttpQuery()
    {
        if ($this->actorId) {
            $fields = ['actorId' => $this->actorId];
        }

        return '?' . http_build_query($fields);
    }
}
