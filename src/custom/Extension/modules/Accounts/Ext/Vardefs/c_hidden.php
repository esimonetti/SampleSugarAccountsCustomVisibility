<?php

$dictionary['Account']['fields']['c_hidden'] = array (
    'name' => 'c_hidden',
    'vname' => 'LBL_C_HIDDEN',
    'type' => 'bool',
    'default' => '0',
    'reportable' => true,
    'comment' => '',
    'importable' => true,
    'duplicate_merge' => 'enabled',
    'audited' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
);

$dictionary['Account']['indices']['c_hidden'] = array (
    'name' => 'idx_acc_c_hidden',
    'type' => 'index',
    'fields' => array (
        'c_hidden'
    ),
);
