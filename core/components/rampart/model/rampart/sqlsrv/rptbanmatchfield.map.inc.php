<?php
/**
 * @package rampart
 */
$xpdo_meta_map['rptBanMatchField']= array (
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
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'ban_match' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'field' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '60',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'pk',
    ),
  ),
  'indexes' => 
  array (
    'PRIMARY' => 
    array (
      'alias' => 'PRIMARY',
      'primary' => true,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'ban' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'ban_match' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'field' => 
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
