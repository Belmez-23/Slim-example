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

    public function save(array $course)
    {
        $course['id'] = 'co'.uniqid();
        $_SESSION[$course['id']] = $course;
    }

    public function search($term)
    {
        foreach ($_SESSION as $course){
            if(str_starts_with($course['id'], 'co') && str_contains($course['title'], $term)){
                $result[] = ['title' => $course['title'],
                    'paid' => $course['paid']
                ];
            }
        }
        return $result;
    }
}
