<?php
namespace Webit\Tools\Data;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;

class SorterCollection extends ArrayCollection
{
    /**
     * Used only for Serializer get work properly
     * @JMS\Type("array<Webit\Tools\Data\Sorter>")
     * @JMS\AccessType("public_method")
     */
    private $sorters;

    public function addSorter(SorterInterface $sorter)
    {
        $this->set($sorter->getProperty(),$sorter);
    }

    public function getSorters()
    {
        return $this->getValues() ?: array();
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
