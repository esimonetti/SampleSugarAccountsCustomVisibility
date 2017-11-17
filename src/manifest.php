<?php
$manifest = array (
    'id' => 'es_custom_accounts_visibility',
    'built_in_version' => '7.9.2.0',
    'name' => 'Custom accounts visibility - Hidden records',
    'description' => 'Custom accounts visibility - Hidden records based on a custom field flag',
    'version' => '0.1',
    'author' => 'Enrico Simonetti',
    'is_uninstallable' => true,
    'published_date' => '2017-11-17 13:37',
    'type' => 'module',
    'acceptable_sugar_versions' => array (
        'exact_matches' => array (),
        'regex_matches' => array (
            '^7.9.[\\d]+.[\\d]+$',
        ),
    ),
);

$installdefs = array (
    'copy' => array (
        array (
            'from' => '<basepath>/custom/Extension/modules/Accounts/Ext/Vardefs/enableHideAccounts.php',
            'to' => 'custom/Extension/modules/Accounts/Ext/Vardefs/enableHideAccounts.php',
        ),
        array (
            'from' => '<basepath>/custom/data/visibility/HideAccounts.php',
            'to' => 'custom/data/visibility/HideAccounts.php',
        ),
        array (
            'from' => '<basepath>/custom/src/Elasticsearch/Provider/Visibility/Filter/HideAccountsFilter.php',
            'to' => 'custom/src/Elasticsearch/Provider/Visibility/Filter/HideAccountsFilter.php',
        ),
        array (
            'from' => '<basepath>/custom/Extension/modules/Accounts/Ext/Language/en_us.hidden_c.lang.php',
            'to' => 'custom/Extension/modules/Accounts/Ext/Language/en_us.hidden_c.lang.php',
        ),
    ),
    'custom_fields' => array(
        array(
            'name' => 'hidden_c',
            'label' => 'LBL_HIDDEN',
            'type' => 'bool',
            'module' => 'Accounts',
            'default_value' => false,
            'help' => '',
            'comment' => '',
            'audited' => true,
            'mass_update' => true,
            'duplicate_merge' => true,
            'reportable' => true,
            'importable' => 'true',
        ),
    ),
);
