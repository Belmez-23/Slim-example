<?php

namespace App;

use mysql_xdevapi\Exception;
//use PDO;

class UserRepository extends Connection implements Validator
{
    private $pdo;

    public function __construct()
    {
        if (!$this->pdo) {
            $pdo = new Connection();
            $this->pdo = $pdo->getConnection();
        }
    }

    public function find($id)
    {
        try {
            $query = $this->pdo->prepare("SELECT * FROM Users WHERE id= ? ");
            $query->execute([$id]);
            $user = $query->fetch();
            return $user ?? [];//throw new \Exception("Wrong name id: {$id}");
        } catch (\Exception $e){
            echo $e->getMessage();
            exit;
        }
    }

    public function save(array $user)
    {
        try {
            if(!isset($user['id'])){
                $user['id'] = 'id' . uniqid();
                $query = $this->pdo->prepare("INSERT INTO Users values (:i, :n, :e)");
                $newUser = array(':e' => $user['email'], ':i' => $user['id'], ':n' => $user['name']);
            }
            else {
                $query = $this->pdo->prepare("UPDATE Users SET name = :n, email = :e WHERE id = :i");
                $newUser = array(':e' => $user['email'], ':i' => $user['id'], ':n' => $user['name']);
            }
            $query->execute($newUser);
            //$_SESSION[$user['id']] = $user;
        } catch (Exception $e){
            echo $e->getMessage();
            exit;
        }
    }

    public function search($term)
    {
        try {

            $data = $this->pdo->query('SELECT * FROM Users')->fetchAll();
            foreach ($data as $user) {
                if (str_starts_with($user['id'], 'id') && str_contains($user['name'], $term)) {
                    $result[] = ['name' => $user['name'],
                        'email' => $user['email'],
                        'id' => $user['id']
                    ];
                }
            }

        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
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
        try {
            $del = $this->pdo->prepare("DELETE FROM Users WHERE id = ?");
            $del->execute([$id]);
        } catch (Exception $e){
            echo $e->getMessage();
            exit;
        }
    }

    public function login($email){
        try{
            $log = $this->pdo->prepare("SELECT * FROM Users WHERE email = ?");
            $log->execute([$email]);
            $user = $log->fetch();
            if($user){
                $_SESSION['user'] = $user;
                return $_SESSION['user'];
            }
            else return null;
        } catch (\Exception $e){
            echo $e->getMessage();
            exit;
        }
    }
}