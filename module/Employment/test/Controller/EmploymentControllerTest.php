<?php

namespace EmploymentTest\Controller;

use Employment\Controller\EmploymentController;
use Employment\Model\Employment;
use Employment\Model\EmploymentTable;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Argument;

class EmploymentControllerTest extends AbstractHttpControllerTestCase
{
    use ProphecyTrait;

    public function setUp(): void
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();

        $this->configureServiceManager($this->getApplicationServiceLocator());
    }

    public function testCheckActionCanBeAccessed()
    {
        $this->dispatch('/inn/check');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('employment');
        $this->assertControllerName(EmploymentController::class);
        $this->assertControllerClass('EmploymentController');
        $this->assertMatchedRouteName('inn_check');
    }

    public function testResultActionWillRedirectWithMockedDb()
    {
        $this->dispatch('/inn/result/123');
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('employment');
        $this->assertMatchedRouteName('inn_result');
        $this->assertRedirectTo('/inn/check');
        $this->employmentTable
            ->getItem(Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(null);
    }

    protected function configureServiceManager(ServiceManager $services)
    {
        $services->setAllowOverride(true);
        $services->setService('config', $this->updateConfig($services->get('config')));
        $services->setService(EmploymentTable::class, $this->mockEmploymentTable()->reveal());
    }

    protected function updateConfig(array $config): array
    {
        $config['db'] = [];

        return $config;
    }

    protected function mockEmploymentTable()
    {
        $this->employmentTable = $this->prophesize(EmploymentTable::class);

        return $this->employmentTable;
    }
}