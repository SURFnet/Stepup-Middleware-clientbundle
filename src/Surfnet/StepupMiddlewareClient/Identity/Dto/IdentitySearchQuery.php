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

class IdentitySearchQuery implements HttpQuery
{
    /**
     * @var string
     */
    private $nameId;

    /**
     * @var string
     */
    private $institution;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $commonName;

    /**
     * @param string $institution
     */
    public function __construct($institution)
    {
        $this->assertNonEmptyString($institution, 'institution');

        $this->institution = $institution;
    }

    /**
     * @param string $commonName
     * @return IdentitySearchQuery
     */
    public function setCommonName($commonName)
    {
        $this->assertNonEmptyString($commonName, 'commonName');

        $this->commonName = $commonName;

        return $this;
    }

    /**
     * @param string $email
     * @return IdentitySearchQuery
     */
    public function setEmail($email)
    {
        $this->assertNonEmptyString($email, 'email');

        $this->email = $email;

        return $this;
    }

    /**
     * @param string $nameId
     * @return IdentitySearchQuery
     */
    public function setNameId($nameId)
    {
        $this->assertNonEmptyString($nameId, 'nameId');

        $this->nameId = $nameId;

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
        $fields = ['institution' => $this->institution];

        if ($this->commonName) {
            $fields['commonName'] = $this->commonName;
        }

        if ($this->email) {
            $fields['email'] = $this->email;
        }

        if ($this->nameId) {
            $fields['NameID'] = $this->nameId;
        }

        return '?' . http_build_query($fields);
    }
}
