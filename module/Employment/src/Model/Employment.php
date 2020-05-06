<?php

namespace Employment\Model;

use \DomainException;
use Laminas\InputFilter\{
    InputFilter,
    InputFilterAwareInterface,
    InputFilterInterface
};
use Employment\Validator\INN;

class Employment implements InputFilterAwareInterface
{
    public $inn;
    public $updated_at;
    public $status;

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

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

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

    public function updateRequired(): bool
    {
        if(is_null($this->updated_at)) {
            return true;
        }

        $timestamp = strtotime($this->updated_at);

        return $timestamp < time() - 24 * 60 * 60;
    }
}