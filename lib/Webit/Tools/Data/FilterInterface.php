<?php
namespace Webit\Tools\Data;

interface FilterInterface
{
    const COMPARISON_NOT = 'not';
    const COMPARISON_EQUAL = 'eq';
    const COMPARISON_GREATER = 'gt';
    const COMPARISON_LESS = 'lt';
    const COMPARISON_GREATER_OR_EQUAL = 'gte';
    const COMPARISON_LESS_OR_EQUAL = 'lte';

    const COMPARISON_DESCENDANT_NODE = 'desc';
    const COMPARISON_CHILD_NODE = 'child';
    const COMPARISON_DESCENDANT_OR_EQUAL_NODE = 'desce';
    const COMPARISON_CHILD_OR_EQUAL_NODE = 'childe';

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
    public function getComparison();

    /**
     * @return FilterParamsInterface
     */
    public function getParams();
}
