<?php

namespace Employment;

use Laminas\Router\Http\{
    Segment,
    Literal
};

return [
    'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],
    ],
    'router' => [
        'routes' => [
            'inn_check' => [
                'type'    => Literal::class,
                'options' => [
                    'route' => '/inn/check',
                    'defaults' => [
                        'controller' => Controller\EmploymentController::class,
                        'action'     => 'check',
                    ],
                ],
            ],
            'inn_result' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/inn/result/[:inn]',
                    'defaults' => [
                        'controller' => Controller\EmploymentController::class,
                        'action' => 'result'
                    ]
                ],
                'constraints' => [
                    'inn' => '[0-9]+',
                ],
            ],
            'inn_error' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/inn/error',
                    'defaults' => [
                        'controller' => Controller\EmploymentController::class,
                        'action' => 'error'
                    ]
                ]
            ]
        ]
    ]
];