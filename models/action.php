<?php

namespace Model;

require_once __DIR__.'/base.php';
require_once __DIR__.'/task.php';

use \SimpleValidator\Validator;
use \SimpleValidator\Validators;

class Action extends Base
{
    const TABLE = 'actions';
    const TABLE_PARAMS = 'action_has_params';

    /**
     * Return the name and description of available actions
     *
     * @access public
     * @return array
     */
    public function getAvailableActions()
    {
        return array(
            'TaskClose' => t('Close the task'),
            'TaskAssignSpecificUser' => t('Assign the task to a specific user'),
            'TaskAssignCurrentUser' => t('Assign a task to the person who make the action'),
        );
    }

    /**
     * Return actions and parameters for a given project
     *
     * @access public
     * @return array
     */
    public function getAllByProject($project_id)
    {
        $actions = $this->db->table(self::TABLE)->eq('project_id', $project_id)->findAll();

        foreach ($actions as &$action) {
            $action['params'] = $this->db->table(self::TABLE_PARAMS)->eq('action_id', $action['id'])->findAll();
        }

        return $actions;
    }

    /**
     * Return all actions and parameters
     *
     * @access public
     * @return array
     */
    public function getAll()
    {
        $actions = $this->db->table(self::TABLE)->findAll();

        foreach ($actions as &$action) {
            $action['params'] = $this->db->table(self::TABLE_PARAMS)->eq('action_id', $action['id'])->findAll();
        }

        return $actions;
    }

    public function create(array $values)
    {
        $this->db->startTransaction();

        $action = array(
            'project_id' => $values['project_id'],
            'event_name' => $values['event_name'],
            'action_name' => $values['action_name'],
        );

        if (! $this->db->table(self::TABLE)->save($action)) {
            $this->db->cancelTransaction();
            return false;
        }

        $action_id = $this->db->getConnection()->getLastId();

        foreach ($values['params'] as $param_name => $param_value) {

            $action_param = array(
                'action_id' => $action_id,
                'name' => $param_name,
                'value' => $param_value,
            );

            if (! $this->db->table(self::TABLE_PARAMS)->save($action_param)) {
                $this->db->cancelTransaction();
                return false;
            }
        }

        $this->db->closeTransaction();

        return true;
    }

    /**
     * Load all actions and attach events
     *
     * @access public
     */
    public function attachEvents()
    {
        foreach ($this->getAll() as $action) {

            require_once __DIR__.'/../actions/'.$action['action_name'].'.php';

            $actionClassName = '\Action\\'.$action['action_name'];
            $listener = new $actionClassName($action['project_id']);
            $listener->task = new Task($this->db, $this->event);

            foreach ($action['params'] as $param) {
                $listener->setParam($param['name'], $param['value']);
            }

            $this->event->attach($action['event_name'], $listener);
        }
    }
}
