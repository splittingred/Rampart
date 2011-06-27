<?php
/**
 * @package rampart
 */
$xpdo_meta_map['rptFlaggedUser']= array (
  'package' => 'rampart',
  'table' => 'rampart_flagged_users',
  'fields' => 
  array (
    'username' => '',
    'password' => '',
    'flaggedon' => NULL,
    'flaggedfor' => '',
    'ip' => NULL,
    'hostname' => NULL,
    'useragent' => NULL,
    'status' => '',
    'actedon' => NULL,
    'actedby' => 0,
    'activation_email_tpl' => '',
    'activation_email_subject' => NULL,
    'activation_resource_id' => 0,
  ),
  'fieldMeta' => 
  array (
    'username' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'password' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'flaggedon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'flaggedfor' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'ip' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '60',
      'phptype' => 'string',
      'null' => true,
    ),
    'hostname' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => 'max',
      'phptype' => 'string',
      'null' => true,
    ),
    'useragent' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => 'max',
      'phptype' => 'string',
      'null' => true,
    ),
    'status' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '10',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'actedon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'actedby' => 
    array (
      'dbtype' => 'int',
      'aphptype' => 'boolean',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'activation_email_tpl' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'activation_email_subject' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => 'max',
      'phptype' => 'string',
      'null' => true,
    ),
    'activation_resource_id' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
  ),
  'indexes' => 
  array (
    'username' => 
    array (
      'alias' => 'username',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'username' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'approved' => 
    array (
      'alias' => 'approved',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'approved' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'approvedby' => 
    array (
      'alias' => 'approvedby',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'approvedby' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'activation_resource_id' => 
    array (
      'alias' => 'activation_resource_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'activation_resource_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'aggregates' => 
  array (
    'User' => 
    array (
      'class' => 'modUser',
      'local' => 'username',
      'foreign' => 'username',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'ActivationResource' => 
    array (
      'class' => 'modResource',
      'local' => 'activation_resource_id',
      'foreign' => 'username',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
