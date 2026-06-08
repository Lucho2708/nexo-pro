<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Modules\IAM\Providers\IAMServiceProvider::class,
    App\Modules\Asamblea\Providers\AsambleaServiceProvider::class,
    App\Modules\Property\Providers\PropertyServiceProvider::class,
    App\Modules\Finance\Providers\FinanceServiceProvider::class,
    App\Modules\Operations\Providers\OperationsServiceProvider::class,
];
