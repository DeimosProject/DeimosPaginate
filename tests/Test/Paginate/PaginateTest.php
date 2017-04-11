<?php

namespace Test\Paginate;

class PaginateTest extends \PHPUnit\Framework\TestCase
{

    protected $limit = 20;
    protected $page = 20;
    protected $start = 1;
    protected $count = 1000;

    /**
     * @var \Deimos\Paginate\Paginate
     */
    protected $simple;

    protected function getRangeHigh($start, $count)
    {
        return $start + $count - 1;
    }

    public function testOffset()
    {
        $simple = (new \Deimos\Paginate\Paginate())
            ->arrayPager(range($this->start, $this->getRangeHigh($this->start, $this->count)))
            ->limit($this->limit);
        self::assertEquals(
            $simple->currentPage(),
            1
        );
    }

    public function testArray()
    {
        $simple = (new \Deimos\Paginate\Paginate())
            ->arrayPager(range($this->start, $this->getRangeHigh($this->start, $this->count)))
            ->limit($this->limit)
            ->page($this->page);

        self::assertEquals(
            $simple->itemCount(),
            $this->count
        );
        self::assertEquals(
            $simple->pageCount(),
            ceil($this->count / $this->limit)
        );
        self::assertEquals(
            count($simple->currentItems(false)),
            20
        );

        $items = $simple->currentItems();
        self::assertEquals(
            count($items),
            20
        );

        self::assertEquals(
            $items[0],
            ($this->page - 1) * $this->limit + $this->start
        );

        self::assertTrue($simple->pageExists(10));
        self::assertEquals(
            $simple->currentPage(),
            $this->page
        );
    }

    public function testQuery()
    {

        $helper = new \Deimos\Helper\Helper();

        $db  = new \Deimos\Database\Database(new \Deimos\Slice\Slice($helper, [
            'adapter'  => 'sqlite',
            'path' => ':memory:',
        ]));

        $db->exec('CREATE TABLE IF NOT EXISTS files (id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,name TEXT NOT NULL)');

        $orm = new \Deimos\ORM\ORM($helper, $db);

        foreach (range($this->start, $this->getRangeHigh($this->start, $this->count)) as $item)
        {
            $orm->create('file', [
                'name' => $item
            ]);
        }

        $fileQuery = $orm->repository('file');

        $simple = (new \Deimos\Paginate\Paginate())
            ->queryPager($fileQuery)
            ->limit($this->limit)
            ->page($this->page);

        self::assertEquals(
            $simple->itemCount(),
            $this->count
        );
        self::assertEquals(
            $simple->pageCount(),
            ceil($this->count / $this->limit)
        );
        self::assertEquals(
            count($simple->currentItems(false)),
            20
        );

        $items = $simple->currentItems();
        self::assertEquals(
            count($items),
            20
        );

        self::assertEquals(
            $items[0]->name,
            ($this->page - 1) * $this->limit + $this->start
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testError()
    {
        (new \Deimos\Paginate\Paginate())->currentItems();
    }

}
