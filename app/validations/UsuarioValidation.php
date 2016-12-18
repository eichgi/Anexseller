<?php
namespace App\Validations;
use Respect\Validation\Validator as v;
use App\Helpers\ResponseHelper;
use App\Models\Usuario;

class UsuarioValidation {
    public static function validate (array $model) {
        try{
            $v = v::key('nombre', v::stringType()->notEmpty())
              ->key('apellido', v::stringType()->notEmpty())
              ->key('correo', v::stringType()->notEmpty()->email());

            if(empty($model['id'])) {
                $v->key('password', v::stringType()->notEmpty()->min(4));
            }

            $v->assert($model);
        } catch (\Exception $e) {
            $rh = new ResponseHelper();
            $rh->setResponse(false, null);
            $rh->validations = $e->findMessages([
                'nombre' => '{{name}} es requerido',
                'apellido' => '{{name}} es requerido',
                'password' => '{{name}} debe tener como mínimo 4 caracteres',
                'correo' => '{{name}} debe ser un correo válido',
            ]);

            exit(json_encode($rh));
        }
    }
}