<?php
/**
 * @author kv.kn <aknk.v@protonmail.ch>
 * @product StudentLife
 * @package app\core
 */

namespace app\core;

/**
 * @property array errors = []
 */

abstract class Model
{
    const RULE_REQUIRED = 'required';
    const RULE_EMAIL = 'email';
    const RULE_MIN = 'min';
    const RULE_MAX = 'max';
    const RULE_MATCH = 'match';
    const RULE_UNIQUE = 'unique';

    public function loadData ($data)
    {
        foreach ($data as $key => $value)
            if ( property_exists($this, $key) )
                $this->{$key} = $value;
    }

    abstract public function rules(): array;

    public function labels (): array
    {
        return [];
    }

    public function getLabel ($attribute)
    {
        return $this->labels()[$attribute] ?? $attribute;
    }

    public function validate ()
    {
        foreach ( $this->rules() as $attribute => $rules )
        {
            $value = $this->{$attribute};

            foreach ( $rules as $rule )
            {
                $ruleName = $rule;

                if ( !is_string($ruleName) )
                    $ruleName = $rule[0];

                if ($ruleName === self::RULE_REQUIRED && !$value)
                    $this->addErrorForRule($attribute, self::RULE_REQUIRED);

                if ( $ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL) )
                    $this->addErrorForRule($attribute, self::RULE_EMAIL);

                if ( $ruleName === self::RULE_UNIQUE )
                {
                    $className = $rule['class'];
                    $uniqueAttr = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();

                    $stmt = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr");
                    $stmt->bindValue(":attr", $value);
                    $stmt->execute();
                    $record = $stmt->fetchObject();

                    if ($record)
                        $this->addErrorForRule( $attribute, self::RULE_UNIQUE, [ 'field' => $this->getLabel($attribute) ] );
                }

                if ( $ruleName === self::RULE_MIN && strlen($value) < $rule['min'] )
                    $this->addErrorForRule($attribute, self::RULE_MIN, $rule);

                if ( $ruleName === self::RULE_MAX && strlen($value) > $rule['max'] )
                    $this->addErrorForRule($attribute, self::RULE_MAX, $rule);

                if ( $ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']} )
                {
                    $rule['match'] = $this->getLabel($rule['match']);
                    $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
                }
            }
        }

        return empty($this->errors);
    }

    private function addErrorForRule (string $attribute, string $rule, $params = [])
    {
        $message = $this->errorMessages()[$rule] ?? '';

        foreach ($params as $key => $value)
            $message = str_replace("{{$key}}", $value, $message);

        $this->errors[$attribute][] = $message;
    }

    public function addError (string $attribute, string $message)
    {
        $this->errors[$attribute][] = $message;
    }

    public function errorMessages ()
    {
        return
        [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => "Enter a valid email address: email@example.com",
            self::RULE_MIN => 'Minimum length should be at least {min}',
            self::RULE_MAX => 'Maximum length should not exceed {max}',
            self::RULE_MATCH => 'This field does not match {match}',
            self::RULE_UNIQUE => 'Sorry, record with this {field} already exits'
        ];
    }

    public function hasError ($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError ($attribute)
    {
        return $this->errors[$attribute][0] ?? false;
    }
}