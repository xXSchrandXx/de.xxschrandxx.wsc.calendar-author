<?php

namespace calendar\system\event\listener;

use wcf\data\user\User;
use wcf\system\event\listener\IParameterizedEventListener;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;

class AuthorAddListener implements IParameterizedEventListener
{
    /**
     * @inheritDoc
     */
    public function execute($eventObj, $className, $eventName, array &$parameters)
    {
        if (!WCF::getSession()->getPermission('user.calendar.addAuthor')) {
            return;
        }
        $this->$eventName($eventObj);
    }

    /**
     * @param \calendar\form\EventEditForm $eventObj
     */
    public function readFormParameters($eventObj) {
        if (isset($_POST['username']) && \is_string($_POST['username'])) {
            $eventObj->additionalFields['authorname'] = $_POST['username'];
        }
    }

    /**
     * @param \calendar\form\EventEditForm $eventObj
     */
    public function validate($eventObj) {
        if (!array_key_exists('authorname', $eventObj->additionalFields)) {
            throw new UserInputException('username');
        }
        $author = User::getUserByUsername($eventObj->additionalFields['authorname']);
        if (!$author->userID) {
            throw new UserInputException('username', 'notFound');
        }
        $eventObj->additionalFields['authorID'] = $author->userID;
    }

    /**
     * @param \calendar\form\EventEditForm $eventObj
     */
    public function assignVariables($eventObj) {
        if (isset($eventObj->event)) {
            $username = $eventObj->event->username;
        } else {
            $username = WCF::getUser()->username;
        }
        WCF::getTPL()->assign([
            'username' => $username
        ]);
    }
}
