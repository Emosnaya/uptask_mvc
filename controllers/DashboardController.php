<?php 

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController {
    public static function index(Router $router){
        session_start();
        isAuth();

        $id = $_SESSION['id'];

        $proyectos = Proyecto::belongsTo('propietarioId', $id);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router){
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = new Proyecto($_POST);
            //Validacion
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)){
                //Generar una url unica
                $hash = md5(uniqid());
                $proyecto->url = $hash;
                //Creador del proyecto
                $proyecto->propietarioId = $_SESSION['id'];
                //Guardar el proyecto
                $proyecto->guardar();

                header('Location: /proyecto?id=' . $proyecto->url);
            }

        }


        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router){
        session_start();
        isAuth();

        $token = $_GET['id'];

        if(!$token) header('Location: /dashboard');

        $proyecto = Proyecto::where('url', $token);
        if($proyecto->propietarioId !== $_SESSION['id']){
            header('Location: /dashboard');
        }

        //Revisar que la persona que visita el proyecto, es la persona que lo creo


        $router->render('dashboard/proyecto',[
            'titulo' => $proyecto->proyecto
        ]);
    }

    public static function perfil(Router $router){
        session_start();
        isAuth();
        $alertas = [];
        $usuario = Usuario::find($_SESSION['id']);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPerfil();

            if(empty($alertas)){
                //Verificar si existe el usuario
                $existeUsuario = Usuario::where('email' ,$usuario->email);
                if($existeUsuario && $existeUsuario !== $usuario->id){
                    Usuario::setAlerta('error', 'El Email ya esta registrado');
                }else{
                    //Guardar usuario
                    $usuario->guardar();
                    Usuario::setAlerta('exito', 'Guardado Correctamente');
                    //Asignar el nombre nuevo a la barra
                    $_SERVER['id'] = $usuario->nombre;
                }
                
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }

    public static function cambiar_password(Router $router){
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = Usuario::find($_SESSION['id']);

            $usuario->sincronizar($_POST);
            $alertas = $usuario->nuevoPassword();

            if(empty($alertas)){
                $resultado = $usuario->comprobarPassword();

                if($resultado){
                    //Asignar el nuevo password
                    $usuario->password = $usuario->password_nuevo;
                    //Eliminar propiedades
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    //Hashear el nuevo Password
                    $usuario->hashPassword();

                    //Actualizar
                    $resultado = $usuario->guardar();
                    
                    if($resultado){
                        Usuario::setAlerta('exito', 'Password Actualizado Correctamente');
                    }
                    

                }else{
                    Usuario::setAlerta('error', 'Password Incorrecto');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Password',
            'alertas' => $alertas
        ]);

    }
}