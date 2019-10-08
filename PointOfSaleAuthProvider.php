<?php

namespace Modules\PointOfSale\;

use App\Providers\AuthServiceProvider;

class PointofsaleAuthProvider extends AuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Pointofsale\Models\Pointofsale::class => \Modules\Pointofsale\Policies\PointofsalePolicy::class,
    ];
}
