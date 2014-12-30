<?php
namespace Webit\Tools\Data;

use JMS\Serializer\Annotation as JMS;

/**
 *
 * @author dbojdo
 *
 */
class Filter implements FilterInterface
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $property;

    /**
     *
     * @var string
     * @JMS\Type("string")
     */
    protected $field;

    /**
     *
     * @var string
     * @JMS\Type("string")
     */
    protected $type = FilterInterface::TYPE_STRING;

    /**
     *
     * @var string
     * @JMS\Type("string")
     */
    protected $comparison = FilterInterface::COMPARISON_EQUAL;

    /**
     *
     * @var mixed
     * @JMS\Type("string")
     */
    protected $value;

    /**
   * @var FilterParams
   * @JMS\Type("Webit\Tools\Data\FilterParams")
     */
    protected $params;

    public function __construct($property, $value, FilterParams $params = null)
    {
        $this->property = $property;
        $this->value = $value;
        $this->params = $params ?: new FilterParams();
    }

    /**
     * (non-PHPdoc)
     * @see Webit\Tools\Data\FilterInterface::getProperty()
     */
    public function getProperty()
    {
        $property = empty($this->property) == false ? $this->property : $this->field;

        return $property;
    }

    public function setProperty($property)
    {
        $this->property = $property;
    }

    /**
     * (non-PHPdoc)
     * @see Webit\Tools\Data\FilterInterface::getValue()
     */
    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     *
     * @param string $comparison
     */
    public function setComparison($comparison)
    {
        $this->comparison = $comparison;
    }

    /**
     * (non-PHPdoc)
     * @see Webit\Tools\Data\FilterInterface::getComparison()
     */
    public function getComparison()
    {
        return $this->comparison;
    }

    /**
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * (non-PHPdoc)
     * @see Webit\Tools\Data\FilterInterface::getType()
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * (non-PHPdoc)
     * @see Webit\Tools\Data\FilterInterface::getParams()
     */
    public function getParams()
    {
        if($this->params == null) {
            $this->params = new FilterParams();
        }
        return $this->params;
    }
}
