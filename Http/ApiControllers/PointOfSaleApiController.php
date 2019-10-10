<?php

namespace Modules\PointOfSale\Http\ApiControllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseAPIController;
use Modules\Pointofsale\Http\Requests\PointofsaleRequest;
use Modules\Pointofsale\Repositories\PointofsaleRepository;
use Modules\Pointofsale\Http\Requests\CreatePointofsaleRequest;
use Modules\Pointofsale\Http\Requests\UpdatePointofsaleRequest;

class PointofsaleApiController extends BaseAPIController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @SWG\Get(
     *   path="/pointofsale",
     *   summary="Get products by barcode",
     *   operationId="getProductByBarcode",
     *   tags={"pointofsale"},
     *   @SWG\Parameter(
     *     in="path",
     *     name="barcode",
     *     type="string",
     *     required=true
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="A list of products with the barcode",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Product"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function productsByBarcode(Request $request)
    {
        $products = Product::whereHas('manufacturerProductDetails', function($query) use($request) {
            $query->where('upc', '=', $request->get('barcode'));
        })->get();

        return $this->response($products);
    }
}
