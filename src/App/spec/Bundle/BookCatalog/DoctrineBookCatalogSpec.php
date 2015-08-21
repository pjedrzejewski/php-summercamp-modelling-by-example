<?php

namespace spec\App\Bundle\BookCatalog;

use App\Domain\BookCatalog\Search\BookSearchResults;
use App\Domain\BookInterface;
use App\Domain\Isbn;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DoctrineBookCatalogSpec extends ObjectBehavior
{
    function let(ObjectManager $doctrineManager, ObjectRepository $doctrineRepository)
    {
        $this->beConstructedWith($doctrineManager, $doctrineRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('App\Bundle\BookCatalog\DoctrineBookCatalog');
    }

    function it_is_a_book_catalog()
    {
        $this->shouldImplement('App\Domain\BookCatalog\BookCatalogInterface');
    }

    function it_adds_and_persists_a_new_book(BookInterface $book, Isbn $isbn, $doctrineManager, $doctrineRepository)
    {
        $book->isbn()->willReturn($isbn);
        $isbn->asString()->willReturn('978-1-56619-909-4');

        $doctrineRepository->findOneBy(array('isbn' => '978-1-56619-909-4'))->willReturn(null);

        $doctrineManager->persist($book)->shouldBeCalled();
        $doctrineManager->flush()->shouldBeCalled();

        $this->add($book);
    }

    function it_throws_an_exception_if_book_with_given_isbn_already_exists(BookInterface $book, Isbn $isbn, $doctrineManager, $doctrineRepository)
    {
        $book->isbn()->willReturn($isbn);
        $isbn->asString()->willReturn('978-1-56619-909-4');

        $doctrineRepository->findOneBy(array('isbn' => '978-1-56619-909-4'))->willReturn($book);

        $doctrineManager->persist($book)->shouldNotBeCalled();
        $doctrineManager->flush()->shouldNotBeCalled();

        $this
            ->shouldThrow(new \InvalidArgumentException('Book with ISBN "978-1-56619-909-4" already exists!'))
            ->duringAdd($book)
        ;
    }

    function it_removes_a_book_based_on_isbn_number(BookInterface $book, Isbn $isbn, $doctrineManager, $doctrineRepository)
    {
        $isbn->asString()->willReturn('978-1-56619-909-4');

        $doctrineRepository->findOneBy(array('isbn' => '978-1-56619-909-4'))->willReturn($book);

        $doctrineManager->remove($book)->shouldBeCalled();
        $doctrineManager->flush()->shouldBeCalled();

        $this->remove($isbn);
    }

    function it_throws_an_exception_if_book_with_given_isbn_does_not_exist(BookInterface $book, Isbn $isbn, $doctrineManager, $doctrineRepository)
    {
        $isbn->asString()->willReturn('978-1-56619-909-4');

        $doctrineRepository->findOneBy(array('isbn' => '978-1-56619-909-4'))->willReturn(null);

        $this
            ->shouldThrow(new \InvalidArgumentException('Book with ISBN "978-1-56619-909-4" does not exist!'))
            ->duringRemove($isbn)
        ;
    }

    function it_returns_true_if_book_exists_in_the_catalog(BookInterface $book, Isbn $isbn, $doctrineRepository)
    {
        $isbn->asString()->willReturn('978-1-56619-909-4');

        $doctrineRepository->findOneBy(array('isbn' => '978-1-56619-909-4'))->willReturn($book);

        $this->hasBookWithIsbn($isbn)->shouldReturn(true);
    }

    function it_returns_false_if_book_does_not_exist_in_the_catalog(Isbn $isbn, $doctrineRepository)
    {
        $isbn->asString()->willReturn('978-1-56619-909-4');

        $doctrineRepository->findOneBy(array('isbn' => '978-1-56619-909-4'))->willReturn(null);

        $this->hasBookWithIsbn($isbn)->shouldReturn(false);
    }

    function it_searches_book_by_isbn_number(BookInterface $book, Isbn $isbn, $doctrineRepository)
    {
        $isbn->asString()->willReturn('978-1-56619-909-4');

        $doctrineRepository->findOneBy(array('isbn' => '978-1-56619-909-4'))->willReturn($book);

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
