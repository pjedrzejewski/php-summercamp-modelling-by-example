<?php

namespace App\Domain;

use Doctrine\ORM\Mapping as ORM;

class Book implements BookInterface
{
    private $title;
    private $isbn;

    public function __construct(BookTitle $title, Isbn $isbn)
    {
        $this->title = $title->asString();
        $this->isbn = $isbn->asString();
    }

    public static function withTitleAndIsbn(BookTitle $title, Isbn $isbn)
    {
        return new self($title, $isbn);
    }

    public function title()
    {
        return new BookTitle($this->title);
    }

    public function isbn()
    {
        return new Isbn($this->isbn);
    }
}
