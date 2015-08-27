<?php

namespace App\Domain\BookCatalog;

use App\Domain\BookInterface;
use App\Domain\Isbn;

class InMemoryBookCatalog implements BookCatalogInterface
{
    private $books = array();

    public function add(BookInterface $book)
    {
        $this->books[$book->isbn()->asString()] = $book;
    }

    public function remove(Isbn $isbn)
    {
        if (!$this->hasBookWithIsbn($isbn)) {
            throw new \InvalidArgumentException(sprintf('Book with ISBN "%s" does not exist!', $isbn->asString()));
        }
        unset($this->books[$isbn->asString()]);
    }

    public function hasBookWithIsbn(Isbn $isbn)
    {
        return array_key_exists($isbn->asString(), $this->books);
    }
}