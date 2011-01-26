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
    'flaggedon' => NULL,
    'flaggedfor' => '',
    'ip' => NULL,
    'hostname' => NULL,
    'useragent' => NULL,
  ),
  'fieldMeta' => 
  array (
    'username' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'flaggedon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'flaggedfor' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'ip' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '60',
      'phptype' => 'string',
      'null' => true,
    ),
    'hostname' => 
    array (
      'dbtype' => 'tinytext',
      'phptype' => 'string',
      'null' => true,
    ),
    'useragent' => 
    array (
      'dbtype' => 'tinytext',
      'phptype' => 'string',
      'null' => true,
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
  ),
);
