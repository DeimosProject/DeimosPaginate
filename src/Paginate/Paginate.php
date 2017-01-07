<?php

namespace Deimos\Paginate;

use Deimos\ORM\SelectQuery;

class Paginate extends Pager
{

    use ExtOrm;

    /**
     * reset pager
     */
    protected function reset()
    {
        parent::reset();
        $this->setSelectQuery(null);
    }

    /**
     * @param bool $asObject
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function currentItems($asObject = true)
    {
        if (!$this->loaded)
        {
            throw new \InvalidArgumentException('Data is not load');
        }

        if ($this->selectQuery === null)
        {
            return $this->slice();
        }

        return (clone $this->selectQuery)
            ->take($this->take)
            ->skip($this->skip)
            ->find($asObject);
    }

    /**
     * @param array $storage
     *
     * @return $this
     */
    public function arrayPager(array $storage)
    {
        $this->reset();
        $this->loaded  = true;
        $this->storage = $storage;

        return $this;
    }

    /**
     * @param SelectQuery $selectQuery
     *
     * @return $this
     */
    public function queryPager(SelectQuery $selectQuery)
    {
        $this->reset();
        $this->setSelectQuery($selectQuery);

        $this->loaded = true;

        return $this;
    }

    /**
     * @param SelectQuery $selectQuery
     *
     * @return $this
     */
    public function queryClone(SelectQuery $selectQuery)
    {
        return $this->queryPager(clone $selectQuery);
    }

    /**
     * @return int
     */
    protected function count()
    {
        return $this->selectQuery === null
            ? count($this->storage)
            : (clone $this->selectQuery)->count();
    }

    /**
     * @return int
     */
    public function itemCount()
    {
        if (!$this->itemCount)
        {
            $this->itemCount = $this->count();
        }

        return $this->itemCount;
    }

}