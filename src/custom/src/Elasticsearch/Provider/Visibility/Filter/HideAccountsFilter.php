<?php

// Enrico Simonetti
// enricosimonetti.com
//
// 2017-11-17 on Sugar 7.9.2.0
// filename: custom/src/Elasticsearch/Provider/Visibility/Filter/HideAccountsFilter.php 

namespace Sugarcrm\Sugarcrm\Elasticsearch\Provider\Visibility\Filter;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\Visibility\Visibility;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\Visibility\Filter\FilterInterface;

class HideAccountsFilter implements FilterInterface
{
    use FilterTrait;

    public function buildFilter(array $options = array())
    {
        $filter = new \Elastica\Filter\Term();
        $filter->setTerm('hidden_record', true);
        return $filter;
    }
}
