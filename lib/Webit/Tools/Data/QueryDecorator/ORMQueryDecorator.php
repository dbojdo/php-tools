<?php
namespace Webit\Tools\Data\QueryDecorator;

use Webit\Tools\Data\FilterParams;
use Webit\Tools\Data\FilterParamsInterface;
use Webit\Tools\Data\FilterInterface;
use Webit\Tools\Data\SorterCollection;
use Webit\Tools\Data\FilterCollection;

use Doctrine\ORM\QueryBuilder;

class ORMQueryDecorator
{
    /**
     * @var QueryBuilder
     */
    protected $qb;

    /**
     * @var array
     */
    protected $filterMap;

    /**
     * @var array
     */
    protected $sorterMap;
    
    /**
     *
     * @param QueryBuilder $qb
     */
    public function __construct(QueryBuilder $qb, $filterMap = array(), $sorterMap = array())
    {
        $this->qb = $qb;
        $this->filterMap = $filterMap;
        $this->sorterMap = empty($sorterMap) ? $filterMap : $sorterMap;
    }

    /**
     *
     * @param  QueryBuilder                                       $qb
     * @param  array                                              $propertyMap
     * @return \Webit\Tools\Data\QueryDecorator\ORMQueryDecorator
     */
    public static function create(QueryBuilder $qb, $filterMap = array(), $sorterMap = array())
    {
        return new self($qb, $filterMap, $sorterMap);
    }

    /**
     *
     * @param FilterCollection $filterCollection
     */
    public function applyFilters(FilterCollection $filterCollection)
    {
        foreach ($filterCollection->getValues() as $filter) {
            $property = $filter->getProperty();
            $property = $this->getQueryProperty($property,'filter');
            if(count($property) == 0) {
                return $this;
            }
            
            switch ($filter->getType()) {
                case FilterInterface::TYPE_STRING:
                    $this->applyStringFilter($property, $filter);
                break;
                case FilterInterface::TYPE_NUMERIC:
                    $this->applyNumericFilter($property, $filter);
                break;
                case FilterInterface::TYPE_DATE:
                case FilterInterface::TYPE_DATETIME:
                    $this->applyDateFilter($property, $filter);
                break;
                case FilterInterface::TYPE_BOOLEAN:
                    $this->applyBooleanFilter($property, $filter);
                break;
                case FilterInterface::TYPE_LIST:
                    $this->applyListFilter($property, $filter);
                break;
            }
        }

        return $this;
    }

    protected function applyDateFilter($property, FilterInterface $filter)
    {
        $qb = $this->qb;
        $property = array_shift($property);

        $paramName = 'date_' . substr(md5(serialize($filter) . microtime()),0,10);
        $value = new \DateTime($filter->getValue());
        if ($filter->getType() == FilterInterface::TYPE_DATE) {
            $value->setTime(0, 0, 0);
        }

        switch ($filter->getComparison()) {
            case FilterInterface::COMPARISON_GREATER:
                $qb->andWhere($qb->expr()->gt($property,(':'.$paramName)));
                $qb->setParameter($paramName, $value,'datetime');
            break;
            case FilterInterface::COMPARISON_LESS:
                $qb->andWhere($qb->expr()->lt($property,(':'.$paramName)));
                $qb->setParameter($paramName, $value,'datetime');
            break;
            case FilterInterface::COMPARISON_LESS_OR_EQUAL:
                if ($filter->getType() == FilterInterface::TYPE_DATE) {
                    $value->add(new \DateInterval('P1D'));
                    $qb->andWhere($qb->expr()->lt($property,(':'.$paramName)));
                    $qb->setParameter($paramName, $value,'datetime');
                } else {
                    $qb->andWhere($qb->expr()->lte($property,(':'.$paramName)));
                    $qb->setParameter($paramName, $value,'datetime');
                }
            break;
            case FilterInterface::COMPARISON_GREATER_OR_EQUAL:
                $qb->andWhere($qb->expr()->gte($property,(':'.$paramName)));
                $qb->setParameter($paramName, $value,'datetime');
            break;
            default:
                // FilterInterface::COMPARISON_EQUAL:
                if ($filter->getType() == FilterInterface::TYPE_DATE) {
                    $qb->andWhere($qb->expr()->gte($property,(':'.$paramName)));
                    $qb->setParameter($paramName, $value,'datetime');

                    $valueTo = clone($value);
                    $valueTo->add(new \DateInterval('P1D'));
                    $qb->andWhere($qb->expr()->lt($property,(':'.$paramName.'_2')));
                    $qb->setParameter(($paramName.'_2'), $valueTo,'datetime');
                } else {
                    $qb->andWhere($qb->expr()->eq($property,(':'.$paramName)));
                    $qb->setParameter($paramName, $value,'datetime');
                }
        }
    }

    protected function applyStringFilter($property, FilterInterface $filter)
    {
        $qb = $this->qb;
        $value = (string) $filter->getValue();
        if (empty($value)) {
            return;
        }

        $value = $this->getStringValueExpression($filter->getParams(), $value);

        $cs = $filter->getParams()->getCaseSensitive();
        $arCond = array();
        foreach ($property as $f) {
            $cond = $qb->expr()->like(($cs ? $f : $qb->expr()->lower($f)),$value);
            $arCond[] = $filter->getParams()->getNegation() ? $qb->expr()->not($cond) : $cond;
        }

        if (count($arCond) > 0) {
            $this->qb->andWhere(call_user_func_array(array($qb->expr(),'orx'), $arCond));
        }
    }

    protected function applyNumericFilter($property, FilterInterface $filter)
    {
        $qb = $this->qb;
        $value = (float) $filter->getValue();
        $arCond = array();
        foreach ($property as $f) {
            switch ($filter->getComparison()) {
                case FilterInterface::COMPARISON_GREATER:
                    $arCond[] = $qb->expr()->gt($f,$value);
                    break;
                case FilterInterface::COMPARISON_LESS:
                    $arCond[] = $qb->expr()->lt($f,$value);
                    break;
                case FilterInterface::COMPARISON_LESS_OR_EQUAL:
                    $arCond[] = $qb->expr()->lte($f,$value);
                    break;
                case FilterInterface::COMPARISON_GREATER_OR_EQUAL:
                    $arCond[] = $qb->expr()->gte($f,$value);
                    break;
                case FilterInterface::COMPARISON_NOT:
                    $arCond[] = $qb->expr()->neq($f,$value);
                    break;
                default:
                    //FilterInterface::COMPARISON_EQUAL:
                    $arCond[] = $qb->expr()->eq($f,$value);
            }
        }

        if (count($arCond) > 0) {
            $this->qb->andWhere(call_user_func_array(array($qb->expr(),'orx'), $arCond));
        }
    }

    protected function applyBooleanFilter($property, FilterInterface $filter)
    {
        $qb = $this->qb;
        $value = (boolean) $filter->getValue();
        $arCond = array();
        foreach ($property as $f) {
            $arCond[] = $qb->expr()->eq($f,$qb->expr()->literal($value));
        }

        if (count($arCond) > 0) {
            $this->qb->andWhere(call_user_func_array(array($qb->expr(),'orx'), $arCond));
        }
    }

    protected function applyListFilter($property, FilterInterface $filter)
    {
        $qb = $this->qb;
        $arValue = explode(',',$filter->getValue());
        $arCond = array();
        foreach ($property as $f) {
            $arCond[] = $qb->expr()->in($f,$arValue);
        }
        
        if (count($arCond) > 0) {
            $this->qb->andWhere(call_user_func_array(array($qb->expr(),'orx'), $arCond));
        }
    }

    /**
     *
     * @param SorterCollection $sorterCollection
     */
    public function applySorters(SorterCollection $sorterCollection)
    {
        foreach ($sorterCollection as $sorter) {
            $arFields = $this->getQueryProperty($sorter->getProperty(),'sort');
            foreach ($arFields as $f) {
                $this->qb->addOrderBy($f,$sorter->getDirection());
            }
        }

        return $this;
    }

    public function applySearching($query,array $fields, FilterParamsInterface $filterParams = null)
    {
        $filterParams = $filterParams ?: new FilterParams(array('case_sensitive'=>false,'like_wildcard'=>FilterParamsInterface::LIKE_WILDCARD_RIGHT));

        $query = $this->getStringValueExpression($filterParams, $query);
        $cs = $filterParams->getCaseSensitive();
        $qb = $this->qb;
        $arCond = array();
        foreach ($fields as $field) {
            $arField = $this->getQueryProperty($field, 'filter');
            // FIXME: tylko po polach typu string
            // FIXME: możliwość ustalenia like %saf% lub %dfssa lub dsfd%
            // FIXME: możliwość ustalenia dodatkowych filtrów (lowercase, usuwanie białych znaków, przecinków itd)
            foreach ($arField as $f) {
                $cond = $qb->expr()->like(($cs ? $f : $qb->expr()->lower($f)),$query);
                $arCond[] = $filterParams->getNegation() ? $qb->expr()->not($cond) : $cond;
            }
        }

        if (count($arCond) > 0) {
            $this->qb->andWhere(call_user_func_array(array($qb->expr(),'orx'), $arCond));
        }

        return $this;
    }

    private function getQueryProperty($property, $scope = 'filter')
    {
        $propertyMap = $scope == 'filter' ? $this->filterMap : $this->sorterMap;
        
        $name = (array) $this->underscoreToCamelCase($property);
        $alias = $this->qb->getRootAlias();
        
        $arResultProperties = array();
        if (key_exists($property,$propertyMap)) {
            $ar = isset($propertyMap[0]) ? $propertyMap[$property] : array($propertyMap[$property]);
            $arProperties = $propertyMap[$property];
            if(!key_exists(0, $arProperties)) {
                $arProperties = array($arProperties);
            }
            
            foreach ($arProperties as $arProperty) {
                $alias = isset($arProperty['alias']) ? $arProperty['alias'] : $alias;
                $name = isset($arProperty['name']) ? (array) $arProperty['name'] : $name;
                foreach ($name as $n) {
                    $a = $alias;
                    if (is_array($n)) {
                        $a = key_exists('alias',$n) ? $n['alias'] : $alias;
                        $n = key_exists('name',$n) ? $n['name'] : array_shift($n);
                    }
                    $arResultProperties[] = $alias ? ($a. '.'.$n) : $n;
                }
            }
        } else {
            foreach($name as $n) {
                $arResultProperties[] = $alias . '.' . $n;
            }
        }

        return $arResultProperties;
    }

    private function getStringValueExpression(FilterParamsInterface $filterParams, $value)
    {
        return $this->qb->expr()->literal($filterParams->getExpression($value));
    }

    private function underscoreToCamelCase($string, $capitalizeFirstCharacter = false)
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }

    /**
     *
     * @param integer $limit
     */
    public function applyLimit($limit)
    {
        if ($limit === null || (int) $limit > 0) {
            $this->qb->setMaxResults($limit);
        }

        return $this;
    }

    /**
     *
     * @param integer $offset
     */
    public function applyOffset($offset)
    {
        if ($offset === null || (int) $offset > 0) {
            $this->qb->setFirstResult($offset);
        }

        return $this;
    }

    /**
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->qb;
    }
}
