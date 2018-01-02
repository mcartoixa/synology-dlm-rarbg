<?php
final class FakeResult
{
    public function __construct()
    { }

    public $title;
    public $download;
    public $size;
    public $datetime;
    public $page;
    public $hash;
    public $seeds;
    public $leechs;
    public $category;
}

final class FakePlugin
{
    public function __construct()
    { }

    public function addResult($title, $download, $size, $datetime, $page, $hash, $seeds, $leechs, $category)
    {
        $result = new FakeResult;
        $result->title = $title;
        $result->download = $download;
        $result->size = $size;
        $result->datetime = $datetime;
        $result->page = $page;
        $result->hash = $hash;
        $result->seeds = $seeds;
        $result->leechs = $leechs;
        $result->category = $category;
        array_push($this->results, $result);
    }

    public $results = array();
}
?>