<?php

namespace App\Domain\BookCatalog;

use App\Domain\BookInterface;
use App\Domain\Isbn;

interface BookCatalogInterface
{
    public function add(BookInterface $book);
    public function remove(Isbn $isbn);
    public function hasBookWithIsbn(Isbn $isbn);
}