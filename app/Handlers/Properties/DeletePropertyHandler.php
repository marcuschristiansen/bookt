<?php

namespace App\Handlers\Properties;

use App\Jobs\Properties\DeleteProperty;
use App\Repositories\PropertiesRepository;

class DeletePropertyHandler
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
     * @param DeleteProperty $command
     * @return mixed
     */
    public function handle(DeleteProperty $command)
    {
        $property = $command->property;

        return $this->property->delete($property->getKey());
    }
}
