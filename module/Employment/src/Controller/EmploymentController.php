<?php

namespace Employment\Controller;

use Employment\Form\EmploymentForm;
use Employment\Model\{
    Employment,
    EmploymentTable,
    APIClient
};
use Laminas\Mvc\Controller\AbstractActionController;
use \RuntimeException;
use \DomainException;

class EmploymentController extends AbstractActionController
{
    private $table;
    private $apiClient;

    public function __construct(EmploymentTable $table, APIClient $apiClient)
    {
        $this->table = $table;
        $this->apiClient = $apiClient;
    }

    public function checkAction()
    {
        $form = new EmploymentForm;
        $employment = new Employment;
        $request = $this->getRequest();

        if(!$request->isPost()) {
            return [
                'form' => $form
            ];
        }

        $post = $request->getPost();
        $form->setInputFilter($employment->getInputFilter());
        $form->setData($post);

        if(!$form->isValid()) {
            return [
                'form' => $form
            ];
        }

        $existingRow = $this->table->getItem($post->inn);

        if(!$existingRow || $existingRow->updateRequired()) {
            try {
                $status = $this->apiClient->getEmploymentStatus($post->inn);
            } catch(RuntimeException $e) {
                return [
                    'form' => $form,
                    'error' => 'Unexpected error'
                ];
            } catch(DomainException $e) {
                return [
                    'form' => $form,
                    'error' => $e->getMessage()
                ];
            }

            $employment->exchangeArray(array_merge($form->getData(), ['status' => (int) $status]));

            $this->table->saveItem($employment);
        }

        $this->redirect()->toRoute('inn_result', [
            'inn' => $post->inn
        ]);
    }

    public function resultAction()
    {
        $employment = $this->table->getItem((int) $this->params()->fromRoute('inn', 0));

        if(!$employment) {
            $this->redirect()->toRoute('inn');
        }

        return [
            'employment' => $employment
        ];
    }

    public function errorAction()
    {
        return;
    }
}