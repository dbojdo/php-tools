<?php
namespace Webit\Tools\Data;

use Doctrine\Common\Collections\ArrayCollection;

class SorterCollection extends ArrayCollection
{
    public function addSorter(SorterInterface $sorter)
    {
        $this->set($sorter->getProperty(),$sorter);
    }

    public function getSorters()
    {
        return $this->getValues();
    }

    public function removeSorter(SorterInterface $sorter)
    {
        $this->removeElement($sorter);
    }

    public function getSorter($property)
    {
        return $this->get($property);
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
