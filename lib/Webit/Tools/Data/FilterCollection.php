<?php
namespace Webit\Tools\Data;

use Doctrine\Common\Collections\ArrayCollection;

class FilterCollection extends ArrayCollection {
	public function addFilter(FilterInterface $filter) {
		$this->set($sorter->getProperty(),$filter);
	}
	
	public function getFilters() {
		return $this->getValues();
	}
	
	public function removeFilter(FilterInterface filter) {
		$this->removeElement(filter);
	}
	
	public function getFilter($property) {
		return $this->get($property);
	}
}
?>
