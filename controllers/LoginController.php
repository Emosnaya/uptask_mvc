<?php

namespace Controllers;

use MVC\Router;

class LoginController {

    public static function login(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

        }

        $router->render('auth/login', [

        ]);

    } 
    public static function logout(){

    }
    public static function crear(){


        if($_SERVER['REQUEST_METHOD'] === 'POST'){

        }
    } 
    public static function olvide(){
        

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

        }
    }  
    public static function reestablecer(){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

        }
    } 
    public static function mensaje(){

    }
    public static function confirmar(){
        
    }
}