<?php

namespace Employment\Model;

use \RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;

/**
 * Employment table model
 */
class EmploymentTable
{
    private $tableGateway;

    /**
     * Constructor
     *
     * @param TableGatewayInterface $tableGateway Laminas ORM object for performing database queries
     */
    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Returns all employment rows from database
     *
     * @return ResultSetInterface|null rows set if exists or NULL
     */
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    /**
     * Returns specific employment row from database
     *
     * @param int $inn INN of a row
     *
     * @return Employment|null Employment model if exists or NULL
     */
    public function getItem(int $inn)
    {
        $rowset = $this->tableGateway->select(['inn' => $inn]);
        $row = $rowset->current();

        if(!$row) {
            return null;
        }

        return $row;
    }

    /**
     * Save row into database
     *
     * @param Employment Employment model
     */
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