<?php
/**
 * File MixedValueWrapper.php
 * Created at: 2014-12-30 18-33
 *
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */

namespace Webit\Tools\Serializer;


class MixedValueWrapper
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @param mixed $value
     * @return MixedValueWrapper
     */
    public static function create($value)
    {
        return new self($value);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}