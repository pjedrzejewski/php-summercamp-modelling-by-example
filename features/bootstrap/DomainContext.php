<?php

use App\Domain\Book;
use App\Domain\BookCatalog\BookCatalogInterface;
use App\Domain\BookCatalog\InMemoryBookCatalog;
use App\Domain\BookInterface;
use App\Domain\BookTitle;
use App\Domain\Isbn;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;

class DomainContext implements Context, SnippetAcceptingContext
{
    /**
     * @var BookCatalogInterface
     */
    private $catalog;

    /**
     * @var null|BookInterface
     */
    private $currentBook;

    public function __construct()
    {
        $this->catalog = new InMemoryBookCatalog();
    }

    /**
     * @Given I want to add a new book
     */
    public function iWantToAddANewBook()
    {
        $this->currentBook = null;
    }

    /**
     * @When I set the title to :title and ISBN to :isbn
     */
    public function iSetTheTitleToAndIsbnTo($title, $isbn)
    {
        $this->currentBook = Book::withTitleAndIsbn(new BookTitle($title), new Isbn($isbn));
    }

    /**
     * @When I try add this book to the catalog
     */
    public function iTryAddThisBookToTheCatalog()
    {
        $this->assertCurrentBookIsDefined();

        $this->catalog->add($this->currentBook);
    }

    /**
     * @Then this new book should be in the catalog
     */
    public function thisNewBookShouldBeInTheCatalog()
    {
        $this->assertCurrentBookIsDefined();

        if (false === $this->catalog->hasBookWithIsbn($this->currentBook->isbn())) {
            throw new \Exception('Book is not in the catalog...');
        }
    }

    /**
     * @Given a book with ISBN :isbn and title :title was added to the catalog
     */
    public function aBookWithIsbnAndTitleWasAddedToTheCatalog($isbn, $title)
    {
        throw new PendingException();
    }

    /**
     * @When I try to remove it from the catalog
     */
    public function iTryToRemoveItFromTheCatalog()
    {
        throw new PendingException();
    }

    /**
     * @Then this book should no longer be in the catalog
     */
    public function thisBookShouldNoLongerBeInTheCatalog()
    {
        throw new PendingException();
    }

    /**
     * @When I search catalog by ISBN number :isbn
     */
    public function iSearchCatalogByIsbnNumber($isbn)
    {
        throw new PendingException();
    }

    /**
     * @Then I should find a single book
     */
    public function iShouldFindASingleBook()
    {
        throw new PendingException();
    }

    /**
     * @When I try to create another book with the same ISBN number
     */
    public function iTryToCreateAnotherBookWithTheSameIsbnNumber()
    {
        throw new PendingException();
    }

    /**
     * @Then I should receive an error about non unique book
     */
    public function iShouldReceiveAnErrorAboutNonUniqueBook()
    {
        throw new PendingException();
    }

    private function assertCurrentBookIsDefined()
    {
        if (null === $this->currentBook) {
            throw new \LogicException('Current book must be defined!');
        }
    }
}
