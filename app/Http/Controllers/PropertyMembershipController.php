<?php

namespace App\Http\Controllers;

use App\Http\Resources\Property\PropertyCollection;
use App\Repositories\Criteria\isPropertyMemberOf;
use App\Repositories\Criteria\ModelFilter;
use App\Repositories\Criteria\RequestWith;
use App\Repositories\PropertiesRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PropertyMembershipController extends Controller
{
    /**
     * @var PropertiesRepository $property
     */
    private PropertiesRepository $property;

    public function __construct(PropertiesRepository $property)
    {
        $this->property = $property;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        $properties = $this->property
            ->pushCriteria(new isPropertyMemberOf())
            ->pushCriteria(new RequestWith())
            ->pushCriteria(new ModelFilter())
            ->all();

        return Inertia::render('Properties/Index', [
            'properties' => new PropertyCollection($properties)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
