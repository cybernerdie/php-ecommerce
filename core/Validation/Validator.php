<?php

namespace App\Core\Validation;

use Illuminate\Database\Capsule\Manager as DB;
use Exception;

class Validator
{
    private $errors = [];
    private $validatedData = [];

    /**
     * Validate input parameters against specified rules.
     *
     * @param array $params
     * @param array $rules
     *
     * @return array
     */
    public function validate($params, $rules)
    {
        foreach ($rules as $field => $rule) {
            $value = $params[$field] ?? null;

            if (is_null($value)) {
                if (strpos($rule, 'nullable') !== false) {
                    continue;
                } else { 
                    $this->addError($field, "The {$field} field is required.");
                    continue;
                }
            }

            $validators = explode('|', $rule);
    
            foreach ($validators as $validator) {
                $validatorName = $validator;
                $validatorParam = null;
                if (strpos($validator, ':') !== false) {
                    list($validatorName, $validatorParam) = explode(':', $validator);
                }
    
                $methodName = 'validate' . ucfirst($validatorName);
    
                $result = match (true) {
                    method_exists($this, $methodName) => $this->$methodName($params, $field, $validatorParam),
                    default => $this->validateRequired($params, $field),
                };
            }

            if (!$this->hasError($field)) {
                $this->validatedData[$field] = $value;
            }
        }

        return $this->errors;
    }

    /**
     * Check if there are any validation errors.
     *
     * @return bool
     */
    public function failed() 
    {
        return !empty($this->errors);
    }

    /**
     * Get the validation errors.
     *
     * @return array
     */
    public function getErrors() 
    {
        return $this->errors;
    }

    /**
     * Add an error message for a specific field.
     *
     * @param string $field
     * @param string $message
     *
     * @return void
     */
    private function addError($field, $message) 
    {
        $this->errors[$field][] = $message;
    }

    /**
     * Determine if there are any errors for a specific field.
     *
     * @param string $field
     *
     * @return bool
     */
    private function hasError($field)
    {
        return !empty($this->errors[$field]);
    }

    /**
     * Get the validated data.
     *
     * @return array
     */
    public function validatedData() 
    {
        return $this->validatedData;
    }

    /**
     * Validate if a field is required.
     *
     * @param array $params
     * @param string $field
     * @return bool
     */
    private function validateRequired($params, $field) 
    {
        if (array_key_exists($field, $params) && !empty($params[$field])) {
            return true;
        } else {
            $this->addError($field, "The {$field} field is required.");
            return false;
        }
    }
    
    /**
     * Validate if a field is a valid email address.
     *
     * @param array $params
     * @param string $field
     * @return bool
     */
    private function validateEmail($params, $field) 
    {
        if (filter_var($params[$field], FILTER_VALIDATE_EMAIL) !== false) {
            return true;
        } else {
            $this->addError($field, "The {$field} field is not a valid email address.");
            return false;
        }
    }

    /**
     * Validate if a field meets the minimum length/value requirement.
     *
     * @param array $params
     * @param string $field
     * @param int $length
     * @return bool
     */
    private function validateMin($params, $field, $length) 
    {
        $value = $params[$field];
    
        // Check if the value is a string
        if (is_string($value)) {
            if (strlen($value) >= $length) {
                return true;
            } else {
                $this->addError($field, "The {$field} field must be at least {$length} characters.");
                return false;
            }
        }
        // Check if the value is an integer
        elseif (is_numeric($value)) {
            if ($value >= $length) {
                return true;
            } else {
                $this->addError($field, "The {$field} field must be at least {$length}.");
                return false;
            }
        }
        // Add other validation logic for other types if needed
        else {
            $this->addError($field, "The {$field} field must be a string or an integer.");
            return false;
        }
    }
    
    /**
     * Validate if a field meets the maximum length/value requirement.
     *
     * @param array $params
     * @param string $field
     * @param int $length
     * @return bool
     */
    private function validateMax($params, $field, $length) 
    {
        $value = $params[$field];
    
        // Check if the value is a string
        if (is_string($value)) {
            if (strlen($value) <= $length) {
                return true;
            } else {
                $this->addError($field, "The {$field} field must be at most {$length} characters.");
                return false;
            }
        }
        
        // Check if the value is an integer
        elseif (is_numeric($value)) {
            if ($value <= $length) {
                return true;
            } else {
                $this->addError($field, "The {$field} field must be at most {$length}.");
                return false;
            }
        }

        // Add other validation logic for other types if needed
        else {
            $this->addError($field, "The {$field} field must be a string or an integer.");
            return false;
        }
    }
    
    /**
     * Validate if a given value is a string.
     *
     * @param array  $params
     * @param string $field 
     * @param int    $length
     *
     * @return bool
     */
    private function validateString($params, $field, $length)
    {
        if (is_string($params[$field])) {
            return true;
        } else {
            $this->addError($field, "The {$field} field must be a string.");
            return false;
        }
    }

    /**
     * Validate if a given value is an integer.
     *
     * @param array  $params
     * @param string $field 
     * @param int    $length
     *
     * @return bool
     */
    private function validateInteger($params, $field, $length)
    {
        $value = $params[$field];
        
        if (!ctype_digit((string) $value) && !is_int($value)) {
            $this->addError($field, "The {$field} field must be an integer.");
            return false;
        }

        return true;
    }

    /**
     * Validate if a given value is an array and not empty.
     *
     * @param array  $params
     * @param string $field
     *
     * @return bool
     */
    private function validateArray($params, $field)
    {
        $value = $params[$field];
    
        if (!is_array($value)) {
            $this->addError($field, "The {$field} field must be an array.");
            return false;
        }
        
        if (empty($value)) {
            $this->addError($field, "The {$field} field must contain at least one item.");
            return false;
        }
    
        return true;
    } 
    
     /**
     * Validate a field against a regular expression pattern.
     *
     * @param array $params
     * @param string $field
     * @param string $pattern
     * @return bool
     */
    private function validateRegex($params, $field, $pattern)
    {
        $value = $params[$field];

        if (!preg_match($pattern, $value)) {
            $this->addError($field, "The {$field} field must be at least 6 characters long and contain at least one uppercase letter, one numeric digit, and one special character.");
            return false;
        }

        return true;
    }

    /**
     * Validate that the given value is unique for the given database table and column.
     *
     * @param string $attribute
     * @param mixed $value
     * @param mixed $parameters
     * @return bool
     * @throws InvalidArgumentException
     */
    private function validateUnique(array $params, $field, $validator): bool|string
    {
        $validator = explode(',', $validator);
        $table = isset($validator[0]) ? $validator[0] : null;
        $column = isset($validator[1]) ? $validator[1] : null;

        if (!$table || !$column) {
            throw new Exception("Invalid validator for unique rule.");
        }
        
        $value = $params[$field];

        $result = DB::table($table)->where($column, $value)->first();

        if ($result) {
            $this->addError($field, "The {$field} has already been taken.");
            return false;
        }

        return true;
    }

    private function validateIn($params, $field, string $validator)
    {
        $value = $params[$field];
        $values = explode(',', $validator);

        if (!in_array($value, $values)) {
            $this->addError($field, "The {$field} field must be one of these: " . implode(',', $values) . ".");
            return false;
        }

        return true;
    }

    /**
     * Validate that the given value exists for the given database table and column.
     *
     * @param string $attribute
     * @param mixed $value
     * @param mixed $parameters
     * @return bool
     * @throws InvalidArgumentException
     */
    private function validateExists(array $params, $field, $validator): bool|string
    {
        $validator = explode(',', $validator);
        $table = isset($validator[0]) ? $validator[0] : null;
        $column = isset($validator[1]) ? $validator[1] : null;

        if (!$table || !$column) {
            throw new Exception("Invalid validator for exists rule.");
        }
        
        $value = $params[$field];

        $result = DB::table($table)->where($column, $value)->first();

        if (!$result) {
            $this->addError($field, "The {$field} given is invalid");
            return false;
        }

        return true;
    }
}
