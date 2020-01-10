<?php

namespace App\Schema;

class ArticleSchema
{
    public static $schema = array(
        'id' => array(
            'type' => 'int',
            'maxLength' => 11,
            'default' => 'NOT NULL',
            'autoInc' => 'AUTO_INCREMENT',
            'primaryKey' => true
        ),
        'user_id' => array(
            'type' => 'int',
            'maxLength' => 11,
            'default' => 'NOT NULL',
        ),
        'title' => array(
            'type' => 'varchar',
            'minLength' => 4,
            'maxLength' => 254,
            'default' => 'NOT NULL',
            'unique' => true
        ),
        'content' => array(
            'type' => 'text',
            'minLength' => 10,
            'maxLength' => 500,
            'default' => 'NOT NULL'
        ),
        'created_at' => array(
            'type' => 'datetime',
            'default' => 'NOT NULL'
        )
    );

    public static $constraint = "CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)";

    public static $options = "ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8";
}