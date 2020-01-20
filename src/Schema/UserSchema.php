<?php

namespace App\Schema;

class UserSchema
{
    public static $schema = array(
        'id' => array(
            'type' => 'int',
            'maxLength' => 11,
            'default' => 'auto_increment',
            'primaryKey' => true
        ),
        'email' => array(
            'type' => 'email',
            'minLength' => 5,
            'maxLength' => 254,
            'default' => 'not null',
            'unique' => true
        ),
        'username' => array(
            'type' => 'varchar',
            'minLength' => 5,
            'maxLength' => 30,
            'default' => 'not null',
            'unique' => true
        ),
        'password' => array(
            'type' => 'password',
            'minLength' => 4,
            'maxLength' => 60,
            'default' => 'not null'
        ),
        'role' => array(
            'type' => 'varchar',
            'maxLength' => 5,
            'default' => 'not null default \'user\'',
            'only' => array('user', 'admin')
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