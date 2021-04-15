<?php

namespace App\Handlers\Properties;

use App\Jobs\Properties\UpdateProperty;
use App\Repositories\PropertiesRepository;

class UpdatePropertyHandler
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
     * @param UpdateProperty $command
     * @return mixed
     */
    public function handle(UpdateProperty $command)
    {
        $request = $command->request;
        $property = $command->property;

        $this->property->updateRich($request->validated(), $property->getKey());

        return $this->property->find($property->getKey());
    }
}
