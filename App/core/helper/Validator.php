<?php

namespace App\core\helper;

use App\core\data\EmpresaRepo;

class Validator
{
    public static function validateLogin(string $username, string $password): array
    {
        $errors = [];

        if (empty(trim($username))) {
            $errors['username'] = "El email es obligatorio.";
        }
        
        if (empty($password)) {
            $errors['password'] = "La contraseña es obligatoria.";
        }

        if (!isset($errors['username']) && !filter_var(trim($username), FILTER_VALIDATE_EMAIL)) {
             $errors['username'] = "El formato del email es incorrecto.";
        }

        return $errors;
    }


    public static function validateEmpresaRegistration(array $data): array
    {
        $errors = [];
        
        // claves del $_POST ==> CLAVES De Span de ERROR
        $field_map = [
            'email' => 'email',
            'cif' => 'cif',
            'telefono' => 'telEmp', 
            'nombre_empresa' => 'nombre_empresa',
            'password' => 'password',
            'contacto_persona' => 'contacto_persona',
            'contacto_telefono' => 'contacto_telefono',
            'provincia' => 'provincia',
            'localidad' => 'localidad',
            'direccion' => 'direccion',
        ];
        
        foreach ($field_map as $post_key => $error_key) {
            $value = $data[$post_key] ?? ''; 
            
            $data[$post_key] = trim($value); 

            if (empty($data[$post_key])) {
                $errors[$error_key] = "Este campo es obligatorio.";
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "El formato del email no es válido.";
        } elseif (EmpresaRepo::isEmailTaken($data['email'])) { 
            $errors['email'] = "Este email ya está registrado en el sistema.";
        }

        $cif_limpio = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $data['cif'])); 
        
        if (strlen($cif_limpio) !== 9) {
            $errors['cif'] = "El CIF debe tener 9 caracteres (letras y números).";
        } elseif (EmpresaRepo::isCifTaken($cif_limpio)) { 
            $errors['cif'] = "Este CIF ya está registrado.";
        }
        
        if (strlen($data['password']) < 8) {
            $errors['password'] = "La contraseña debe tener al menos 8 caracteres.";
        }

        $telefono_empresa_limpio = preg_replace('/[^0-9]/', '', $data['telefono']);
        if (strlen($telefono_empresa_limpio) !== 9 || !is_numeric($telefono_empresa_limpio)) {
            $errors['telEmp'] = "El teléfono debe contener 9 dígitos.";
        }

        $telefono_contacto_limpio = preg_replace('/[^0-9]/', '', $data['contacto_telefono']);
        if (strlen($telefono_contacto_limpio) !== 9 || !is_numeric($telefono_contacto_limpio)) {
            $errors['contacto_telefono'] = "El teléfono de contacto debe contener 9 dígitos.";
        }
        
        return $errors;
    }


    public static function validateOfferCreation(array $data): array
    {
        $errors = [];
        
        $required_fields = ['titulo', 'desc', 'salario', 'fechaFin', 'select1']; 
        $clean_data = [];

        foreach ($required_fields as $field) {
            $value = $data[$field] ?? '';
            $clean_data[$field] = trim($value);

            if (empty($clean_data[$field])) {
                $error_key = ($field === 'desc') ? 'descripcion' : $field; 
                $errors[$error_key] = "Este campo es obligatorio.";
            }
        }
        
        $clean_data['select2'] = trim($data['select2'] ?? '');

        if (empty($errors)) {

            $salario_limpio = preg_replace('/[^0-9.]/', '', $clean_data['salario']);
            
            if (!is_numeric($salario_limpio) || (float)$salario_limpio <= 0) {
                $errors['salario'] = "El salario debe ser un valor numérico positivo.";
            }

            if (strlen($clean_data['desc']) > 250) {
                $errors['descripcion'] = "La descripción no puede exceder los 250 caracteres.";
            }

            $fechaFin = $clean_data['fechaFin'];
            $fechaActual = date('Y-m-d');
            
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaFin)) {
                $errors['fechaFin'] = "El formato de la fecha debe ser YYYY-MM-DD.";
            } 

            else if (strtotime($fechaFin) === false || strtotime($fechaFin) < strtotime($fechaActual)) {
                $errors['fechaFin'] = "La fecha debe ser válida y posterior o igual al día de hoy.";
            }


            if (!empty($clean_data['select2']) && $clean_data['select1'] === $clean_data['select2']) {
                $errors['select2'] = "Los ciclos seleccionados no pueden ser idénticos.";
                $errors['select1'] = "Los ciclos seleccionados no pueden ser idénticos.";
            }
        }
        
        return $errors;
    }

    public static function validateEmpresaRegistrationAdmin($data)
    {
        $errors = [];
        
        $field_map = [
            'email' => 'email',
            'cif' => 'cif',
            'telefono' => 'telefono',
            'nombre' => 'nombre',
        ];
        
        foreach ($field_map as $post_key => $error_key) {
            $value = $data[$post_key] ?? ''; 
            
            $data[$post_key] = trim($value); 

            if (empty($data[$post_key])) {
                $errors[$error_key] = "Este campo es obligatorio.";
            }
        }


        if (isset($data['email']) && !empty($data['email'])) {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "El formato del email no es válido.";
            } elseif (EmpresaRepo::isEmailTaken($data['email'])) { 
                $errors['email'] = "Este email ya está registrado en el sistema.";
            }
        }

        if (isset($data['cif']) && !empty($data['cif'])) {
            $cif_limpio = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $data['cif'])); 
            
            if (strlen($cif_limpio) !== 9) {
                $errors['cif'] = "El CIF debe tener 9 caracteres (letras y números).";
            } elseif (EmpresaRepo::isCifTaken($cif_limpio)) { 
                $errors['cif'] = "Este CIF ya está registrado.";
            }
        }

        if (isset($data['telefono']) && !empty($data['telefono'])) {
            $telefono_empresa_limpio = preg_replace('/[^0-9]/', '', $data['telefono']);
            if (strlen($telefono_empresa_limpio) !== 9 || !is_numeric($telefono_empresa_limpio)) {
                $errors['telefono'] = "El teléfono debe contener 9 dígitos.";
            }
        }
        
        return $errors;
    }


}
