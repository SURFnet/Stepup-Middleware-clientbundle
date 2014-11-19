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

namespace Surfnet\StepupMiddlewareClientBundle\Dto;

use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;

abstract class CollectionDto implements Dto
{
    /**
     * @Assert\Valid
     *
     * @var array
     */
    protected $elements;

    /**
     * @var int
     */
    protected $totalItems;

    /**
     * @var int
     */
    protected $currentPage;

    /**
     * @var int
     */
    protected $itemsPerPage;

    public function __construct(array $elements, $totalItems, $currentPage, $itemsPerPage)
    {
        $this->elements = $elements;
        $this->totalItems = (int) $totalItems;
        $this->currentPage = (int) $currentPage;
        $this->itemsPerPage = (int) $itemsPerPage;
    }

    /**
     * @param array   $data
     * @return static
     */
    public static function fromCollectionData(array $data)
    {
        $elements = [];
        foreach ($data['items'] as $key => $item) {
            $elements[$key] = static::fromData($item);
        }

        return new static(
            $elements,
            $data['collection']['total_items'],
            $data['collection']['page'],
            $data['collection']['page_size']
        );
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @return array
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @return int
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    /**
     * @return int
     */
    public function getTotalItems()
    {
        return $this->totalItems;
    }
}
