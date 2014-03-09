<?php

require_once __DIR__.'/base.php';

use Model\Action;
use Model\Project;
use Model\Board;
use Model\Task;

class ActionTest extends Base
{
    public function testFetchActions()
    {
        $action = new Action($this->db, $this->event);
        $board = new Board($this->db, $this->event);
        $project = new Project($this->db, $this->event);

        $this->assertEquals(1, $project->create(array('name' => 'unit_test')));

        // We should have nothing
        $this->assertEmpty($action->getAll());
        $this->assertEmpty($action->getAllByProject(1));

        // We create a new action
        $this->assertTrue($action->create(array(
            'project_id' => 1,
            'event_name' => Task::EVENT_MOVE_COLUMN,
            'action_name' => 'TaskClose',
            'params' => array(
                'column_id' => 4,
            )
        )));

        // We should have our action
        $this->assertNotEmpty($action->getAll());
        $this->assertEquals($action->getAll(), $action->getAllByProject(1));

        $actions = $action->getAll();

        $this->assertEquals(1, count($actions));
        $this->assertEquals(1, $actions[0]['project_id']);
        $this->assertEquals(Task::EVENT_MOVE_COLUMN, $actions[0]['event_name']);
        $this->assertEquals('TaskClose', $actions[0]['action_name']);
        $this->assertEquals('column_id', $actions[0]['params'][0]['name']);
        $this->assertEquals(4, $actions[0]['params'][0]['value']);
    }

    public function testExecuteAction()
    {
        $task = new Task($this->db, $this->event);
        $board = new Board($this->db, $this->event);

        $project = new Project($this->db, $this->event);

        $action = new Action($this->db, $this->event);
        $action->project = $project;
        $action->task = $task;

        // We create a project
        $this->assertEquals(1, $project->create(array('name' => 'unit_test')));

        // We create a task
        $this->assertEquals(1, $task->create(array(
            'title' => 'unit_test',
            'project_id' => 1,
            'owner_id' => 1,
            'color_id' => 'red',
            'column_id' => 1,
        )));

        // We create a new action
        $this->assertTrue($action->create(array(
            'project_id' => 1,
            'event_name' => Task::EVENT_MOVE_COLUMN,
            'action_name' => 'TaskClose',
            'params' => array(
                'column_id' => 4,
            )
        )));

        // We bind events
        $action->attachEvents();

        // Our task should be open
        $t1 = $task->getById(1);
        $this->assertEquals(1, $t1['is_active']);
        $this->assertEquals(1, $t1['column_id']);

        // We move our task
        $task->move(1, 4, 1);

        // Our task should be closed
        $t1 = $task->getById(1);
        $this->assertEquals(0, $t1['is_active']);
        $this->assertEquals(4, $t1['column_id']);
    }
}
