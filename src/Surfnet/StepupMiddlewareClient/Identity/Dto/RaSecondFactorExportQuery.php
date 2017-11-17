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
use Surfnet\StepupMiddlewareClient\Dto\HttpQuery;

final class RaSecondFactorExportQuery implements HttpQuery
{
    const STATUS_UNVERIFIED = 'unverified';
    const STATUS_VERIFIED = 'verified';
    const STATUS_VETTED = 'vetted';
    const STATUS_REVOKED = 'revoked';

    /**
     * @var string
     */
    private $institution;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var string|null The second factor type's ID (eg. Yubikey public ID)
     */
    private $secondFactorId;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @var string|null One of the STATUS_* constants.
     */
    private $status;

    /**
     * @var string|null
     */
    private $orderBy;

    /**
     * @var string|null
     */
    private $orderDirection;

    /**
     * @param string $institution
     */
    public function __construct($institution)
    {
        $this->assertNonEmptyString($institution, 'institution');
        $this->institution = $institution;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName($name)
    {
        $this->assertNonEmptyString($name, 'name');

        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param null|string $type
     */
    public function setType($type)
    {
        $this->assertNonEmptyString($type, 'type');

        $this->type = $type;
    }

    /**
     * @return null|string
     */
    public function getSecondFactorId()
    {
        return $this->secondFactorId;
    }

    /**
     * @param null|string $secondFactorId
     */
    public function setSecondFactorId($secondFactorId)
    {
        $this->assertNonEmptyString($secondFactorId, 'secondFactorId');

        $this->secondFactorId = $secondFactorId;
    }

    /**
     * @return null|string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     */
    public function setEmail($email)
    {
        $this->assertNonEmptyString($email, 'email');

        $this->email = $email;
    }

    /**
     * @return null|string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        Assert\that($status)->choice(
            [self::STATUS_UNVERIFIED, self::STATUS_VERIFIED, self::STATUS_VETTED, self::STATUS_REVOKED, ''],
            'Invalid second factor status, must be one of the STATUS constants'
        );

        $this->status = $status ?: null;
    }

    /**
     * @param string $orderBy
     */
    public function setOrderBy($orderBy)
    {
        $this->assertNonEmptyString($orderBy, 'orderBy');

        $this->orderBy = $orderBy;
    }

    /**
     * @param string|null $orderDirection
     */
    public function setOrderDirection($orderDirection)
    {
        Assert\that($orderDirection)->choice(
            ['asc', 'desc', '', null],
            "Invalid order direction, must be one of 'asc', 'desc'"
        );

        $this->orderDirection = $orderDirection ?: null;
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
     * Return the Http Query string as should be used, MUST include the '?' prefix.
     *
     * @return string
     */
    public function toHttpQuery()
    {
        return '?' . http_build_query(
            array_filter(
                [
                    'institution'    => $this->institution,
                    'name'           => $this->name,
                    'type'           => $this->type,
                    'secondFactorId' => $this->secondFactorId,
                    'email'          => $this->email,
                    'status'         => $this->status,
                    'orderBy'        => $this->orderBy,
                    'orderDirection' => $this->orderDirection
                ],
                function ($value) {
                    return !is_null($value);
                }
            )
        );
    }
}
