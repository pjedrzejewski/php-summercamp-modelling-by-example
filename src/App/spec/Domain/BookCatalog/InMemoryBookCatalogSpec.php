<?php

namespace spec\App\Domain\BookCatalog;

use App\Domain\BookCatalog\Search\BookSearchResults;
use App\Domain\BookInterface;
use App\Domain\Isbn;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InMemoryBookCatalogSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('App\Domain\BookCatalog\InMemoryBookCatalog');
    }
    
    function it_is_a_book_catalog()
    {
        $this->shouldImplement('App\Domain\BookCatalog\BookCatalogInterface');
    }

    function it_adds_book_via_isbn_number(BookInterface $book, Isbn $isbn)
    {
        $book->isbn()->willReturn($isbn);
        $isbn->asString()->willReturn('978-1-56619-909-4');

        $this->hasBookWithIsbn($isbn)->shouldReturn(false);
        $this->add($book);
        $this->hasBookWithIsbn($isbn)->shouldReturn(true);
    }

    function it_throws_exception_when_book_with_given_isbn_already_exists(BookInterface $book, Isbn $isbn)
    {
        $book->isbn()->willReturn($isbn);
        $isbn->asString()->willReturn('978-1-56619-909-4');

        $this->add($book);

        $this
            ->shouldThrow(new \InvalidArgumentException('Book with ISBN "978-1-56619-909-4" already exists!'))
            ->duringAdd($book)
        ;
    }

    function it_removes_book_via_isbn_number(BookInterface $book, Isbn $isbn)
    {
        $book->isbn()->willReturn($isbn);
        $isbn->asString()->willReturn('978-1-56619-909-4');

        $this->add($book);
        $this->remove($isbn);

        $this->hasbookWithIsbn($isbn)->shouldReturn(false);
    }

    function it_throws_exception_if_book_with_given_isbn_does_not_exist(Isbn $isbn)
    {
        $isbn->asString()->willReturn('978-1-56619-909-4');

        $this->shouldThrow(new \InvalidArgumentException('Book with ISBN "978-1-56619-909-4" does not exist!'))->duringRemove($isbn);
    }

    function it_returns_0_results_when_searching_by_isbn_and_empty(Isbn $isbn)
    {
        $isbn->asString()->willReturn('978-1-56619-909-4');

        //$this->searchByIsbn($isbn);
    }

    function it_returns_exactly_one_result_when_searching_by_exact_isbn_and_it_matches(BookInterface $book, Isbn $isbn)
    {
        $book->isbn()->willReturn($isbn);
        $isbn->asString()->willReturn('978-1-56619-909-4');

        $this->add($book);

        $this->searchByIsbn($isbn)->shouldHaveResultCount(1);
    }

    public function getMatchers()
    {
        return [
            'haveResultCount' => function ($subject, $key) {
                if (!$subject instanceof BookSearchResults || !is_int($key)) {
                    return false;
                }

                return $subject->count() === $key;
            }
        ];
    }
}
