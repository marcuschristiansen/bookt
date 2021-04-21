<?php

namespace App\Handlers\UserProperties;

use App\Jobs\Properties\DeleteProperty;
use App\Jobs\UserProperties\DeleteUserProperty;
use App\Repositories\PropertiesRepository;
use App\Repositories\UserPropertyRepository;

class DeleteUserPropertyHandler
{
    /**
     * @var UserPropertyRepository $userProperty
     */
    protected UserPropertyRepository $userProperty;

    public function __construct(UserPropertyRepository $userProperty)
    {
        $this->userProperty = $userProperty;
    }

    /**
     * @param DeleteUserProperty $command
     * @return mixed
     */
    public function handle(DeleteUserProperty $command)
    {
        $userProperty = $command->userProperty;

        return $this->userProperty->delete($userProperty->getKey());
    }
}
