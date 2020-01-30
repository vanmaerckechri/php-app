<?php

namespace App\Schema;

class ArticleSchema
{
    public static $schema = array(
        'id' => array(
            'type' => 'int',
            'maxLength' => 11,
            'default' => 'auto_increment',
            'primaryKey' => true
        ),
        'user_id' => array(
            'type' => 'int',
            'maxLength' => 11,
            'default' => 'not null',
            'foreignKey' => array(
                'table' => 'user',
                'column' => 'id',
                'constraint' => true
            )
        ),
        'title' => array(
            'type' => 'varchar',
            'minLength' => 25,
            'maxLength' => 75,
            'default' => 'not null',
            'unique' => true
        ),
        'slug' => array(
            'type' => 'varchar',
            'minLength' => 5,
            'maxLength' => 75,
            'default' => 'not null',
            'slug' => 'title',
            'unique' => true
        ),
        'content' => array(
            'type' => 'text',
            'minLength' => 350,
            'maxLength' => 1024,
            'default' => 'not null'
        ),
        'img_file' => array(
            'type' => 'varchar',
            'minLength' => 5,
            'maxLength' => 75,
            'default' => 'null',
            'unique' => true
        ),
        'created_at' => array(
            'type' => 'datetime',
            'default' => 'not null default current_timestamp'
        )
    );

    public static $options = array(
        'engine' => 'InnoDB',
        'auto_increment' => 0,
        'default charset' => 'utf8'
    );
}