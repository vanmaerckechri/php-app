<?php

namespace App\Schema;

class CategorySchema
{
    public static $schema = array(
        'id' => array(
            'type' => 'int',
            'maxLength' => 11,
            'default' => 'NOT NULL',
            'autoInc' => 'AUTO_INCREMENT',
            'primaryKey' => true
        ),
        'title' => array(
            'type' => 'varchar',
            'minLength' => 4,
            'maxLength' => 50,
            'default' => 'NOT NULL',
            'unique' => true
        )
    );

    public static $options = array(
        'engine' => 'InnoDB',
        'auto_increment' => 0,
        'default charset' => 'utf8'
    );

    public static $constraint = "";
}