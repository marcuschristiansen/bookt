<?php

namespace App\Handlers\Properties;

use App\Events\SlotCreated;
use App\Jobs\Properties\CreateProperty;
use App\Jobs\Slots\CreateSlot;
use App\Models\Property;
use App\Models\Slot;
use App\Repositories\PropertiesRepository;
use App\Repositories\SlotsRepository;

class CreatePropertyHandler
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
     * @param CreateProperty $command
     * @return mixed
     */
    public function handle(CreateProperty $command)
    {
        $request = $command->request;
        $fields = array_merge($request->all(), [
            'team_id' => auth()->user()->currentTeam->getKey(),
        ]);

        return $this->property->create($fields);
    }
}
