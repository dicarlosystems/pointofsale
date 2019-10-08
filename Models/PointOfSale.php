<?php

namespace Modules\PointOfSale\Models;

use App\Models\EntityModel;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class PointOfSale extends EntityModel
{
    use PresentableTrait;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $presenter = 'Modules\PointOfSale\Presenters\PointOfSalePresenter';

    /**
     * @var string
     */
    protected $fillable = [""];

    /**
     * @var string
     */
    protected $table = 'pointofsale';

    public function getEntityType()
    {
        return 'pointofsale';
    }

}
