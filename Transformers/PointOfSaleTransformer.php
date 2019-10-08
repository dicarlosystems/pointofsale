<?php

namespace Modules\PointOfSale\Transformers;

use Modules\Pointofsale\Models\Pointofsale;
use App\Ninja\Transformers\EntityTransformer;

/**
 * @SWG\Definition(definition="Pointofsale", @SWG\Xml(name="Pointofsale"))
 */

class PointofsaleTransformer extends EntityTransformer
{
    /**
    * @SWG\Property(property="id", type="integer", example=1, readOnly=true)
    * @SWG\Property(property="user_id", type="integer", example=1)
    * @SWG\Property(property="account_key", type="string", example="123456")
    * @SWG\Property(property="updated_at", type="integer", example=1451160233, readOnly=true)
    * @SWG\Property(property="archived_at", type="integer", example=1451160233, readOnly=true)
    */

    /**
     * @param Pointofsale $pointofsale
     * @return array
     */
    public function transform(Pointofsale $pointofsale)
    {
        return array_merge($this->getDefaults($pointofsale), [
            
            'id' => (int) $pointofsale->public_id,
            'updated_at' => $this->getTimestamp($pointofsale->updated_at),
            'archived_at' => $this->getTimestamp($pointofsale->deleted_at),
        ]);
    }
}
