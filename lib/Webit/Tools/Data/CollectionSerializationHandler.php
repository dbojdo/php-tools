<?php
namespace Webit\Tools\Data;

use JMS\Serializer\Handler\ArrayCollectionHandler;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\VisitorInterface;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Context;
use JMS\Serializer\Handler\SubscribingHandlerInterface;

class CollectionSerializationHandler implements SubscribingHandlerInterface {
    public static function getSubscribingMethods()
    {        
        $formats = array('json', 'xml', 'yml');
        $collectionTypes = array(
            'Webit\Tools\Data\FilterCollection',
            'Webit\Tools\Data\SorterCollection'
        );
    
        foreach ($collectionTypes as $type) {
            foreach ($formats as $format) {
                $methods[] = array(
                    'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                    'type' => $type,
                    'format' => $format,
                    'method' => 'serializeCollection',
                );
    
                $methods[] = array(
                    'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                    'type' => $type,
                    'format' => $format,
                    'method' => 'deserializeCollection',
                );
            }
        }
    
        return $methods;
    }
    

    public function serializeCollection(VisitorInterface $visitor, Collection $collection, array $type, Context $context)
    {
        // We change the base type, and pass through possible parameters.
        $type['name'] = 'array';
    
        return $visitor->visitArray($collection->toArray(), $type, $context);
    }
    
    public function deserializeCollection(VisitorInterface $visitor, $data, array $type, Context $context)
    {
        $cls = $type['name'];
        $coll = new $cls($visitor->visitArray($data, $type, $context));
        
        return $coll;
    }
}
