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

    public function save(array $item)
    {
        if (empty($item['name']) || $item['email'] === '') {
            $json = json_encode($item);
            throw new \Exception("Wrong data: {$json}");
        }
        $item['id'] = uniqid();
        $_SESSION[$item['id']] = $item;
        $file = fopen('user_list.csv', 'a');
        fputcsv($file, $item);
        fclose($file);
    }

    public function search($term)
    {
        $file = fopen('user_list.csv', 'r');
        if ($file!== FALSE) {
            while (($data = fgetcsv($file, 0, ",")) !== FALSE) {
                $list[] = $data;
            }
            foreach ($list as $user){
                if(str_contains($user[0], $term)){
                    $result[] = ['name' => $user[0],
                    'email' => $user[1]
                    ]; //ищется и передаётся только имя
                }
            }
            return $result;
            fclose($file);
        }
    }

}
