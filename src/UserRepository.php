<?php

namespace App;

class UserRepository
{
    public function __construct()
    {
        session_start();
    }

    public function all()
    {
        return array_values($_SESSION);
    }

    public function find(int $id)
    {
        if (!isset($_SESSION[$id])) {
            throw new \Exception("Wrong name id: {$id}");
        }

        return $_SESSION[$id];
    }

    public function save(array $user)
    {
        $user['id'] = 'id'.uniqid();
        $_SESSION[$user['id']] = $user;
    }

    public function search($term)
    {
        foreach ($_SESSION as $user){
            if(str_starts_with($user['id'], 'id') && str_contains($user['name'], $term)){
                $result[] = ['name' => $user['name'],
                    'email' => $user['email']
                ];
            }
        }
        return $result;

    }

}
