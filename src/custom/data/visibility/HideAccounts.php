<?php

// Enrico Simonetti
// enricosimonetti.com
//
// 2017-11-17 on Sugar 7.9.2.0
// filename: custom/data/visibility/HideAccounts.php

use Sugarcrm\Sugarcrm\Elasticsearch\Provider\Visibility\StrategyInterface as ElasticStrategyInterface;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\Visibility\Visibility;
use Sugarcrm\Sugarcrm\Elasticsearch\Analysis\AnalysisBuilder;
use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Mapping;
use Sugarcrm\Sugarcrm\Elasticsearch\Adapter\Document;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\Visibility\Filter;

class HideAccounts extends SugarVisibility implements ElasticStrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function addVisibilityWhere(&$query)
    {
        if (!$this->isSecurityApplicable()) {
            return $query;
        }

        $whereClause = sprintf(
            "%s.hidden_c != '1'",
            $this->getTableAlias()
        );

        if (!empty($query)) {
            $query .= " AND $whereClause ";
        } else {
            $query = $whereClause;
        }

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function addVisibilityWhereQuery(SugarQuery $sugarQuery, $options = array())
    {
        return $this->addVisibilityQuery($sugarQuery);
    }

    /**
     * {@inheritdoc}
     */
    public function addVisibilityQuery(SugarQuery $sugarQuery)
    {
        if ($this->isSecurityApplicable()) {
            $sugarQuery->where()->notEquals('hidden_c', true);
        }
        return $sugarQuery;
    }

    /**
     * Check if we can apply our security model
     * @return false|true
     */
    protected function isSecurityApplicable()
    {
        global $current_user;

        if (!$current_user instanceof User) {
            return false;
        }

        if ($this->bean->disable_row_level_security) {
            return false;
        }

        if ($current_user->isAdminForModule($this->bean->module_name)) {
            return false;
        }

        return true;
    }

    /**
     * Get table alias
     * @return string
     */
    protected function getTableAlias()
    {
        return DBManagerFactory::getInstance()->getValidDBName($this->bean->get_custom_table_name(), false, 'alias');
    }

    /**
     * {@inheritdoc}
     */
    public function elasticBuildAnalysis(AnalysisBuilder $analysisBuilder, Visibility $provider)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function elasticBuildMapping(Mapping $mapping, Visibility $provider)
    {
        $mapping->addNotAnalyzedField('hidden_record');
    }

    /**
     * {@inheritdoc}
     */
    public function elasticProcessDocumentPreIndex(Document $document, \SugarBean $bean, Visibility $provider)
    {
        $document->setDataField('hidden_record', !empty($bean->hidden_c) ? true : false);
    }

    /**
     * {@inheritdoc}
     */
    public function elasticGetBeanIndexFields($module, Visibility $provider)
    {
        // this will make sure the field is retrieved for the bean, so that it is available on elasticProcessDocumentPreIndex for manipulation
        return array('hidden_c');
    }

    /**
     * {@inheritdoc}
     */
    public function elasticAddFilters(User $user, \Elastica\Filter\BoolFilter $filter, Visibility $provider)
    {
        if (!$this->isSecurityApplicable()) {
            return;
        }

        $filter->addMustNot($provider->createFilter('HideAccounts'));
    }
}
