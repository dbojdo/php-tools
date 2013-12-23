<?php
namespace Webit\Tools\Data;
use JMS\Serializer\Annotation as JMS;

/**
 *
 * @author dbojdo
 *
 */
class FilterParams implements FilterParamsInterface
{
    /**
   * @var bool
     */
    protected $caseSensitive = false;

    /**
     * @var string
     */
    protected $likeWildcard = FilterParamsInterface::LIKE_WILDCARD_NONE;

    /**
     * @var bool
     */
    protected $negation = false;

    /**
     * @return boolean
     * @JMS\Type("boolean")
     */
    public function getCaseSensitive()
    {
        return $this->caseSensitive;
    }

    /**
     *
     * @param bool $caseSensitive
     * @JMS\Type("boolean")
     */
    public function setCaseSensitive($caseSensitive)
    {
        $this->caseSensitive = (bool) $caseSensitive;
    }

    /**
     *
     * @return string
     * @JMS\Type("string")
     */
    public function getLikeWildcard()
    {
        return $this->likeWildcard;
    }

    /**
     *
     * @param string $likeWildcard
     */
    public function setLikeWildcard($likeWildcard)
    {
        $this->likeWildcard = $likeWildcard;
    }

    /**
     *
     * @return boolean
     */
    public function getNegation()
    {
        return $this->negation;
    }

    /**
     * @param bool $negation
     */
    public function setNegation($negation)
    {
        $this->negation = (bool) $negation;
    }
    
    public function getExpression($value) {
        $cs = $this->getCaseSensitive();
        $wc = $this->getLikeWildcard();
        switch ($wc) {
        	case FilterParamsInterface::LIKE_WILDCARD_LEFT:
        	    $value = '%'.$value;
        	    break;
        	case FilterParamsInterface::LIKE_WILDCARD_RIGHT:
        	    $value .= '%';
        	    break;
        	case FilterParamsInterface::LIKE_WILDCARD_BOTH:
        	    $value = '%'.$value . '%';
        	    break;
        }
        $value = $cs ? $value : mb_strtolower($value);
        
        return $value;
    }
}
