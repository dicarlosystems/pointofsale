<?php

namespace Modules\PointOfSale\Http\ApiControllers;

use App\Http\Controllers\BaseAPIController;
use Modules\Pointofsale\Repositories\PointofsaleRepository;
use Modules\Pointofsale\Http\Requests\PointofsaleRequest;
use Modules\Pointofsale\Http\Requests\CreatePointofsaleRequest;
use Modules\Pointofsale\Http\Requests\UpdatePointofsaleRequest;

class PointofsaleApiController extends BaseAPIController
{
    protected $PointofsaleRepo;
    protected $entityType = 'pointofsale';

    public function __construct(PointofsaleRepository $pointofsaleRepo)
    {
        parent::__construct();

        $this->pointofsaleRepo = $pointofsaleRepo;
    }

    /**
     * @SWG\Get(
     *   path="/pointofsale",
     *   summary="List pointofsale",
     *   operationId="listPointofsales",
     *   tags={"pointofsale"},
     *   @SWG\Response(
     *     response=200,
     *     description="A list of pointofsale",
     *      @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Pointofsale"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function index()
    {
        $data = $this->pointofsaleRepo->all();

        return $this->listResponse($data);
    }

    /**
     * @SWG\Get(
     *   path="/pointofsale/{pointofsale_id}",
     *   summary="Individual Pointofsale",
     *   operationId="getPointofsale",
     *   tags={"pointofsale"},
     *   @SWG\Parameter(
     *     in="path",
     *     name="pointofsale_id",
     *     type="integer",
     *     required=true
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="A single pointofsale",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Pointofsale"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function show(PointofsaleRequest $request)
    {
        return $this->itemResponse($request->entity());
    }




    /**
     * @SWG\Post(
     *   path="/pointofsale",
     *   summary="Create a pointofsale",
     *   operationId="createPointofsale",
     *   tags={"pointofsale"},
     *   @SWG\Parameter(
     *     in="body",
     *     name="pointofsale",
     *     @SWG\Schema(ref="#/definitions/Pointofsale")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="New pointofsale",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Pointofsale"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function store(CreatePointofsaleRequest $request)
    {
        $pointofsale = $this->pointofsaleRepo->save($request->input());

        return $this->itemResponse($pointofsale);
    }

    /**
     * @SWG\Put(
     *   path="/pointofsale/{pointofsale_id}",
     *   summary="Update a pointofsale",
     *   operationId="updatePointofsale",
     *   tags={"pointofsale"},
     *   @SWG\Parameter(
     *     in="path",
     *     name="pointofsale_id",
     *     type="integer",
     *     required=true
     *   ),
     *   @SWG\Parameter(
     *     in="body",
     *     name="pointofsale",
     *     @SWG\Schema(ref="#/definitions/Pointofsale")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Updated pointofsale",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Pointofsale"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function update(UpdatePointofsaleRequest $request, $publicId)
    {
        if ($request->action) {
            return $this->handleAction($request);
        }

        $pointofsale = $this->pointofsaleRepo->save($request->input(), $request->entity());

        return $this->itemResponse($pointofsale);
    }


    /**
     * @SWG\Delete(
     *   path="/pointofsale/{pointofsale_id}",
     *   summary="Delete a pointofsale",
     *   operationId="deletePointofsale",
     *   tags={"pointofsale"},
     *   @SWG\Parameter(
     *     in="path",
     *     name="pointofsale_id",
     *     type="integer",
     *     required=true
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Deleted pointofsale",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Pointofsale"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function destroy(UpdatePointofsaleRequest $request)
    {
        $pointofsale = $request->entity();

        $this->pointofsaleRepo->delete($pointofsale);

        return $this->itemResponse($pointofsale);
    }

}
