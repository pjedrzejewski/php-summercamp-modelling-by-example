<?php

namespace spec\App\Domain;

use App\Domain\BookTitle;
use App\Domain\Isbn;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BookSpec extends ObjectBehavior
{
    function let(BookTitle $title, Isbn $isbn)
    {
        $title->asString()->willReturn('Winds of Winter');
        $isbn->asString()->willReturn('978-1-56619-909-4');

        $this->beConstructedThrough('withTitleAndIsbn', array($title, $isbn));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('App\Domain\Book');
    }

    function it_is_a_book()
    {
        $this->shouldImplement('App\Domain\BookInterface');
    }

    function it_has_a_title($title)
    {
        $this->title()->shouldBeSameTitleAs($title);
    }

    function it_has_an_isbn_number($isbn)
    {
        $this->isbn()->shouldBeSameIsbnAs($isbn);
    }

    public function getMatchers()
    {
        return [
            'beSameTitleAs' => function ($subject, $key) {
                if (!$subject instanceof BookTitle || !$key instanceof BookTitle) {
                    return false;
                }

                return $subject->asString() === $key->asString();
            },
            'beSameIsbnAs' => function ($subject, $key) {
                if (!$subject instanceof Isbn || !$key instanceof Isbn) {
                    return false;
                }

                return $subject->equals($key);
            },
        ];
    }
}