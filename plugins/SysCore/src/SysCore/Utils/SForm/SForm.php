<?php 

namespace SysCore\Utils\SForm;

class SForm {
    
    private static $forms = [];
    
    public function formatTitle($text) {
        return $text;
    }
    
    public function formatButton($text) {
        return $text;
    }
    
    public function validate($data, $rules) {
        $errors = [];
        foreach ($rules as $i => $rule) {
            $fields = explode("|", $rule);
            foreach ($fields as $field) {
                $arr = explode(":", $field);
                $k = $arr[0];
                $args = (isset($arr[1])) ? explode(",", $arr[1]) : [];
                
                switch ($k) {
                    case "required":
                        if (is_null($data[$i]) || $data[$i] == '') {
                            $errors[$i] = "The $i field is required.";
                        }
                        break;
                    case "numeric":
                        if (!is_numeric($data[$i])) {
                            $errors[$i] = "The $i should be numeric.";
                        }
                        break;
                }
            }
        }
        return $errors;
    }
    
    public function getError($name, $data) {
        if (isset($data['errors'])) {
            $errors = $data['errors'];
            return (isset($errors[$name])) ? $errors[$name] : '';
        }
        return '';
    }
    
    public static function getForms() {
        return self::$forms;
    }
    
    public static function registerForm($formName, $formClass) {
        self::$forms[$formName] = $formClass;
        return true;
    }
    
    public static function getForm($formName = '') {
        if (trim($formName) == '') {
            $formName = 'main-menu';
        }
        if (isset(self::$forms[$formName])) {
            return self::$forms[$formName];
        }
        return null;
    }
    
}