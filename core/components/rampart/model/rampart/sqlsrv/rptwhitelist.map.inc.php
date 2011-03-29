<?php
/**
 * @package rampart
 */
$xpdo_meta_map['rptWhiteList']= array (
  'package' => 'rampart',
  'table' => 'rampart_whitelist',
  'fields' => 
  array (
    'ip' => NULL,
    'active' => 0,
    'notes' => NULL,
    'createdon' => NULL,
    'createdby' => 0,
    'editedon' => NULL,
    'editedby' => 0,
  ),
  'fieldMeta' => 
  array (
    'ip' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '60',
      'phptype' => 'string',
      'null' => true,
      'index' => 'index',
    ),
    'active' => 
    array (
      'dbtype' => 'bit',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'notes' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => 'max',
      'phptype' => 'string',
      'null' => true,
    ),
    'createdon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'createdby' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'editedon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'editedby' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
  ),
  'indexes' => 
  array (
    'ip' => 
    array (
      'alias' => 'ip',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'ip' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'active' => 
    array (
      'alias' => 'active',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'active' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'createdby' => 
    array (
      'alias' => 'createdby',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'createdby' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'editedby' => 
    array (
      'alias' => 'editedby',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'editedby' => 
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
    'CreatedBy' => 
    array (
      'class' => 'modUser',
      'local' => 'createdby',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'EditedBy' => 
    array (
      'class' => 'modUser',
      'local' => 'editedby',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
