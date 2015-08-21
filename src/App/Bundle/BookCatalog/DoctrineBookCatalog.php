<?php

namespace App\Bundle\BookCatalog;

use App\Domain\BookCatalog\BookCatalogInterface;
use App\Domain\BookCatalog\Search\BookSearchResults;
use App\Domain\BookInterface;
use App\Domain\Isbn;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

class DoctrineBookCatalog implements BookCatalogInterface
{
    private $doctrineManager;
    private $doctrineRepository;

    public function __construct(ObjectManager $doctrineManager, ObjectRepository $doctrineRepository)
    {
        $this->doctrineManager = $doctrineManager;
        $this->doctrineRepository = $doctrineRepository;
    }

    public function add(BookInterface $book)
    {
        $isbn = $book->isbn();

        if ($this->hasBookWithIsbn($isbn)) {
            throw new \InvalidArgumentException(sprintf('Book with ISBN "%s" already exists!', $isbn->asString()));
        }

        $this->doctrineManager->persist($book);
        $this->doctrineManager->flush();
    }

    public function remove(Isbn $isbn)
    {
        if (!$this->hasBookWithIsbn($isbn)) {
            throw new \InvalidArgumentException(sprintf('Book with ISBN "%s" does not exist!', $isbn->asString()));
        }

        $book = $this->doctrineRepository->findOneBy(array('isbn' => $isbn->asString()));

        $this->doctrineManager->remove($book);
        $this->doctrineManager->flush();
    }

    public function hasBookWithIsbn(Isbn $isbn)
    {
        return null !== $this->doctrineRepository->findOneBy(array('isbn' => $isbn->asString()));
    }

    public function searchByIsbn(Isbn $isbn)
    {
        $book = $this->doctrineRepository->findOneBy(array('isbn' => $isbn->asString()));

        return new BookSearchResults(array($book));
    }
}
