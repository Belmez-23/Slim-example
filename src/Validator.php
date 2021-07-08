<?php
namespace App;

interface ValidatorInterface
{
    // Return array of errors, or empty array if no errors
    public function validate(array $data);
}

class Validator implements ValidatorInterface
{
    public function validate(array $anydata)
    {
        // BEGIN (write your solution here)
        $errors = [];
        if (!$anydata) {
            $errors['name'] = "Can't be blank";
        }
        return $errors;
        // END
    }
}