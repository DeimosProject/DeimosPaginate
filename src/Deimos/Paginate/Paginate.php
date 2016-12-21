<?php

namespace Deimos\Paginate;

use Deimos\ORM\SelectQuery;

class Paginate
{

    /**
     * @var int
     */
    protected $defaultTake = 15;

    /**
     * @var SelectQuery
     */
    protected $selectQuery;

    /**
     * @var array
     */
    protected $storage;

    /**
     * @var int
     */
    protected $itemCount;

    /**
     * @var int
     */
    protected $take;

    /**
     * @var int
     */
    protected $skip;

    /**
     * @var bool
     */
    protected $loaded;

    /**
     * reset pager
     */
    protected function reset()
    {
        $this->loaded      = false;
        $this->selectQuery = null;
        $this->storage     = null;
        $this->itemCount   = null;
        $this->take        = $this->defaultTake;
        $this->skip        = null;
    }

    /**
     * @param bool $asArray
     *
     * @return array
     * @throws \Exception
     */
    public function currentItems($asArray = false)
    {
        if (!$this->loaded)
        {
            throw new \Exception('Data is not load');
        }

        if ($this->selectQuery)
        {
            $storage = $this->selectQuery
                ->take($this->take)
                ->skip($this->skip)
                ->find($asArray);
        }
        else
        {
            $storage = array_slice($this->storage, $this->skip, $this->take);
        }

        return $storage;
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
        $this->loaded      = true;
        $this->selectQuery = $selectQuery;

        return $this;
    }

    /**
     * @return int
     */
    public function itemCount()
    {
        if (!$this->itemCount)
        {
            if ($this->selectQuery)
            {
                $this->itemCount = $this->selectQuery->count();
            }
            else
            {
                $this->itemCount = count($this->storage);
            }
        }

        return $this->itemCount;
    }

    /**
     * @return int
     */
    public function pageCount()
    {
        return (int)ceil($this->itemCount() / $this->take);
    }

    /**
     * @param int $page
     *
     * @return bool
     */
    public function pageExists($page)
    {
        return $page > 0 && $this->pageCount() >= $page;
    }

    /**
     * @return int
     */
    public function currentPage()
    {
        return (int)($this->skip / $this->take + 1);
    }

    /**
     * @param int $page
     *
     * @return Paginate
     */
    public function page($page)
    {
        return $this->skip($this->pageCount() * ($page - 1));
    }

    /**
     * @param int $limit
     *
     * @return $this
     */
    public function take($limit)
    {
        $this->take = $limit;

        return $this;
    }

    /**
     * @param int $limit
     *
     * @return Paginate
     */
    public function limit($limit)
    {
        return $this->take($limit);
    }

    /**
     * @param int $offset
     *
     * @return $this
     */
    public function skip($offset)
    {
        $this->skip = $offset;

        return $this;
    }

    /**
     * @param int $offset
     *
     * @return Paginate
     */
    public function offset($offset)
    {
        return $this->skip($offset);
    }

}