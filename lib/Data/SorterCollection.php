<?php
namespace Webit\Tools\Data;

use Doctrine\Common\Collections\ArrayCollection;

class SorterCollection extends ArrayCollection
{

    /**
     * 
     * @param SorterInterface $sorter
     */
    public function addSorter(SorterInterface $sorter)
    {
        $this->add($sorter);
    }

    /**
     * 
     * @param string $property
     * @return SorterCollection
     */
    public function getSorters($property = null)
    {
        if($property) {
            $coll = $this->filter(function(SorterInterface $sorter) use ($property){
                return $sorter->getProperty() == $property;
            });
            
            return $coll;
        }
        
        return $this;
    }

    /**
     * 
     * @param SorterInterface $sorter
     */
    public function removeSorter(SorterInterface $sorter)
    {
        $this->removeElement($sorter);
    }

    /**
     * 
     * @param string $property
     * @return SorterInterface
     */
    public function getSorter($property)
    {
        $coll = $this->getSorters($property);
        if($coll->count() > 0) {
            return $coll->first();
        }
        
        return null;
    }

    /**
     * @param array $sorters
     */
    public function setSorters(array $sorters)
    {
        foreach ($sorters as $sorter) {
            $this->addSorter($sorter);
        }
    }
}
