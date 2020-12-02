<?php

namespace notify_events\models;

/**
 * Class Model
 * @package notify_events\models
 */
abstract class Model implements ModelInterface
{
    /** @var array */
    protected $_attributes = [];

    /** @var array */
    protected $_errors = [];

    /**
     * Post constructor.
     * @param array|null $data
     */
    public function __construct($data = null)
    {
        if ($data) {
            $fields = static::fields();

            foreach ($fields as $field) {
                if (!array_key_exists($field, $data)) {
                    continue;
                }

                $this->$field = $data[$field];
            }
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    public function load($data)
    {
        if (!array_key_exists('form', $data)) {
            return false;
        }

        $fields = $this->rules();
        $fields = array_keys($fields);

        foreach ($fields as $field) {
            if ($field == 'id') {
                continue;
            }

            if (array_key_exists($field, $data['form'])) {
                $this->$field = wp_unslash($data['form'][$field]);
            } else {
                $this->$field = null;
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        $this->_errors = [];

        $rules = $this->rules();

        foreach ($rules as $field => $rule_list) {
            foreach ($rule_list as $rule) {
                $rule_name   = array_shift($rule);
                $rule_params = $rule;

                $this->$field = $this->validate_rule($rule_name, $field, $this->$field, $rule_params);
            }
        }

        return empty($this->_errors);
    }

    /**
     * @param string $rule_name
     * @param string $field
     * @param mixed  $value
     * @param array  $rule_params
     * @return mixed
     */
    protected function validate_rule($rule_name, $field, $value, $rule_params)
    {
        if (method_exists($this, 'rule_' . $rule_name)) {
            return call_user_func([$this, 'rule_' . $rule_name], $field, $value, $rule_params);
        } elseif (function_exists($rule_name)) {
            return call_user_func_array($rule_name, array_merge([$value], $rule_params));
        } else {
            wp_die(sprintf(__('Invalid rule %s', WPNE), $rule_name));
        }
    }

    /**
     * @param string $attribute
     * @param string $message
     */
    protected function add_error($attribute, $message)
    {
        $this->_errors[$attribute][] = $message;
    }

    /**
     * @param string $attribute
     * @return bool
     */
    public function has_error($attribute)
    {
        return array_key_exists($attribute, $this->_errors);
    }

    /**
     * @param string $attribute
     * @return mixed|null
     */
    public function get_error($attribute)
    {
        if (!$this->has_error($attribute)) {
            return null;
        }

        return reset($this->_errors[$attribute]);
    }

    /**
     * @return array
     */
    public function errors()
    {
        return $this->_errors;
    }

    /**
     * @param  string $attribute
     * @return bool
     */
    public function __isset($attribute)
    {
        $fields = $this->fields();

        return in_array($attribute, $fields);
    }

    /**
     * @param  string $attribute
     * @return mixed|null
     */
    public function __get($attribute)
    {
        $fields = $this->fields();

        if (!in_array($attribute, $fields)) {
            wp_die('Get invalid field: ' . $attribute);
        }

        if (!array_key_exists($attribute, $this->_attributes)) {
            return null;
        }

        return $this->_attributes[$attribute];
    }

    /**
     * @param string $attribute
     * @param mixed  $value
     */
    public function __set($attribute, $value)
    {
        $fields = $this->fields();

        if (!in_array($attribute, $fields)) {
            wp_die('Get invalid field: . ' . $attribute);
        }

        $this->_attributes[$attribute] = $value;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @param array $params
     * @return mixed
     */
    protected function rule_required($attribute, $value, $params)
    {
        if (($value === null) || (trim((string)$value) === '')) {
            $message = array_key_exists('message', $params) ? $params['message'] : __('Field required', WPNE);

            $this->add_error($attribute, $message);
        }

        return $value;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @param array $params
     * @return mixed
     */
    protected function rule_int($attribute, $value, $params)
    {
        if (!is_numeric($value)) {
            $message = array_key_exists('message', $params) ? $params['message'] : __('Field must be a number', WPNE);

            $this->add_error($attribute, $message);
        }

        return (int)$value;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @param array $params
     * @return mixed
     */
    protected function rule_boolean($attribute, $value, $params)
    {
        if (is_bool($value)) {
            return $value;
        } elseif ($value === 'on') {
            return true;
        }

        return false;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @param array $params
     * @return mixed
     */
    protected function rule_string($attribute, $value, $params)
    {
        if (!is_string($value)) {
            $message = array_key_exists('message', $params) ? $params['message'] : __('Field must be string', WPNE);

            $this->add_error($attribute, $message);
        } else {
            if (array_key_exists('min', $params) && array_key_exists('max', $params) && ($params['min'] == $params['max'])) {
                if (mb_strlen($value) != $params['min']) {
                    $message = array_key_exists('message_equal', $params) ? $params['message'] : sprintf(__('Field must be equal %s chars', WPNE), $params['min']);

                    $this->add_error($attribute, $message);
                }
            } else {
                if (array_key_exists('min', $params)) {
                    if (mb_strlen($value) < $params['min']) {
                        $message = array_key_exists('message_min', $params) ? $params['message'] : sprintf(__('Field must be equal or greater than %s chars', WPNE), $params['min']);

                        $this->add_error($attribute, $message);
                    }
                }
                if (array_key_exists('max', $params)) {
                    if (mb_strlen($value) > $params['max']) {
                        $message = array_key_exists('message_max', $params) ? $params['message'] : sprintf(__('Field must be equal or lower than %s chars', WPNE), $params['max']);

                        $this->add_error($attribute, $message);
                    }
                }
            }
        }

        return (string)$value;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @param array $params
     * @return mixed
     */
    protected function rule_match($attribute, $value, $params)
    {
        if (!preg_match($params['pattern'], $value)) {
            $message = array_key_exists('message', $params) ? $params['message'] : __('Invalid value', WPNE);

            $this->add_error($attribute, $message);
        }

        return $value;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @param array $params
     * @return mixed
     */
    protected function rule_in($attribute, $value, $params)
    {
        $range = (array)$params['range'];

        if (!in_array($value, $range)) {
            $message = array_key_exists('message', $params) ? $params['message'] : __('Invalid value', WPNE);

            $this->add_error($attribute, $message);
        }

        return $value;
    }

    /**
     * @param string $attribute
     * @param mixed  $value
     * @param array  $params
     * @return mixed
     */
    protected function rule_exists($attribute, $value, array $params)
    {
        /** @var PostModel $model_class */
        $model_class = $params['model'];

        $model = $model_class::findOne($value, false);

        if (!$model) {
            $message = array_key_exists('message', $params) ? $params['message'] : __('Invalid value', WPNE);

            $this->add_error($attribute, $message);
        }

        return $value;
    }

    /**
     * @param string $attribute
     * @param mixed  $value
     * @param array  $params
     * @return mixed
     */
    protected function rule_each($attribute, $value, array $params)
    {
        if (!is_array($value)) {
            $message = array_key_exists('message', $params) ? $params['message'] : __('Value must be array', WPNE);

            $this->add_error($attribute, $message);
        } else {
            $rules = (array)$params['rules'];

            foreach ($value as $idx => $val) {
                foreach ($rules as $rule) {
                    $rule_name   = array_shift($rule);
                    $rule_params = $rule;

                    $value[$idx] = $this->validate_rule($rule_name, $attribute, $val, $rule_params);
                }
            }
        }

        return $value;
    }
}
