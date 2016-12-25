<?php

use TheDava\View\Helper\TitleViewHelper;

return [
    'meta' => [
        'titles' => [
            TitleViewHelper::KEY_DEFAULT                               => [
                TitleViewHelper::KEY_DEFAULT => 'DefaultTitle',
            ],
            \TheDavaTest\Mock\Controller\PHPUnitController::class      => [
                'index'                      => 'PHPUnitTitle',
                'test'                       => 'PHPUnitTestTitle',
                TitleViewHelper::KEY_DEFAULT => 'PHPUnitDefaultTitle',
            ],
            \TheDavaTest\Mock\Controller\PHPUnitErrorController::class => [
                'index' => 'PHPUnitErrorIndexTitle',
            ],
        ],
    ],
];
