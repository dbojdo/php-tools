<?php
/**
 * File TypeAliasHandler.php
 * Created at: 2014-12-30 08-17
 *
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */

namespace Webit\Tools\Serializer;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\VisitorInterface;

class TypeAliasHandler implements SubscribingHandlerInterface
{

    /**
     * @var array
     */
    private $classMap = array();

    /**
     * @param array $classMap
     */
    public function __construct(array $classMap)
    {
        $this->classMap = $classMap;
    }

    /**
     * @param string $type
     * @param string $targetClass
     */
    public function setTypeMap($type, $targetClass)
    {
        $this->classMap[$type] = $targetClass;
    }

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
        $interfaces = array(
            'Webit\Shipment\Address\DeliveryAddressInterface',
            'Webit\Shipment\Address\SenderAddressInterface',
            'Webit\Shipment\Consignment\ConsignmentInterface',
            'Webit\Shipment\Consignment\DispatchConfirmationInterface',
            'Webit\Shipment\Parcel\ParcelInterface',
            'Webit\Shipment\Vendor\VendorInterface'
        );

        $support = array();
        foreach ($interfaces as $interface) {
            foreach (array('json', 'xml') as $format) {
                $support[] = array(
                    'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                    'type' => $interface,
                    'format' => $format,
                    'method' => 'resolveInterface'
                );

                $support[] = array(
                    'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                    'type' => $interface,
                    'format' => $format,
                    'method' => 'resolveInterface'
                );
            }
        }
    }

    /**
     * @param VisitorInterface $visitor
     * @param mixed $data
     * @param array $type
     * @param Context $context
     * @return mixed
     */
    public function resolveInterface(VisitorInterface $visitor, $data, array $type, Context $context)
    {
        if (! isset($this->classMap[$type[0]])) {
            throw new \UnexpectedValueException(sprintf('Unsupported type: "%s"', $type[0]));
        }

        $type[0] = $this->classMap[$type[0]];

        return $context->accept($data, $type);
    }
}
