<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controller
 *
 * @author lab-cpu
 */
class Controller {
    
    public function sesssionActive(){
        if (session_status() == PHP_SESSION_NONE){
            session_start();
        }
    }
    
    public function validateSession(){
        if(empty($_SESSION))
        {
            return false;
        }else{
            return true;
        }
        
    }

        public function call_view($nameView){
        include_once "./src/views/".$nameView;
    }
    
}
