<?php

namespace Controllers;

use MVC\Router;

class LoginController {

    public static function login(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

        }

        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);

    } 
    public static function logout(){

    }
    public static function crear(Router $router){


        if($_SERVER['REQUEST_METHOD'] === 'POST'){

        }

        //Render a la vista
        $router->render('auth/crear', [
            'titulo' => 'Crear tu cuenta'
        ]);
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