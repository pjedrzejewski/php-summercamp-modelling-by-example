<?php

namespace spec\App\Domain\BookCatalog\Search;

use App\Domain\BookInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BookSearchResultsSpec extends ObjectBehavior
{
    function let(BookInterface $book1, BookInterface $book2, BookInterface $book3)
    {
        $this->beConstructedThrough('fromArrayOfBooks', array(array($book1, $book2, $book3)));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('App\Domain\BookCatalog\Search\BookSearchResults');
    }

    function it_throws_exception_if_results_contain_anything_else_than_book(\stdClass $object)
    {
        $this
            ->shouldThrow(new \InvalidArgumentException('Results can only contain instances of "App\Domain\BookInterface".'))
            ->during('__construct', array(array('foo', 5, $object)))
        ;
    }

    function it_is_empty_by_default()
    {
        $this->beConstructedWith(array());

        $this->count()->shouldReturn(0);
        $this->shouldBeEmpty();
    }

    function its_not_empty_when_initialized_with_some_books()
    {
        $this->shouldNotBeEmpty();
    }

    function it_is_countable()
    {
        $this->shouldImplement('\Countable');

        $this->count()->shouldReturn(3);
    }

    function it_has_books(BookInterface $book1, BookInterface $book2, BookInterface $book3)
    {
        $this->books()->shouldReturn(array($book1, $book2, $book3));
    }
}
