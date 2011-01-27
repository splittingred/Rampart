<?php
/**
 * @package rampart
 */
$xpdo_meta_map['rptBanMatchBan']= array (
  'package' => 'rampart',
  'table' => 'rampart_ban_matches_bans',
  'fields' => 
  array (
    'ban' => 0,
    'ban_match' => 0,
    'field' => '',
  ),
  'fieldMeta' => 
  array (
    'ban' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'ban_match' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'field' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '60',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'pk',
    ),
  ),
  'aggregates' => 
  array (
    'Ban' => 
    array (
      'class' => 'rptBan',
      'local' => 'ban',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'BanMatch' => 
    array (
      'class' => 'rptBanMatch',
      'local' => 'ban_match',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
