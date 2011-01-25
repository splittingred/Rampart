<?php
/**
 * @package rampart
 */
$xpdo_meta_map['rptBan']= array (
  'package' => 'rampart',
  'table' => 'rampart_bans',
  'fields' => 
  array (
    'bangroup' => 0,
    'ip_low1' => 0,
    'ip_high1' => 0,
    'ip_low2' => 0,
    'ip_high2' => 0,
    'ip_low3' => 0,
    'ip_high3' => 0,
    'ip_low4' => 0,
    'ip_high4' => 0,
    'hostname' => NULL,
    'email' => NULL,
    'username' => NULL,
    'matches' => 0,
  ),
  'fieldMeta' => 
  array (
    'bangroup' => 
    array (
      'dbtype' => 'int',
      'precision' => '255',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'ip_low1' => 
    array (
      'dbtype' => 'int',
      'precision' => '3',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'ip_high1' => 
    array (
      'dbtype' => 'int',
      'precision' => '3',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'ip_low2' => 
    array (
      'dbtype' => 'int',
      'precision' => '3',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'ip_high2' => 
    array (
      'dbtype' => 'int',
      'precision' => '3',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'ip_low3' => 
    array (
      'dbtype' => 'int',
      'precision' => '3',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'ip_high3' => 
    array (
      'dbtype' => 'int',
      'precision' => '3',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'ip_low4' => 
    array (
      'dbtype' => 'int',
      'precision' => '3',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'ip_high4' => 
    array (
      'dbtype' => 'int',
      'precision' => '3',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'hostname' => 
    array (
      'dbtype' => 'tinytext',
      'phptype' => 'string',
      'null' => true,
    ),
    'email' => 
    array (
      'dbtype' => 'tinytext',
      'phptype' => 'string',
      'null' => true,
    ),
    'username' => 
    array (
      'dbtype' => 'tinytext',
      'phptype' => 'string',
      'null' => true,
    ),
    'matches' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
  ),
  'aggregates' => 
  array (
    'BanGroup' => 
    array (
      'class' => 'rptBanGroup',
      'local' => 'bangroup',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
