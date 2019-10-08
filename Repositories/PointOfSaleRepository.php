<?php

namespace Modules\PointOfSale\Repositories;

use DB;
use Modules\Pointofsale\Models\Pointofsale;
use App\Ninja\Repositories\BaseRepository;
//use App\Events\PointofsaleWasCreated;
//use App\Events\PointofsaleWasUpdated;

class PointofsaleRepository extends BaseRepository
{
    public function getClassName()
    {
        return 'Modules\Pointofsale\Models\Pointofsale';
    }

    public function all()
    {
        return Pointofsale::scope()
                ->orderBy('created_at', 'desc')
                ->withTrashed();
    }

    public function find($filter = null, $userId = false)
    {
        $query = DB::table('pointofsale')
                    ->where('pointofsale.account_id', '=', \Auth::user()->account_id)
                    ->select(
                        
                        'pointofsale.public_id',
                        'pointofsale.deleted_at',
                        'pointofsale.created_at',
                        'pointofsale.is_deleted',
                        'pointofsale.user_id'
                    );

        $this->applyFilters($query, 'pointofsale');

        if ($userId) {
            $query->where('clients.user_id', '=', $userId);
        }

        /*
        if ($filter) {
            $query->where();
        }
        */

        return $query;
    }

    public function save($data, $pointofsale = null)
    {
        $entity = $pointofsale ?: Pointofsale::createNew();

        $entity->fill($data);
        $entity->save();

        /*
        if (!$publicId || intval($publicId) < 0) {
            event(new ClientWasCreated($client));
        } else {
            event(new ClientWasUpdated($client));
        }
        */

        return $entity;
    }

}
