<?php

return [
    'directories' => [
        'Migrations',
        'Models',
        'Policies',
        'Providers',
        'Controllers',
        'DTOs',
        'Requests',
        'Services',
        'Helpers',
        'Contracts/Repositories',
        'Repositories',
        'Routes/Api',
        'Tests/Feature',
        'Tests/Unit',
    ],
    'stubs' =>[
        'Models' => 'model.stub',
        'Policies' => 'policy.stub',
        'Providers' => 'serviceprovider.stub',
        'Controllers' => 'controller.stub',
        'DTOs' => 'dto.stub',
        'Services' => 'service.stub',
        'Contracts/Repositories' => 'repositoryinterface.stub',
        'Repositories' => 'repository.stub',
    ]
];
