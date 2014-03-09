<?php

namespace Action;

require_once __DIR__.'/Base.php';

class TaskAssignCurrentUser extends Base
{
    public function getActionRequiredParameters()
    {
        return array(
            'column_id' => t('Column'),
            'user_id' => t('User'),
        );
    }

    public function getEventRequiredParameters()
    {
        return array(
            'task_id',
            'column_id',
        );
    }

    public function execute(array $data)
    {
        if ($this->isExecutable($data)) {

            if ($data['column_id'] == $this->getParam('column_id')) {
                $this->task->update(array(
                    'id' => $data['task_id'],
                    'owner_id' => $this->getModel('acl')->getUserId(),
                ));
            }
        }
    }
}
