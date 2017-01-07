<?php

namespace Deimos\Paginate;

abstract class Pager
{

    /**
     * @var int
     */
    protected $defaultTake = 15;

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

    /**
     * reset pager
     */
    protected function reset()
    {
        $this->loaded    = false;
        $this->storage   = null;
        $this->itemCount = null;
        $this->take      = $this->defaultTake;
        $this->skip      = null;
    }

    /**
     * @return array
     */
    protected function slice()
    {
        return array_slice($this->storage, $this->skip, $this->take);
    }

    /**
     * @return int
     */
    public abstract function itemCount();

    /**
     * @return bool
     */
    protected function isLoaded()
    {
        return !!$this->loaded;
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
     * @return static
     */
    public function page($page)
    {
        return $this->skip($this->pageCount() * ($page - 1));
    }

    /**
     * @param int $limit
     *
     * @return static
     */
    public function take($limit)
    {
        $this->take = $limit;

        return $this;
    }

    /**
     * @param int $limit
     *
     * @return static
     */
    public function limit($limit)
    {
        return $this->take($limit);
    }

    /**
     * @param int $offset
     *
     * @return static
     */
    public function skip($offset)
    {
        $this->skip = $offset;

        return $this;
    }

    /**
     * @param int $offset
     *
     * @return static
     */
    public function offset($offset)
    {
        return $this->skip($offset);
    }

}