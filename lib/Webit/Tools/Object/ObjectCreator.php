<?php
namespace Webit\Tools\Object;

class ObjectCreator
{
    /**
     * Create instance of given $class without using constructor
     * @param  string                    $class
     * @throws \InvalidArgumentException
     * @return mixed                     instance of $class
     */
    public static function newInstance($class)
    {
        if (!class_exists($class,true)) {
            throw new \InvalidArgumentException('Required class ('.$class.') couldn\'t be found');
        }

        return unserialize(sprintf('O:%d:"%s":0:{}', strlen($class), $class));
    }
}
