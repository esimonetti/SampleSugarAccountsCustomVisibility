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
    protected $db_field_name = 'c_hidden';
    protected $elastic_field_name = 'hidden_record';
    
    /**
     * {@inheritdoc}
     */
    public function addVisibilityWhere(&$query)
    {
        if (!$this->isSecurityApplicable()) {
            return $query;
        }

        $whereClause = sprintf(
            "%s.%s != '1'",
            $this->getTableAlias(),
            $this->db_field_name
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
            $sugarQuery->where()->notEquals($this->db_field_name, true);
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
        return DBManagerFactory::getInstance()->getValidDBName($this->bean->table_name, false, 'alias');
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
        $mapping->addNotAnalyzedField($this->elastic_field_name);
    }

    /**
     * {@inheritdoc}
     */
    public function elasticProcessDocumentPreIndex(Document $document, \SugarBean $bean, Visibility $provider)
    {
        $document->setDataField($this->elastic_field_name, !empty($bean->{$this->db_field_name}) ? true : false);
    }

    /**
     * {@inheritdoc}
     */
    public function elasticGetBeanIndexFields($module, Visibility $provider)
    {
        // this will make sure the field is retrieved for the bean, so that it is available on elasticProcessDocumentPreIndex for manipulation
        return array($this->db_field_name);
    }

    /**
     * {@inheritdoc}
     */
    public function elasticAddFilters(User $user, \Elastica\Filter\BoolFilter $filter, Visibility $provider)
    {
        if (!$this->isSecurityApplicable()) {
            return;
        }

        $filter->addMust($provider->createFilter('HideAccounts', array('field_name' => $this->elastic_field_name, 'field_value' => false)));
    }
}
