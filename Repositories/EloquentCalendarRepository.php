<?php

namespace Modules\Calendar\Repositories;

use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

/**
 * Class EloquentCalendarRepository.
 */
abstract class EloquentCalendarRepository extends EloquentBaseRepository
{
    /**
     * Find data by multiple that is between two values
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     * @return mixed
     */
    public function findWhereBetween(array $whereBetween, $columns = array('*'))
    {
        $this->applyCriteria();

        foreach ($whereBetween as $field => $value) {
            list($field, $val) = $value;
            $this->model = $this->model->orWhereBetween($field, $val);
        }

        $model = $this->model->get($columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Find data by multiple that is not between two values
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     * @return mixed
     */
    public function findWhereNotBetween(array $whereBetween, $columns = array('*'))
    {
        $this->applyCriteria();

        foreach ($whereBetween as $field => $value) {
            list($field, $val) = $value;
            $this->model = $this->model->orWhereNotBetween($field, $val);
        }

        $model = $this->model->get($columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

}
