<?php

namespace App\Handlers\UserProperties;

use App\Jobs\UserProperties\CreateUserProperty;
use App\Repositories\PropertiesRepository;

class CreateUserPropertyHandler
{
    /**
     * @var PropertiesRepository $property
     */
    protected PropertiesRepository $property;

    public function __construct(PropertiesRepository $property)
    {
        $this->property = $property;
    }

    /**
     * @param CreateUserProperty $command
     * @return mixed
     */
    public function handle(CreateUserProperty $command)
    {
        $request = $command->request;
        $property = $this->property->findBy('joining_code', $request->joining_code);

        return $property->users()->sync(auth()->user()->getKey());
    }
}
