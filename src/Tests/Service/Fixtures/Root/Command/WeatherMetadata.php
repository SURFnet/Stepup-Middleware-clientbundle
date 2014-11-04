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

namespace Surfnet\StepupMiddlewareClientBundle\Tests\Service\Fixtures\Root\Command;

use Surfnet\StepupMiddlewareClientBundle\Command\Metadata;

class WeatherMetadata implements Metadata
{
    /**
     * @var float
     */
    public $atmosphericPressure;

    /**
     * @param float $atmosphericPressure The atmospheric pressure measured at command execution in millibars.
     */
    public function __construct($atmosphericPressure)
    {
        $this->atmosphericPressure = $atmosphericPressure;
    }

    /**
     * @return array
     */
    public function serialise()
    {
        return ['millibars' => $this->atmosphericPressure];
    }
}
