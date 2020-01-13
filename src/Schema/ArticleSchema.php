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
            'minLength' => 25,
            'maxLength' => 125,
            'default' => 'NOT NULL',
            'unique' => true
        ),
        'slug' => array(
            'type' => 'varchar',
            'minLength' => 5,
            'maxLength' => 125,
            'default' => 'NOT NULL',
            'slug' => 'title',
            'unique' => true
        ),
        'content' => array(
            'type' => 'text',
            'minLength' => 200,
            'maxLength' => 600,
            'default' => 'NOT NULL'
        ),
        'created_at' => array(
            'type' => 'datetime',
            'default' => 'NOT NULL DEFAULT CURRENT_TIMESTAMP'
        )
    );

    public static $constraint = "CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)";

    public static $options = "ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8";
}