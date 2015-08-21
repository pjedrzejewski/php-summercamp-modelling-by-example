<?php

namespace spec\App\Domain;

use App\Domain\Isbn;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IsbnSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('978-1-56619-909-4');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('App\Domain\Isbn');
    }

    function it_cannot_be_empty()
    {
        $this->shouldThrow(new \InvalidArgumentException('ISBN number cannot be an empty string!'))->during('__construct', array(''));
    }

    function it_has_to_respect_isbn10_or_isbn13_standard()
    {
        $this->shouldThrow(new \InvalidArgumentException('This is not a valid ISBN number.'))->during('__construct', array('12345'));
    }
    
    function it_can_be_represented_as_string()
    {
        $this->asString()->shouldReturn('978-1-56619-909-4');
    }

    function it_can_be_compared_with_other_isbns(Isbn $isbn1, Isbn $isbn2)
    {
        $isbn1->asString()->willReturn('978-1-56619-909-4');
        $isbn2->asString()->willReturn('978-3-39572-432-1');

        $this->equals($isbn1)->shouldReturn(true);
        $this->equals($isbn2)->shouldReturn(false);
    }
}
