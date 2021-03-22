<?php

namespace App\Handlers\Passes;

use App\Models\Pass;
use App\Repositories\PassRepository;

class CreatePassHandler
{
    /**
     * @var Pass $pass
     */
    protected $pass;

    public function __construct(PassRepository $pass)
    {
        $this->pass = $pass;
    }

    /**
     *
     */
    public function handle()
    {

    }
}
