<?php

namespace Action;

require_once __DIR__.'/base.php';

/**
 * Duplicate a task to another project
 *
 * @package action
 * @author  Frederic Guillot
 */
class TaskDuplicateAnotherProject extends Base
{
    /**
     * Constructor
     *
     * @access public
     * @param  integer  $project_id  Project id
     * @param  Task     $task        Task model instance
     */
    public function __construct($project_id, \Model\Task $task)
    {
        parent::__construct($project_id);
        $this->task = $task;
    }

    /**
     * Get the required parameter for the action (defined by the user)
     *
     * @access public
     * @return array
     */
    public function getActionRequiredParameters()
    {
        return array(
            'column_id' => t('Column'),
            'project_id' => t('Project'),
        );
    }

    /**
     * Get the required parameter for the event
     *
     * @access public
     * @return array
     */
    public function getEventRequiredParameters()
    {
        return array(
            'task_id',
            'column_id',
            'project_id',
        );
    }

    /**
     * Execute the action
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool            True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        if ($data['column_id'] == $this->getParam('column_id') && $data['project_id'] != $this->getParam('project_id')) {

            $this->task->duplicateToAnotherProject($data['task_id'], $this->getParam('project_id'));

            return true;
        }

        return false;
    }
}
