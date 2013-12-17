<?php
namespace Webit\Tools\Data;

use JMS\Serializer\Annotation as JMS;

class Sorter implements SorterInterface
{
    /**
     * 
     * @var string
     * @JMS\Type("string")
     */
    protected $property;

    /**
     *
     * @var string
     * @JMS\Type("string")
     */
    protected $direction;

    /**
     *
     * @param string $property
     * @param string $direction (ASC|DESC)
     */
    public function __construct($property, $direction = self::DIRECTION_ASC)
    {
        $this->setProperty($property);
        $this->setDirection($direction);
    }

    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @var string $property
     */
    public function setProperty($property)
    {
        $this->property = $property;
    }

    /**
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @var string (ASC|DESC)
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
    }
}
