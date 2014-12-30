<?php
namespace Webit\Tools\Object\NameingStrategy;

class CamelCaseNamingStrategy implements NamingStrategyInterface
{
    public function getPropertyName($field)
    {
        $property = $this->underscoreToCamelCase($field);

        return $property;
    }

    public function getGetterName($field)
    {
        return 'get' . $this->underscoreToCamelCase($field,true);
    }

    public function getSetterName($field)
    {
        return 'set' . $this->underscoreToCamelCase($field,true);
    }

    private function underscoreToCamelCase($string, $capitalizeFirstCharacter = false)
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }
}
