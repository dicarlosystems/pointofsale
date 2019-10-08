<?php

namespace Modules\PointOfSale\Http\Controllers;

use Auth;
use App\Http\Controllers\BaseController;
use App\Services\DatatableService;
use Modules\PointOfSale\Datatables\PointOfSaleDatatable;
use Modules\PointOfSale\Repositories\PointOfSaleRepository;
use Modules\PointOfSale\Http\Requests\PointOfSaleRequest;
use Modules\PointOfSale\Http\Requests\CreatePointOfSaleRequest;
use Modules\PointOfSale\Http\Requests\UpdatePointOfSaleRequest;

class PointOfSaleController extends BaseController
{
    protected $PointOfSaleRepo;
    //protected $entityType = 'pointofsale';

    public function __construct(PointOfSaleRepository $pointofsaleRepo)
    {
        //parent::__construct();

        $this->pointofsaleRepo = $pointofsaleRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('list_wrapper', [
            'entityType' => 'pointofsale',
            'datatable' => new PointOfSaleDatatable(),
            'title' => mtrans('pointofsale', 'pointofsale_list'),
        ]);
    }

    public function datatable(DatatableService $datatableService)
    {
        $search = request()->input('sSearch');
        $userId = Auth::user()->filterId();

        $datatable = new PointOfSaleDatatable();
        $query = $this->pointofsaleRepo->find($search, $userId);

        return $datatableService->createDatatable($datatable, $query);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(PointOfSaleRequest $request)
    {
        $data = [
            'pointofsale' => null,
            'method' => 'POST',
            'url' => 'pointofsale',
            'title' => mtrans('pointofsale', 'new_pointofsale'),
        ];

        return view('pointofsale::edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreatePointOfSaleRequest $request)
    {
        $pointofsale = $this->pointofsaleRepo->save($request->input());

        return redirect()->to($pointofsale->present()->editUrl)
            ->with('message', mtrans('pointofsale', 'created_pointofsale'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(PointOfSaleRequest $request)
    {
        $pointofsale = $request->entity();

        $data = [
            'pointofsale' => $pointofsale,
            'method' => 'PUT',
            'url' => 'pointofsale/' . $pointofsale->public_id,
            'title' => mtrans('pointofsale', 'edit_pointofsale'),
        ];

        return view('pointofsale::edit', $data);
    }

    /**
     * Show the form for editing a resource.
     * @return Response
     */
    public function show(PointOfSaleRequest $request)
    {
        return redirect()->to("pointofsale/{$request->pointofsale}/edit");
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdatePointOfSaleRequest $request)
    {
        $pointofsale = $this->pointofsaleRepo->save($request->input(), $request->entity());

        return redirect()->to($pointofsale->present()->editUrl)
            ->with('message', mtrans('pointofsale', 'updated_pointofsale'));
    }

    /**
     * Update multiple resources
     */
    public function bulk()
    {
        $action = request()->input('action');
        $ids = request()->input('public_id') ?: request()->input('ids');
        $count = $this->pointofsaleRepo->bulk($ids, $action);

        return redirect()->to('pointofsale')
            ->with('message', mtrans('pointofsale', $action . '_pointofsale_complete'));
    }
}
