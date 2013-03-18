<?php
namespace Webit\Tools\Data;

use JMS\Serializer\Annotation as JMS;

/**
 * 
 * @author dbojdo
 *
 */
class Filter implements FilterInterface {	
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
	protected $comparision = FilterInterface::COMPARISION_EQUAL;
	
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
	
	public function __construct($property, $value, FilterParams $params = null) {
		$this->property = $property;
		$this->value = $value;
		$this->params = $params ?: new FilterParams();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Webit\Tools\Data\FilterInterface::getProperty()
	 */
	public function getProperty() {
		$property = empty($this->property) == false ? $this->property : $this->field;
		return $property;
	}
	
	public function setProperty($property) {
		$this->property = $property;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Webit\Tools\Data\FilterInterface::getValue()
	 */
	public function getValue() {
		return $this->value;
	}
	
	public function setValue($value) {
		$this->value = $value;
	}
	
	/**
	 * 
	 * @param string $comparision
	 */
	public function setComparision($comparision) {
		$this->comparision = $comparision;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Webit\Tools\Data\FilterInterface::getComparision()
	 */
	public function getComparision() {
		return $this->comparision;
	}
	
	/**
	 * 
	 * @param string $type
	 */
	public function setType($type) {
		$this->type = $type;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Webit\Tools\Data\FilterInterface::getType()
	 */
	public function getType() {
		return $this->type;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Webit\Tools\Data\FilterInterface::getParams()
	 */
	public function getParams() {
		return $this->params ?: new FilterParams();
	}
}