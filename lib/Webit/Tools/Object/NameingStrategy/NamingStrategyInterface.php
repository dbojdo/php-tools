<?php
namespace Webit\Tools\Object\NameingStrategy;

interface NamingStrategyInterface {
	public function getGetterName($property);
	public function getSetterName($property);
	public function getPropertyName($field);
}
?>
