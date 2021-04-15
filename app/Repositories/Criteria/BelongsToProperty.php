<?php
namespace App\Repositories\Criteria;

use App\Models\Property;
use App\Repositories\Contracts\RepositoryInterface as Repository;
use Illuminate\Database\Eloquent\Builder;

class BelongsToProperty extends Criteria
{
    /**
     * @var Property $property
     */
    public $property;

    /**
     * ModelFilter constructor.
     *
     * @param Property $property
     */
    public function __construct(Property $property)
    {
        $this->property = $property;
    }

    /**
     * @param $model
     * @param Repository $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        return $model->whereProperty($this->property);
    }
}
