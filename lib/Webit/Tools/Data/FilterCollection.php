<?php
namespace Webit\Tools\Data;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;

class FilterCollection extends ArrayCollection {
	/**
	 * Used only for Serializer get work properly
	 * @JMS\Type("array<Webit\Tools\Data\Filter>")
	 * @JMS\AccessType("public_method")
	 */
	private $filters;
	
	public function addFilter(FilterInterface $filter) {
		$this->set($filter->getProperty(),$filter);
	}
	
	public function getFilters() {
		return $this->getValues();
	}
	
	public function removeFilter(FilterInterface $filter) {
		$this->removeElement(filter);
	}
	
	public function getFilter($property) {
		return $this->get($property);
	}
	
	/**
	 * @param array $filters
	 */
	public function setFilters(array $filters) {
		foreach($filters as $filter) {
			$this->addFilter($filter);
		}
	}
}
?>
