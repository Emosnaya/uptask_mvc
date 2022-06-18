<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
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
        $usuario = new Usuario;
        $alertas = [];


        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            if(empty($alertas)){
                $existeUsuario = Usuario::where('email', $usuario->email);

                if($existeUsuario){
                    Usuario::setAlerta('error', 'El usuario ya está registrado');
                    $alertas = Usuario::getAlertas();
                }else {
                    //Hashear el password
                    $usuario->hashPassword();
                    //Eiminar password 
                    unset($usuario->password2);

                    //Crear Token
                    $usuario->crearToken();

                    $resultado = $usuario->guardar();

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    if($resultado){
                        header('Location: /mensaje');
                    }
                }
            }
        }

        //Render a la vista
        $router->render('auth/crear', [
            'titulo' => 'Crear tu cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
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
        
        $token = s($_GET['token']);

        if (!$token) header('Location: /');

        //Encontrar al usuario
        $usuario = Usuario::where('token', $token);
        
        if(empty($usuario)){
            //No se econtró un usuario 
            Usuario::setAlerta('error', 'Token No Válido');
        }else{
            //Confirmar la cuenta
            $usuario->confirmado = 1;
            unset($usuario->password2);
            $usuario->token = null;

            //Guardar en la base de datos
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar', [
            'titulo' => 'Confirmar tu cuenta',
            'alertas' => $alertas
        ]);
    }
}