<?php

namespace Modules\PointOfSale\Http\Requests;

use App\Http\Requests\EntityRequest;

class PointOfSaleRequest extends EntityRequest
{
    protected $entityType = 'pointofsale';
}
