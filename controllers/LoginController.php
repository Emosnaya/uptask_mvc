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
    public static function olvide( Router $router){
        

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

        }


        $router->render('auth/olvide', [
            'titulo' => 'Olvide Password'
        ]);
    }  
    public static function reestablecer(Router $router){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

        }

        $router->render('auth/reestablecer', [
            'titulo' =>'Reestablecer Password'
        ]);
    } 
    public static function mensaje(Router $router){

        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada Exitosamente'
        ]);
    }
    public static function confirmar(Router $router){
        

        $router->render('auth/confirmar', [
            'titulo' => 'Confirmar tu cuenta'
        ]);
    }
}