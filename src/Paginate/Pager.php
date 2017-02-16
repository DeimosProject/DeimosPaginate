<?php

namespace Deimos\Paginate;

abstract class Pager
{

    /**
     * @var int
     */
    protected $defaultTake = 50;

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
    protected $limit;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var bool
     */
    protected $loaded;

    /**
     * reset pager
     */
    protected function reset()
    {
        $this->loaded    = false;
        $this->storage   = null;
        $this->itemCount = null;
        $this->limit = $this->defaultTake;
        $this->page = 1;
    }

    /**
     * @return array
     */
    protected function slice()
    {
        return array_slice($this->storage, $this->offset(), $this->limit);
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
        return $this->loaded === true;
    }

    /**
     * @return int
     */
    public function pageCount()
    {
        return (int)ceil($this->itemCount() / $this->limit);
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
        if (!$this->offset())
        {
            return 1;
        }

        return (int)($this->offset() / $this->limit + 1);
    }

    /**
     * @param int $page
     *
     * @return static
     */
    public function page($page)
    {
        $this->page = abs($page);

        return $this;
    }

    /**
     * @param int $limit
     *
     * @return static
     */
    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return int
     */
    protected function offset()
    {
        return ($this->page - 1) * $this->limit;
    }

}