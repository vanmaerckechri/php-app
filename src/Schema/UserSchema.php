<?php

namespace App\Schema;

class UserSchema
{
    public static $schema = array(
        'id' => array(
            'type' => 'int',
            'maxLength' => 11,
            'default' => 'NOT NULL AUTO_INCREMENT',
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
            'type' => 'password',
            'minLength' => 4,
            'maxLength' => 60,
            'default' => 'NOT NULL'
        ),
        'role' => array(
            'type' => 'varchar',
            'maxLength' => 5,
            'default' => 'NOT NULL DEFAULT \'user\'',
            'only' => array('user', 'admin')
        ),
        'created_at' => array(
            'type' => 'datetime',
            'default' => 'NOT NULL DEFAULT CURRENT_TIMESTAMP'
        )
    );

    public static $options = array(
        'engine' => 'InnoDB',
        'auto_increment' => 0,
        'default charset' => 'utf8'
    );
}