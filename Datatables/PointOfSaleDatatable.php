<?php

namespace Modules\PointOfSale\Datatables;

use Utils;
use URL;
use Auth;
use App\Ninja\Datatables\EntityDatatable;

class PointOfSaleDatatable extends EntityDatatable
{
    public $entityType = 'pointofsale';
    public $sortCol = 1;

    public function columns()
    {
        return [
            
            [
                'created_at',
                function ($model) {
                    return Utils::fromSqlDateTime($model->created_at);
                }
            ],
        ];
    }

    public function actions()
    {
        return [
            [
                mtrans('pointofsale', 'edit_pointofsale'),
                function ($model) {
                    return URL::to("pointofsale/{$model->public_id}/edit");
                },
                function ($model) {
                    return Auth::user()->can('editByOwner', ['pointofsale', $model->user_id]);
                }
            ],
        ];
    }

}
