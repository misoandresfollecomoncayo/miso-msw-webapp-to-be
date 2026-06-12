<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClubickPayment
 *
 * @author andres
 */
class ClubickPayment {
    
    public $id;
    public $date;
    public $amount;
    public $method;
    public $user;
    public $idClubick;
    
    public function __construct($id, $date, $amount, $method, $user, $idClubick) {
        $this->id = $id;
        $this->date = $date;
        $this->amount = $amount;
        $this->method = $method;
        $this->user = $user;
        $this->idClubick = $idClubick;
    }
    
}
