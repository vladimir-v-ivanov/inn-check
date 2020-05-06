<?php

namespace Employment\Model;

use \RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;

class EmploymentTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getItem(int $inn)
    {
        $rowset = $this->tableGateway->select(['inn' => $inn]);
        $row = $rowset->current();

        if(!$row) {
            return null;
        }

        return $row;
    }

    public function saveItem(Employment $employment)
    {
        $data = [
            'inn' => $employment->inn,
            'status' => $employment->status
        ];

        if($this->getItem($employment->inn) instanceof Employment) {
            $this->tableGateway->update($data, ['inn' => $employment->inn]);
        } else {
            $this->tableGateway->insert($data);
        }
    }
}