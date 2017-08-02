<?php
class BlogPost extends ObjectModel
{

    public $id_post;
    public $title;
    public $date_add;
    public $body;
    public static $definition = array(
        'table' => 'post',
        'primary' => 'id_post',
        'fields' => array(
            'title' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isString'),
            'body' => array('type' => self::TYPE_STRING, 'validate' => 'isString')
        ),
    );
}
