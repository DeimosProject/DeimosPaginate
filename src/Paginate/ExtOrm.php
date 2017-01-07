<?php

namespace Deimos\Paginate;

use Deimos\ORM\SelectQuery;

trait ExtOrm
{

    /**
     * @var SelectQuery
     */
    protected $selectQuery;

    /**
     * @param SelectQuery $query
     */
    protected function setSelectQuery(SelectQuery $query)
    {
        $this->selectQuery = $query;
    }

}