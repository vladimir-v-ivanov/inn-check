<?php

namespace Employment\Model;

use \DomainException;
use Laminas\InputFilter\{
    InputFilter,
    InputFilterAwareInterface,
    InputFilterInterface
};
use Employment\Validator\INN;

/**
 * Employment model
 */
class Employment implements InputFilterAwareInterface
{
    public $inn;
    public $updated_at;
    public $status;

    /**
     * Fill model properties from array
     *
     * @param array $data array of data where keys is properties names
     */
    public function exchangeArray(array $data)
    {
        if(isset($data['inn'])) {
            $this->inn = $data['inn'];
        }

        if(isset($data['updated_at'])) {
            $this->updated_at = $data['updated_at'];
        }

        if(isset($data['status'])) {
            $this->status = $data['status'];
        }
    }

    /**
     * Sets input filter for model data validation
     *
     * @param InputFilterInterface $inputFilter input filter object with validation rules
     *
     * @throws DomainException
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    /**
     * Returns current input filter of the model
     *
     * @return InputFilterInterface input filter object with validation rules
     */
    public function getInputFilter()
    {
        if($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'inn',
            'required' => true,
            'validators' => [
                [
                    'name' => INN::class
                ]
            ]
        ]);

        $this->inputFilter = $inputFilter;

        return $this->inputFilter;
    }

    /**
     * Checks update status is required
     *
     * @return bool returns TRUE if update required, otherwise returns FALSE
     */
    public function updateRequired(): bool
    {
        if(is_null($this->updated_at)) {
            return true;
        }

        $timestamp = strtotime($this->updated_at);

        // TODO: hardcoded
        return $timestamp < time() - 24 * 60 * 60;
    }
}