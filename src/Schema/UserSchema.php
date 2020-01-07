<?php

namespace App\Schema;

class UserSchema
{
    public static $schema = array(
        'id' => array(
            'type' => 'int',
            'maxLength' => 11,
            'default' => 'NOT NULL',
            'autoInc' => 'AUTO_INCREMENT',
            'primaryKey' => true
        ),
        'email' => array(
            'type' => 'email',
            'minLength' => 4,
            'maxLength' => 254,
            'default' => 'NOT NULL',
            'unique' => true
        ),
        'username' => array(
            'type' => 'varchar',
            'minLength' => 4,
            'maxLength' => 30,
            'default' => 'NOT NULL',
            'unique' => true
        ),
        'password' => array(
            'type' => 'varchar',
            'minLength' => 4,
            'maxLength' => 60,
            'default' => 'NOT NULL'
        ),
        'role' => array(
            'type' => 'varchar',
            'maxLength' => 5,
            'default' => 'NOT NULL DEFAULT \'user\'',
            'only' => array('user', 'admin')
        )
    );

    public static $constraint;

    public static $options = "ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8";
}