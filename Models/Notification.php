<?php

use Cloud\Engine\PHP\Utils\CloudEngineStrings;

class Notification {
    
    private $idNotification;
    
    private $content;

    private $viewed;
    
    private $idUser;

    private $createdTimestamp;
    
    public function __construct($idNotification, $content, $viewed, $idUser, $createdTimestamp) {
        $this->idNotification = $idNotification;
        $this->content = $content;
        $this->viewed = $viewed;
        $this->idUser = $idUser;
        $this->createdTimestamp = $createdTimestamp;
    }
    
    public function getIdNotification() {
        return $this->idNotification;
    }

    public function getContent() {
        return $this->content;
    }

    public function wasViewed() {
        return $this->viewed;
    }

    public function getUser() {
        $user = CustomerDAO::getCustomerById($this->idUser);
        if ($user == null) {
            $user = SystemUserDAO::getSystemUserById($this->idUser);
        }
        return $user;
    }

    public function getCreatedTimestamp() {
        return $this->createdTimestamp;
    }
    
    public function getCreatedTimestampFormatted() {
        return CloudEngineStrings::timestampToHumanFormat($this->getCreatedTimestamp());
    }

}