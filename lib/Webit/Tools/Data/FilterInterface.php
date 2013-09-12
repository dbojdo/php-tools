<?php
namespace Webit\Tools\Data;

interface FilterInterface
{
    const COMPARISION_NOT = 'not';
    const COMPARISION_EQUAL = 'eq';
    const COMPARISION_GREATER = 'gt';
    const COMPARISION_LESS = 'lt';
    const COMPARISION_GREATER_OR_EQUAL = 'gte';
    const COMPARISION_LESS_OR_EQUAL = 'lte';

    const COMPARISION_DESCENDANT_NODE = 'desc';
    const COMPARISION_CHILD_NODE = 'child';
    const COMPARISION_DESCENDANT_OR_EQUAL_NODE = 'desce';
    const COMPARISION_CHILD_OR_EQUAL_NODE = 'childe';

    const TYPE_BOOLEAN = 'boolean';
    const TYPE_STRING = 'string';
    const TYPE_NUMERIC = 'numeric';
    const TYPE_DATE = 'date';
    const TYPE_DATETIME = 'datetime';
    const TYPE_LIST = 'list';
    const TYPE_NODE = 'node';

    /**
     * @return string
     */
    public function getProperty();

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getComparision();

    /**
     * @return FilterParamsInterface
     */
    public function getParams();
}
