<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function getUsuario()
    {
        return response([
            'usuarios' => DB::table('usuarios')->get()
        ]);
    }

    public function autenticarUsuario(Request $request)
    {
        try {
            $login = $request->get('login');
            $usuario = DB::table('usuarios')->where('login', $login)->first();

            if (! $usuario) {
                throw new \Exception("Usuário não encontrado", 400);
            }

            if (! $usuario->senha_alterada) {
                throw new \Exception("Usuário bloqueado. Favor, solicite a alteração de senha em esqueci minha senha", 400);
            }

            if (! password_verify($request->get('senha'), $usuario->senha)) {
                throw new \Exception("Senha inválida", 400);
            }

            return response([
                'message' => 'Acesso Liberado'
            ]);

        }catch (\Exception $exception) {
            return response([
                'message' => $exception->getMessage()
            ],400);
        }
    }
}
