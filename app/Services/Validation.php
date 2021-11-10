<?php

namespace app\Services;

class Validation
{
    public function validate(array $rules = [], array $message = []) : array
    {
        $errors = [];
        $request = new Request();
        if (!empty($rules)) {
            foreach ($rules as $field => $rule_item) {
                $rule_item_arr = explode('|', $rule_item); // tách ra mảng bởi |
                foreach ($rule_item_arr as $item) {
                    $rule_name = null;
                    $rule_value = null;

                    $rule_arr = explode(':', $item); // tách ra mảng bởi :
                    $rule_name = reset($rule_arr); // lấy giá trị đầu tiên
                    if (count($rule_arr) > 1) {
                        $rule_value = end($rule_arr); // lấy giá trị cuối cùng
                    }
                    if ($rule_name == 'required') { // bắt buộc nhập
                        if (empty($request->input($field))) {
                            $errors[$field][] = $message["$field.$rule_name"] ?? "$field is $rule_name";
                        }
                    }
                    if ($rule_name == 'min') { // tối thiểu
                        if (strlen($request->input($field)) < $rule_value) {
                            $errors[$field][] = $message["$field.$rule_name"] ?? "$field $rule_name $rule_value character";
                        }
                    }
                    if ($rule_name == 'max') { // tối đa
                        if (strlen($request->input($field)) > $rule_value) {
                            $errors[$field][] = $message["$field.$rule_name"] ?? "$field $rule_name $rule_value character";
                        }
                    }
                    if ($rule_name == 'email') { // kiểm tra email
                        if (!filter_var($request->input($field), FILTER_VALIDATE_EMAIL)) {
                            $errors[$field][] = $message["$field.$rule_name"] ?? "$field is not $rule_name";
                        }
                    }
                    if ($rule_name == 'match') { // giá trị sẽ bằng mới key được chỉ định
                        if ($request->input($field) != $request->input($rule_value)) {
                            $errors[$field][] = $message["$field.$rule_name"] ?? "$field not $rule_name";
                        }
                    }
                }
            }
        }
        return $errors;
    }
}