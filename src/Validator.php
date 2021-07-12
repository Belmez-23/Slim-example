<?php
namespace App;

interface ValidatorInterface
{
    // Return array of errors, or empty array if no errors
    public function validate($param1, $param2, array $data);
}

class Validator implements ValidatorInterface
{
    public function validate($param1, $param2, array $anydata): array
    {
        // BEGIN (write your solution here)
        $errors = [];

        if (empty($anydata[$param1])) {
            $errors[$param1] = "Пропущены данные о ".$param1;
        }

        if (empty($anydata[$param2])) {
            $errors[$param2] = "Пропущены данные о ".$param2;
        }

        return $errors;
        // END
    }
}