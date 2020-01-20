<?php

namespace App\Schema;

class CategorySchema
{
    public static $schema = array(
        'id' => array(
            'type' => 'int',
            'maxLength' => 11,
            'default' => 'not null auto_increment',
            'primaryKey' => true
        ),
        'title' => array(
            'type' => 'varchar',
            'minLength' => 4,
            'maxLength' => 50,
            'default' => 'not null',
            'unique' => true
        )
    );

    public static $options = array(
        'engine' => 'InnoDB',
        'auto_increment' => 0,
        'default charset' => 'utf8'
    );
}