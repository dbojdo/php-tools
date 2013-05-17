<?php
namespace Webit\Tools\Data\QueryDecorator;

class QueryField {
	/**
	 * 
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $alias;

	/**
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * 
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * 
	 * @return string
	 */
	public function getAlias() {
		return $this->alias;
	}

	/**
	 * 
	 * @param string $alias
	 */
	public function setAlias($alias) {
		$this->alias = $alias;
	}
}
?>
