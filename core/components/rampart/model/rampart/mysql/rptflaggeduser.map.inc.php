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
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'password' => 
    array (
      'dbtype' => 'varchar',
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
    'status' => 
    array (
      'dbtype' => 'varchar',
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
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'activation_email_tpl' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'activation_email_subject' => 
    array (
      'dbtype' => 'tinytext',
      'phptype' => 'string',
      'null' => true,
    ),
    'activation_resource_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
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
