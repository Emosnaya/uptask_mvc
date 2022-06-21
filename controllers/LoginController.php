<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {

    public static function login(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarLogin();

            if(empty($alertas)){
                //Verificar si el usuario existe
                $usuario = Usuario::where('email', $usuario->email);

                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }else{
                    //El usuario existe
                    if( password_verify($_POST['password'], $usuario->password)) {
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        header('Location /proyectos');

                    }else{
                        Usuario::setAlerta('error', 'Password Incorrecto');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();
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
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)){
                //Buscar el usuario
                $usuario = Usuario::where('email', $usuario->email);

                if($usuario && $usuario->confirmado){
                    //Generar un token
                    $usuario->crearToken();
                    unset($usuario->password2);

                    //Actualizar el usuario
                    $usuario->guardar();

                    //Enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    //Imorimir la alerta
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');
                }else{
                    Usuario::setAlerta('error', 'El usuario no existe o no está confirmado');
                    
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide', [
            'titulo' => 'Olvide Password',
            'alertas' => $alertas
        ]);
    }  
    public static function reestablecer(Router $router){

        $token = s($_GET['token']);
        $mostrar = true;
        if(!$token) header('Location: /');
        //Identificar el usuario
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no válido');
            $mostrar = false;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Añadir el nuevo Password
            $usuario->sincronizar($_POST);

            //Validar Passowrd
            $alertas = $usuario->validarPassword();
            
            if(empty($alertas)){
                //Hashear el nuevo password
                $usuario->hashPassword();
                unset($usuario->password2);
                //Eliminar el token
                $usuario->token = null;

                //Guardar en la BD
                $resultado = $usuario->guardar();
                if($resultado) header('Location: /');

            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/reestablecer', [
            'titulo' =>'Reestablecer Password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
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