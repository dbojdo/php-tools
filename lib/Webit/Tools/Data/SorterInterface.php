<?php
namespace Webit\Tools\Data;

interface SorterInterface {
	const DIRECTION_ASC = 'ASC';
	const DIRECTION_DESC = 'DESC';

	public function getProperty();
	
	public function setProperty($property);
	
	public function getDirection();
	
	public function setDirection($direction);
}
?>
