<?php

namespace App;

class CourseRepository
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
            throw new \Exception("Wrong course id: {$id}");
        }

        return $_SESSION[$id];
    }

    public function save(array $item)
    {
        if (empty($item['title']) || $item['paid'] === '') {
            $json = json_encode($item);
            throw new \Exception("Wrong data: {$json}");
        }
        $item['id'] = uniqid();
        $_SESSION[$item['id']] = $item;
        $file = fopen('courses_list.csv', 'a');
        fputcsv($file, $item);
        fclose($file);
    }

    public function search($term)
    {
        $file = fopen('courses_list.csv', 'r');
        if ($file!== FALSE) {
            while (($data = fgetcsv($file, 0, ",")) !== FALSE) {
                $list[] = $data;
            }
            foreach ($list as $item){
                if(str_contains($item[0], $term)){
                    $result[] = ['title' => $item[0],
                        'paid' => $item[1]
                    ];  //ищется и передаётся только имя
                }
            }
            return $result;
            fclose($file);
        }
    }
}
