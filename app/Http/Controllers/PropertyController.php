<?php

namespace App\Http\Controllers;

use App\Http\Requests\Property\PropertyCreate;
use App\Http\Requests\Property\PropertyUpdate;
use App\Http\Resources\Property\PropertyCollection;
use App\Http\Resources\Property\PropertyResource;
use App\Jobs\Properties\CreateProperty;
use App\Jobs\Properties\DeleteProperty;
use App\Jobs\Properties\UpdateProperty;
use App\Models\Property;
use App\Repositories\Criteria\BelongsToTeam;
use App\Repositories\Criteria\ModelFilter;
use App\Repositories\Criteria\RequestWith;
use App\Repositories\PropertiesRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class PropertyController extends Controller
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
        $this->authorize('viewAny', Property::class);

        $properties = $this->property
            ->pushCriteria(new BelongsToTeam())
            ->pushCriteria(new RequestWith())
            ->pushCriteria(new ModelFilter())
            ->all();

        return Inertia::render('Properties/Index', [
            'properties' => new PropertyCollection($properties)
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Properties/Create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id): Response
    {
        $property = $this->property->pushCriteria(new RequestWith())->with(['calendars', 'users'])->find($id);
        $this->authorize('view', $property);

        return Inertia::render('Properties/Show', [
            'property' => new PropertyResource($property)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($id)
    {
        $property = $this->property->pushCriteria(new RequestWith())->find($id);
        $this->authorize('update', $property);

        return Inertia::render('Properties/Edit', [
            'property' => new PropertyResource($property)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PropertyCreate $request
     * @return RedirectResponse
     */
    public function store(PropertyCreate $request): RedirectResponse
    {
        $this->authorize('create', Property::class);

        CreateProperty::dispatchNow($request);

        return Redirect::route('properties.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PropertyUpdate $request
     * @param int $id
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(PropertyUpdate $request, $id)
    {
        $property = $this->property->findOrFail($id);
        $this->authorize('update', $property);

        UpdateProperty::dispatchNow($request, $property);

        return Redirect::route('properties.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id)
    {
        $property = $this->property->findOrFail($id);
        $this->authorize('delete', $property);

        DeleteProperty::dispatchNow($property);

        return redirect()->back();
    }
}
