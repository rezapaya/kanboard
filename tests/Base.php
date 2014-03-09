<?php

require __DIR__.'/../vendor/PicoDb/Database.php';
require __DIR__.'/../core/event.php';
require __DIR__.'/../core/translator.php';
require __DIR__.'/../models/schema.php';
require __DIR__.'/../models/task.php';
require __DIR__.'/../models/acl.php';
require __DIR__.'/../models/comment.php';
require __DIR__.'/../models/project.php';
require __DIR__.'/../models/user.php';
require __DIR__.'/../models/board.php';
require __DIR__.'/../models/action.php';

abstract class Base extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->db = $this->getDbConnection();
        $this->event = new \Core\Event;
    }

    public function getDbConnection()
    {
        $db = new \PicoDb\Database(array(
            'driver' => 'sqlite',
            'filename' => ':memory:'
        ));

        if ($db->schema()->check(10)) {
            return $db;
        }
        else {
            die('Unable to migrate database schema!');
        }
    }
}
