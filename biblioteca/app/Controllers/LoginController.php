<?php
namespace App\Controllers;
use App\Models\User;

class LoginController extends Controller {
    private $modelo;

    public function __construct(){
        $this->modelo = new User();
    }

    public function index() {
        return $this->view('login', [
            'title' => 'Iniciar Sesión',
            'error' => null
        ]);
    }

    public function verificar() {

        $usuarioP  = $_POST['usuario'] ?? '';
        $passwordP = trim($_POST['password'] ?? ''); 

        if ($usuarioP === '' || $passwordP === '') {
            return $this->view('login', [
                'title' => 'Iniciar Sesión',
                'error' => 'Debes ingresar usuario y contraseña'
            ]);
        }

        
        $respuesta = $this->modelo->where('usuario', '=', $usuarioP)->first();

        if ($respuesta) {
            if (password_verify($passwordP, $respuesta['password'])) {
                //Creamos la sesion
                session_start();
                //Creamos las variables de sesion
                $_SESSION['user'] = $respuesta['usuario'];
                echo json_encode(['res'=>true]);
                exit;
            } else {
                echo json_encode(['res'=>false, 'message' => 'Contraseña Incorrecta']);
                exit();
            }
        } else {
            echo json_encode(['res' => false, 'message' => 'Usuario incorrecto']);
            exit();
        }
        
        /*if ($respuesta && $respuesta['password'] === $passwordP) {
            header('Location: /dashboard');
            exit;
        } else {
            echo json_encode(['res' => false, 'message' => 'Usuario Incorrecto']);
        }*/

        
        return $this->view('login', [
            'title' => 'Iniciar Sesión',
            'error' => 'Usuario o contraseña incorrectos'
        ]);
    }

    public function cerrar_sesion(){
        unset($_SESSION['user']);
        session_destroy();
        header('Location: /login');
    }
}