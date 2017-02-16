<?php

namespace Deimos\Paginate;

use Deimos\ORM\Queries\Query;

class Paginate extends Pager
{

    /**
     * @var Query
     */
    protected $query;

    /**
     * @param Query $query
     */
    protected function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return Query
     */
    protected function query()
    {
        return clone $this->query;
    }

    /**
     * reset pager
     */
    protected function reset()
    {
        parent::reset();
        $this->setQuery(null);
    }

    /**
     * @param bool $asObject
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function currentItems($asObject = true)
    {
        if (!$this->isLoaded())
        {
            throw new \InvalidArgumentException('Data is not load');
        }

        if ($this->query === null)
        {
            return $this->slice();
        }

        return $this->query()
            ->limit($this->limit)
            ->offset($this->offset())
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
     * @param Query $query
     *
     * @return $this
     */
    public function queryPager(Query $query)
    {
        $this->reset();
        $this->setQuery($query);

        $this->loaded = true;

        return $this;
    }

    /**
     * @param Query $query
     *
     * @return $this
     */
    protected function queryClone(Query $query)
    {
        return $this->queryPager(clone $query);
    }

    /**
     * @return int
     */
    protected function count()
    {
        if ($this->query)
        {
            return $this->query()->count();
        }

        return count($this->storage);
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