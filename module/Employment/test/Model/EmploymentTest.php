<?php

namespace EmploymentTest\Model;

use Employment\Model\Employment;
use PHPUnit\Framework\TestCase;
use DomainException;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\InputFilter\InputFilter;

class EmploymentTest extends TestCase
{
    public function testNewObjectHasNullProperties()
    {
        $employment = new Employment;

        $this->assertNull($employment->inn);
        $this->assertNull($employment->updated_at);
        $this->assertNull($employment->status);
    }

    public function testExchangeArray()
    {
        $employment = new Employment;
        $data = [
            'inn' => '1234567890',
            'updated_at' => date('Y-m-d H:i:s'),
            'status' => 1
        ];

        $employment->exchangeArray($data);

        $this->assertSame($data['inn'], $employment->inn);
        $this->assertSame($data['updated_at'], $employment->updated_at);
        $this->assertSame($data['status'], $employment->status);
    }

    public function testExchangeArrayNotChangingPropertiesThatNotPresent()
    {
        $employment = new Employment;
        $initialData = [
            'inn' => '1234567890',
            'updated_at' => date('Y-m-d H:i:s'),
            'status' => 1
        ];

        $employment->inn = $initialData['inn'];
        $employment->updated_at = $initialData['updated_at'];
        $employment->status = $initialData['status'];

        $employment->exchangeArray([]);

        $this->assertSame($initialData['inn'], $employment->inn);
        $this->assertSame($initialData['updated_at'], $employment->updated_at);
        $this->assertSame($initialData['status'], $employment->status);
    }

    public function testInputFilterInjectionThrowsDomainException()
    {
        $employment = new Employment;

        $this->expectException(DomainException::class);

        $employment->setInputFilter(new InputFilter);
    }

    public function testGetInputFilterReutrnsCorrectInstance()
    {
        $employment = new Employment;

        $this->assertInstanceOf(InputFilterInterface::class, $employment->getInputFilter());
    }

    public function testUpdateRequiredOnNewObject()
    {
        $employment = new Employment;

        $this->assertTrue($employment->updateRequired());
    }

    public function testUpdateRequiredOnOutdatedObject()
    {
        $employment = new Employment;

        $employment->updated_at = date('Y-m-d H:i:s', time() - (24 * 60 * 60) - 1);

        $this->assertTrue($employment->updateRequired());
    }

    public function testUpdateNotRequiredOnFreshObject()
    {
        $employment = new Employment;

        $employment->updated_at = date('Y-m-d H:i:s');

        $this->assertFalse($employment->updateRequired());
    }
}