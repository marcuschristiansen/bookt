<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserProperty\UserPropertyCreate;
use App\Http\Resources\Property\PropertyCollection;
use App\Jobs\UserProperties\CreateUserProperty;
use App\Jobs\UserProperties\DeleteUserProperty;
use App\Models\UserProperty;
use App\Repositories\Criteria\isPropertyMemberOf;
use App\Repositories\Criteria\ModelFilter;
use App\Repositories\Criteria\RequestWith;
use App\Repositories\PropertiesRepository;
use App\Repositories\UserPropertyRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserPropertyController extends Controller
{
    /**
     * @var UserPropertyRepository $userProperty
     */
    private UserPropertyRepository $userProperty;

    /**
     * @var PropertiesRepository $property
     */
    private PropertiesRepository $property;

    /**
     * UserPropertyController constructor.
     *
     * @param PropertiesRepository $property
     * @param UserPropertyRepository $userProperty
     */
    public function __construct(PropertiesRepository $property, UserPropertyRepository $userProperty)
    {
        $this->property = $property;
        $this->userProperty = $userProperty;
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $userId
     * @return Response
     */
    public function index(int $userId): Response
    {
        $this->authorize('viewAny', [UserProperty::class, $userId]);

        $properties = $this->property
            ->pushCriteria(new isPropertyMemberOf($userId))
            ->pushCriteria(new RequestWith())
            ->pushCriteria(new ModelFilter())
            ->all();

        return Inertia::render('Users/Properties/Index', [
            'properties' => new PropertyCollection($properties)
        ]);
    }

    /**
     * Add user as a member to the property
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserPropertyCreate $request)
    {
        CreateUserProperty::dispatchNow($request);

        return redirect()->back();
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
     * @param int $userId
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(int $userId, int $id)
    {
        $userProperty = $this->property->findWhereOrFail(['user_id' => $userId, 'property_id' => $id]);
        $this->authorize('delete', $userProperty);

        DeleteUserProperty::dispatchNow($userProperty);

        return redirect()->back();
    }
}
