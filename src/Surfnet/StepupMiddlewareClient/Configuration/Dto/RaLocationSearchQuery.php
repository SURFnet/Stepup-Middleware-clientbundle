<?php

namespace Surfnet\StepupMiddlewareClient\Configuration\Dto;

use Assert;
use Surfnet\StepupMiddlewareClient\Dto\HttpQuery;

class RaLocationSearchQuery implements HttpQuery
{
    /**
     * @var string
     */
    private $institution;

    /**
     * @var string|null
     */
    private $orderBy = 'name';

    /**
     * @var string|null
     */
    private $orderDirection = 'asc';

    /**
     * @param string $institution
     */
    public function __construct($institution)
    {
        $this->assertNonEmptyString($institution, 'institution');

        $this->institution = $institution;
    }

    /**
     * @return string
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * @param string $orderBy
     * @return $this
     */
    public function setOrderBy($orderBy)
    {
        $this->assertNonEmptyString($orderBy, 'orderBy');

        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * @param string $orderDirection
     * @return $this
     */
    public function setOrderDirection($orderDirection)
    {
        $this->assertNonEmptyString($orderDirection, 'orderDirection');
        Assert\that($orderDirection)->choice(
            ['asc', 'desc', '', null],
            "Invalid order direction, must be one of 'asc', 'desc'"
        );

        $this->orderDirection = $orderDirection;

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
        return '?institution=' . urlencode($this->institution)
            . '&orderBy=' . urlencode($this->orderBy)
            . '&orderDirection' . urlencode($this->orderDirection);
    }
}
