<?php

namespace App\Domain\BookCatalog\Search;

use App\Domain\BookInterface;

class BookSearchResults implements \Countable
{
    private $books = array();

    public function __construct(array $books)
    {
        foreach ($books as $book) {
            $this->assertIsBook($book);
        }

        $this->books = $books;
    }

    public static function fromArrayOfBooks(array $books)
    {
        return new self($books);
    }

    public function count()
    {
        return count($this->books);
    }

    public function isEmpty()
    {
        return 0 === $this->count();
    }

    public function books()
    {
        return $this->books;
    }

    private function assertIsBook($argument)
    {
        if (!$argument instanceof BookInterface) {
            throw new \InvalidArgumentException('Results can only contain instances of "App\Domain\BookInterface".');
        }
    }
}