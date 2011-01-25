<?php
/**
 * @package rampart
 */
$xpdo_meta_map['rptBanGroup']= array (
  'package' => 'rampart',
  'table' => 'rampart_ban_groups',
  'fields' => 
  array (
    'name' => '',
    'description' => NULL,
    'createdon' => NULL,
    'expireson' => NULL,
    'notes' => NULL,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'createdon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'expireson' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'notes' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
  ),
  'composites' => 
  array (
    'Bans' => 
    array (
      'class' => 'rptBan',
      'local' => 'id',
      'foreign' => 'bangroup',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
