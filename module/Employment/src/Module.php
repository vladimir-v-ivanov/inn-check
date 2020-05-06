<?php

namespace Employment;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\Http;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getAPIConfig()
    {
        return include __DIR__ . '/../config/api.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\EmploymentTable::class => function($container) {
                    return new Model\EmploymentTable($container->get(Model\EmploymentTableGateway::class));
                },
                Model\EmploymentTableGateway::class => function($container) {
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Employment());
                    return new TableGateway(
                        'inn_validation',
                        $container->get(AdapterInterface::class),
                        null,
                        $resultSetPrototype
                    );
                },
                Model\APIClient::class => function($container) {
                    return new Model\APIClient($container->get(Http\Client::class));
                },
                Http\Client::class => function($container) {
                    $config = $this->getAPIConfig();

                    $client = new Http\Client;
                    $client->setUri($config['api_uri']);
                    $client->setMethod('POST');
                    $client->setHeaders([
                        'Content-Type' => 'application/json'
                    ]);
                    $client->setOptions([
                        'timeout' => $config['request_timeout']
                    ]);

                    return $client;
                }
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\EmploymentController::class => function($container) {
                    return new Controller\EmploymentController(
                        $container->get(Model\EmploymentTable::class),
                        $container->get(Model\APIClient::class)
                    );
                },
            ],
        ];
    }
}