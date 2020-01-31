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
            'type' => 'int',
            'maxLength' => 1,
            'default' => 'not null default 1',
            'only' => array(1, 2)
        ),
        'created_at' => array(
            'type' => 'datetime',
            'default' => 'not null default current_timestamp'
        ),
        'status' => array(
            'type' => 'int',
            'maxLength' => 1,
            'default' => 'not null default 1',
        ),
        'token' => array(
            'type' => 'varchar',
            'maxLength' => 32,
            'default' => 'null',
        )
    );

    public static $options = array(
        'engine' => 'InnoDB',
        'auto_increment' => 0,
        'default charset' => 'utf8'
    );
}