<?php

namespace EmploymentTest\Model;

use Employment\Model\EmploymentTable;
use Employment\Model\Employment;
use PHPUnit\Framework\TestCase;
use Laminas\Db\ResultSet\ResultSetInterface;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Prophecy\PhpUnit\ProphecyTrait;

class EmploymentTableTest extends TestCase
{
    use ProphecyTrait;

    protected function setUp(): void
    {
        $this->tableGateway = $this->prophesize(TableGatewayInterface::class);
        $this->employmentTable = new EmploymentTable($this->tableGateway->reveal());
    }

    public function testFetchAllReturnsAllResultSet()
    {
        $resultSet = $this->prophesize(ResultSetInterface::class)->reveal();
        $this->tableGateway->select()->willReturn($resultSet);

        $this->assertSame($resultSet, $this->employmentTable->fetchAll());
    }
}