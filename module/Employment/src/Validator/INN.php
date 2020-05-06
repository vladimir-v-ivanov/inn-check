<?php

namespace Employment\Validator;

use Laminas\Validator\AbstractValidator;

/**
 * Custom validator for INN validation
 */
class INN extends AbstractValidator
{
    /**
     * Constants for generating error messages
     */
    const FORMAT = 'format';
    const LENGTH = 'length';
    const DIGIT = 'digit';

    /**
     * Digits coefficients for format cheking
     */
    const COEFFICIENTS_10 = [2, 4, 10, 3, 5, 9, 4, 6, 8];
    const COEFFICIENTS_11 = [7, 2, 4, 10, 3, 5, 9, 4, 6, 8];
    const COEFFICIENTS_12 = [3, 7, 2, 4, 10, 3, 5, 9, 4, 6, 8];

    /**
     * Error messages templates
     */
    protected $messageTemplates = [
        self::FORMAT => "INN value '%value%' is invalid",
        self::LENGTH => "INN value '%value%' must be 10 or 12 characters long",
        self::DIGIT => "INN value '%value%' must contain only digits"
    ];

    /**
     * Validates INN value
     *
     * @param mixed $value INN value
     *
     * @return bool returns TRUE if INN value is valid, else returns FALSE
     */
    public function isValid($value)
    {
        $this->setValue($value);
        $len = strlen($value);

        if(!ctype_digit((string) $value)) {
            $this->error(self::DIGIT);
            return false;
        }

        if($len == 10) {
            $digit10 = $this->getChecksum($value, self::COEFFICIENTS_10);
            $sourceDigit9 = substr($value, 9, 1);

            if($digit10 != $sourceDigit9) {
                $this->error(self::FORMAT);
                return false;
            }
        } else if($len == 12) {
            $digit11 = $this->getChecksum($value, self::COEFFICIENTS_11);
            $digit12 = $this->getChecksum($value, self::COEFFICIENTS_12);
            $sourceDigit10 = substr($value, 10, 1);
            $sourceDigit11 = substr($value, 11, 1);

            if($digit11 != $sourceDigit10 || $digit12 != $sourceDigit11) {
                $this->error(self::FORMAT);
                return false;
            }
        } else {
            $this->error(self::LENGTH);
            return false;
        }

        return true;
    }

    protected function getChecksum($inn, $coefficients) {
        $n = 0;

        foreach($coefficients as $i => $k) {
            $n += $k * (int) substr($inn, $i, 1);
        }

        return $n % 11 % 10;
    }
}