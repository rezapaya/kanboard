<?php

require_once __DIR__.'/base.php';

use Model\Task;
use Model\Comment;

class TaskTest extends Base
{
    public function testDateFormat()
    {
        $t = new \Model\Task($this->db, $this->event);

        $this->assertEquals('2014-03-05', date('Y-m-d', $t->getTimestampFromDate('05/03/2014', 'd/m/Y')));
        $this->assertEquals('2014-03-05', date('Y-m-d', $t->getTimestampFromDate('03/05/2014', 'm/d/Y')));
        $this->assertEquals('2014-03-05', date('Y-m-d', $t->getTimestampFromDate('3/5/2014', 'm/d/Y')));
        $this->assertEquals('2014-03-05', date('Y-m-d', $t->getTimestampFromDate('5/3/2014', 'd/m/Y')));
        $this->assertEquals('2014-03-05', date('Y-m-d', $t->getTimestampFromDate('5/3/14', 'd/m/y')));
        $this->assertEquals(0, $t->getTimestampFromDate('5/3/14', 'd/m/Y'));
        $this->assertEquals(0, $t->getTimestampFromDate('5-3-2014', 'd/m/Y'));
    }
}
