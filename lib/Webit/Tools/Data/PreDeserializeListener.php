<?php
namespace Webit\Tools\Data;

use JMS\Serializer\EventDispatcher\PreDeserializeEvent;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;

class PreDeserializeListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
                array('event' => 'serializer.pre_deserialize', 'method' => 'onPreDeserialize'),
        );
    }

    /**
     * @param PreDeserializeEvent $event
     */
    public function onPreDeserialize(PreDeserializeEvent $event)
    {
        $type = $event->getType();
        switch ($type['name']) {
            case 'Webit\Tools\Data\FilterCollection':
                $data = $event->getData();
                $event->setData(array('filters'=>$data));
                break;
            case 'Webit\Tools\Data\SorterCollection':
                $data = $event->getData();
                $event->setData(array('sorters'=>$data));
                break;
        }
    }
}
