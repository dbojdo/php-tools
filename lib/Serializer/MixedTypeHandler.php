<?php
/**
 * File MixedTypeHandler.php
 * Created at: 2014-12-30 08-52
 *
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */

namespace Webit\Tools\Serializer;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\VisitorInterface;

class MixedTypeHandler implements SubscribingHandlerInterface
{
    /**
     * Return format:
     *
     *      array(
     *          array(
     *              'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
     *              'format' => 'json',
     *              'type' => 'DateTime',
     *              'method' => 'serializeDateTimeToJson',
     *          ),
     *      )
     *
     * The direction and method keys can be omitted.
     *
     * @return array
     */
    public static function getSubscribingMethods()
    {
        $supported = array();
        $supported[] = array(
            'format' => 'json',
            'type' => 'mixed',
            'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
            'method' => 'deserializeMixed'
        );

        $supported[] = array(
            'format' => 'json',
            'type' => 'mixed',
            'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
            'method' => 'serializeMixed'
        );

        return $supported;
    }

    public function serializeMixed(VisitorInterface $visitor, $data, array $type, Context $context)
    {
        return $data->getValue();
    }

    public function deserializeMixed(VisitorInterface $visitor, $data, array $type, Context $context)
    {
        return $data;
    }
}
