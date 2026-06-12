<?php

/**
 * Description of Warehouse
 *
 * @author root
 */
class Giveaway {
    
    private $id;
    private $name;
    private $email;
    private $city;
    private $phone;
    private $createdTimestamp;
    
    public function __construct($id,$name,$email,$city,$phone,$createdTimestamp) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->city = $city;
        $this->phone = $phone;
        $this->createdTimestamp = $createdTimestamp;
    }

    
}
