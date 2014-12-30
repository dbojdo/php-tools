<?php
namespace Webit\Tools\Object;

use Webit\Tools\Object\NameingStrategy\NamingStrategyInterface;
use Webit\Tools\Object\NameingStrategy\CamelCaseNamingStrategy;

class ObjectUpdater
{
    const STRATEGY_REFLECTION = 'reflection';
    const STRATEGY_SETTER = 'setter';
    const STRATEGY_GETTER = 'getter';

    /**
   * @var NamingStrategyInterface
     */
    protected $namingStrategy;

    public function __construct(NamingStrategyInterface $nameingStrategy = null)
    {
        $this->namingStrategy = $nameingStrategy ?: new CamelCaseNamingStrategy();
    }

    public function fromArray($object, array $data = array(), array $exclude = array(), $strategy = self::STRATEGY_REFLECTION, $fallback = true)
    {
        foreach ($data as $field=>$value) {
            $propertyName = $this->namingStrategy->getPropertyName($field);
            if ($strategy == self::STRATEGY_REFLECTION) {
                $success = $this->setPropertyByReflection($object, $propertyName, $value);
                if (!$success && $fallback) {
                    $this->setPropertyBySetter($object, $propertyName, $value);
                }
            } else {
                $success = $this->setPropertyBySetter($object, $propertyName, $value);
                if (!$success && $fallback) {
                    $this->setPropertyByReflection($object, $propertyName, $value);
                }
            }
        }
    }

    public function fromObject($source, $dest, array $arProperties = array(), $ommit = true, $gatherStrategy = self::STRATEGY_REFLECTION, $setStrategy = self::STRATEGY_REFLECTION)
    {
        $arProperties = $this->getValidProperites($source,$arProperties, $ommit);
        $arValues = $this->getValues($source, $arProperties, $gatherStrategy);

        $this->fromArray($dest, $arValues, array(), $setStrategy);
    }

    private function getValidProperites($source, $arProperties = array(), $ommit = true)
    {
        $refObj = new \ReflectionObject($source);
        $arObjProperties = $refObj->getProperties();

        $arKeys = array();
        foreach ($arObjProperties as $property) {
            $arKeys[] = $property->getName();
        }

        return $ommit ? array_diff($arKeys,$arProperties) : array_intersect($arKeys,$arProperties);
    }

    private function getValues($source, $arProperties, $strategy)
    {
        $arValues = array();
        foreach ($arProperties as $propertyName) {
            if ($strategy == self::STRATEGY_REFLECTION) {
                $value = $this->getPropertyByReflection($source, $propertyName);
            } else {
                try {
                    $value = $this->getPropertyByGetter($source, $propertyName);
                } catch (\Exception $e) {
                    $value = $this->getPropertyByReflection($source, $propertyName);
                }
            }

            $arValues[$propertyName] = $value;
        }

        return $arValues;
    }

    private function getPropertyByReflection($object, $propertyName)
    {
        $ref = new \ReflectionProperty(get_class($object), $propertyName);
        $ref->setAccessible(true);

        return $ref->getValue($object);
    }

    private function getPropertyByGetter($object, $propertyName)
    {
        $class = new \ReflectionClass(get_class($object));
        $getter = $this->namingStrategy->getGetterName($propertyName);

        if (!$class->hasMethod($getter)) {
            throw new \Exception('Method '. get_class($object).'::'.$getter.' doesn\'t exist.');
        }

        $method = $class->getMethod($getter);
        if ($method->isPublic() && !$method->isAbstract()) {
            return $method->invoke($object);
        }
    }

    private function setPropertyByReflection($object, $propertyName, $value)
    {
        $class = new \ReflectionClass(get_class($object));
        if (!$class->hasProperty($propertyName)) {
            return false;
        }

        $property = new \ReflectionProperty(get_class($object), $propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);

        return true;
    }

    private function setPropertyBySetter($object, $propertyName, $value)
    {
        $setter = $this->namingStrategy->getSetterName($propertyName);
        $class = new \ReflectionClass(get_class($object));
        if (!$class->hasMethod($setter)) {
            return false;
        }

        $method = $class->getMethod($setter);
        if ($method->isPublic() && !$method->isAbstract()) {
            $method->invoke($object,$value);

            return true;
        }

        return false;
    }
}
