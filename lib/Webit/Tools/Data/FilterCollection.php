<?php
namespace Webit\Tools\Data;

use Doctrine\Common\Collections\ArrayCollection;

class FilterCollection extends ArrayCollection
{    
    /**
     * 
     * @param FilterInterface $filter
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->add($filter);
    }

    /**
     * 
     * @param string $property
     * @return FilterCollection
     */
    public function getFilters($property = null)
    {
        if($property) {
            $coll = $this->filter(function(FilterInterface $filter) use ($property) {
                return $filter->getProperty() == $property;
            });
            
            return $coll;
        }
        
        return $this;
    }

    /**
     * 
     * @param FilterInterface $filter
     */
    public function removeFilter(FilterInterface $filter)
    {
        $this->removeElement(filter);
    }

    /**
     * 
     * @param string $property
     * @return FilterCollection
     */
    public function getFilter($property)
    {
        $coll = $this->getFilters($property);
        if($coll->count() > 0) {
            return $coll->first();
        }
        
        return null;
    }

    /**
     * @param array $filters
     */
    public function setFilters(array $filters)
    {
        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }
    }
}
