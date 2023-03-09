<?php

namespace calendar\system\event\listener;

use calendar\data\event\EventAction;
use calendar\data\event\EventEditor;
use wcf\system\event\listener\IParameterizedEventListener;
use wcf\system\WCF;

class ModifyAuthorListener implements IParameterizedEventListener
{
    /**
     * @inheritDoc
     * @param \calendar\data\event\EventAction $eventObj
     */
    public function execute($eventObj, $className, $eventName, array &$parameters)
    {
        if (!WCF::getSession()->getPermission('user.calendar.addAuthor')) {
            return;
        }
        $action = $eventObj->getActionName();
        if (!($action == 'create' || $action == 'update')) {
            return;
        }
        if (!(isset($eventObj->getParameters()['data']['authorname']) || isset($eventObj->getParameters()['data']['authorID']))) {
            return;
        }
        $data = [
            'authorname' => null,
            'authorID' => null,
            'username' => $eventObj->getParameters()['data']['authorname'],
            'userID' => $eventObj->getParameters()['data']['authorID']
        ];
        if (WCF::getSession()->getPermission('user.calendar.deactivate')) {
            $data['isDisabled'] = 1;
        }
        if ($action == 'create') {
            if (
                $eventObj->getParameters()['data']['authorname'] === $eventObj->getParameters()['data']['username'] &&
                $eventObj->getParameters()['data']['authorID'] === $eventObj->getParameters()['data']['userID']
                ) {
                    return;
            }
            $data = 
            $action = new EventAction([$eventObj->getReturnValues()['returnValues']], 'update', ['data' => $data]);
        } else {
            $eventObjects = [];
            foreach ($eventObj->getObjects() as $event) {
                if (
                    $eventObj->getParameters()['data']['authorname'] === $event->username &&
                    $eventObj->getParameters()['data']['authorID'] === $event->userID
                ) {
                    continue;
                }
                array_push($eventObjects, $event);
            }
            if (empty($eventObjects)) {
                return;
            }
            $action = new EventAction($eventObjects, 'update', ['data' => $data]);
        }
        $action->executeAction();
    }
}
