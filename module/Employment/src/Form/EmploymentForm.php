<?php

namespace Employment\Form;

use Laminas\Form\Form;

/**
 * INN cheking form
 */
class EmploymentForm extends Form
{
    /**
     * Constructor
     *
     * Argument $name will be ignored
     *
     * @param string|null $name name of the form
     */
    public function __construct($name = null)
    {
        parent::__construct('inn_check');

        $this->add([
            'name' => 'inn',
            'type' => 'text',
            'options' => [
                'label' => 'INN'
            ],
            'attributes' => [
                'class' => 'form-control w-100'
            ]
        ]);
        $this->add([
            'name' => 'updated_at',
            'type' => 'hidden'
        ]);
        $this->add([
            'name' => 'status',
            'type' => 'hidden'
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'options' => [
                'label' => '&nbsp;',
                'label_options' => [
                    'disable_html_escape' => true
                ]
            ],
            'attributes' => [
                'value' => 'Check',
                'id'    => 'submitbutton',
                'class' => 'btn btn-primary w-100'
            ],
        ]);
    }
}