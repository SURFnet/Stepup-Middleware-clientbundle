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

namespace Surfnet\StepupMiddlewareClient\Identity\Dto;

use Assert;

class VerifiedSecondFactorSearchQuery implements HttpQuery
{
    /**
     * @var string
     */
    private $identityId;

    /**
     * @var string
     */
    private $secondFactorId;


    /**
     * @var string
     */
    private $registrationCode;

    /**
     * @param string $identityId
     * @return self
     */
    public function setIdentityId($identityId)
    {
        $this->assertNonEmptyString($identityId, 'identityId');

        $this->identityId = $identityId;

        return $this;
    }

    /**
     * @param string $secondFactorId
     * @return self
     */
    public function setSecondFactorId($secondFactorId)
    {
        $this->assertNonEmptyString($secondFactorId, 'secondFactorId');

        $this->secondFactorId = $secondFactorId;

        return $this;
    }

    /**
     * @param string $registrationCode
     * @return self
     */
    public function setRegistrationCode($registrationCode)
    {
        $this->assertNonEmptyString($registrationCode, 'registrationCode');

        $this->registrationCode = $registrationCode;

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

    public function toHttpQuery()
    {
        $fields = [];

        if ($this->identityId) {
            $fields['identityId'] = $this->identityId;
        }

        if ($this->secondFactorId) {
            $fields['secondFactorId'] = $this->secondFactorId;
        }

        if ($this->registrationCode) {
            $fields['registrationCode'] = $this->registrationCode;
        }

        return '?' . http_build_query($fields);
    }
}
