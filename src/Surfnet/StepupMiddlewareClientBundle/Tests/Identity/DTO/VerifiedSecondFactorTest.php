<?php

/**
 * Copyright 2018 SURFnet bv
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

namespace Surfnet\StepupMiddlewareClient\Tests\Identity\DTO;


use DateTime;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\VerifiedSecondFactor;

class VerifiedSecondFactorTest extends \PHPUnit_Framework_TestCase
{

    public function test_expires_at_is_fourteen_days_in_future()
    {
        $secondFactor = $this->buildVerifiedSecondFactorFrom(
            '2000-01-01',
            '2000-01-01'
        );

        $expectedExpirationDate = '2000-01-15';
        $expectedRequestedAtData = '2000-01-01';

        $this->assertEquals($expectedExpirationDate, $secondFactor->expiresAt()->format('Y-m-d'));
        $this->assertEquals(
            $expectedRequestedAtData,
            $secondFactor->registrationRequestedAt->format('Y-m-d'),
            'The registrationRequestedAt date was changed during operations on the DTO. This field should not have changed.'
        );
    }

    public function test_has_expired()
    {
        $secondFactor = $this->buildVerifiedSecondFactorFrom(
            '2000-01-01',
            '2000-01-16'
        );

        $this->assertTrue($secondFactor->hasExpired());
    }

    public function test_has_not_expired()
    {
        $secondFactor = $this->buildVerifiedSecondFactorFrom(
            '2000-01-01 00:00:00',
            '2000-01-15 23:59:59'
        );

        $this->assertTrue($secondFactor->hasExpired());
    }

    /**
     * Builds a stripped down VerifiedSecondFactor DTO (with just the registrationRequestedAt set)
     * @param string $requestedAt Y-m-d formatted date
     * @param string $now Y-m-d formatted date
     * @return VerifiedSecondFactor
     */
    private function buildVerifiedSecondFactorFrom($requestedAt, $now)
    {
        return VerifiedSecondFactor::fromData(
            [
                'id' => 1,
                'type' => 'sms',
                'second_factor_identifier' => '+316999999',
                'registration_code' => '1A3 P3S',
                'registration_requested_at' => $requestedAt,
                'identity_id' => 1,
                'institution' => 'institution.example.com',
                'common_name' => 'John Doe'
            ],
            new DateTime($now)
        );
    }

}
