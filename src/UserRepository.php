<?php

namespace App;
//include 'Validator.php';

class UserRepository implements Validator
{
    public function __construct()
    {
        session_start();
    }

    public function all()
    {
        return array_values($_SESSION);
    }

    public function find($id)
    {
        return  $_SESSION[$id] ?? [];//throw new \Exception("Wrong name id: {$id}");
    }

    public function save(array $user)
    {
        $user['id'] ?? $user['id'] = 'id'.uniqid();
        $_SESSION[$user['id']] = $user;
    }

    public function search($term)
    {
        foreach ($_SESSION as $user){
            if(str_starts_with($user['id'], 'id') && str_contains($user['name'], $term)){
                $result[] = ['name' => $user['name'],
                    'email' => $user['email'],
                    'id' => $user['id']
                ];
            }
        }
        return $result;
    }

    public function validate(array $data)
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = "Пропущены данные об имени";
        }

        if (empty($data['email'])) {
            $errors['email'] = "Пропущены данные о email";
        }

        return $errors;
    }

    public function destroy($id)
    {
        unset($_SESSION[$id]);
    }
}
