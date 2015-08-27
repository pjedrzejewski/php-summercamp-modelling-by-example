<?php

use App\Domain\Book;
use App\Domain\BookCatalog\BookCatalogInterface;
use App\Domain\BookCatalog\InMemoryBookCatalog;
use App\Domain\BookCatalog\Search\BookSearchResults;
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

    /**
     * @var BookSearchResults
     */
    private $currentBookSearchResults;

    public function __construct()
    {
        $this->catalog = new InMemoryBookCatalog();
        $this->currentBookSearchResults = new BookSearchResults(array());
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
        $this->currentBook = Book::withTitleAndIsbn(new BookTitle($title), new Isbn($isbn));
        $this->catalog->add($this->currentBook);
    }

    /**
     * @When I try to remove it from the catalog
     */
    public function iTryToRemoveItFromTheCatalog()
    {
        if (null === $this->currentBook) {
            throw new \LogicException('First create a book, before trying to remove it!');
        }
        $this->catalog->remove($this->currentBook->isbn());
    }

    /**
     * @Then this book should no longer be in the catalog
     */
    public function thisBookShouldNoLongerBeInTheCatalog()
    {
        $this->assertCurrentBookIsDefined();
        if (true === $this->catalog->hasBookWithIsbn($this->currentBook->isbn())) {
            throw new \Exception('Book is still in the catalog...');
        }
    }

    /**
     * @When I search catalog by ISBN number :isbn
     */
    public function iSearchCatalogByIsbnNumber($isbn)
    {
        $this->currentBookSearchResults = $this->catalog->searchByIsbn(new Isbn($isbn));
    }

    /**
     * @Then I should find a single book
     */
    public function iShouldFindASingleBook()
    {
        if (1 !== $actualCount = $this->currentBookSearchResults->count()) {
            throw new \Exception(sprintf('Expected exactly 1 book, but got %d...', $actualCount));
        }
    }

    /**
     * @When I try to create another book with the same ISBN number
     */
    public function iTryToCreateAnotherBookWithTheSameIsbnNumber()
    {
        $this->assertCurrentBookIsDefined();

        $this->currentBook = Book::withTitleAndIsbn(new BookTitle('Random Title'), new Isbn($this->currentBook->isbn()->asString()));
    }
    /**
     * @Then I should receive an error about non unique book
     */
    public function iShouldReceiveAnErrorAboutNonUniqueBook()
    {
        $this->assertCurrentBookIsDefined();

        try {
            $this->catalog->add($this->currentBook);
        } catch (\Exception $exception) {
            return;
        }

        throw new \Exception('Expected exception, but got none.');
    }

    private function assertCurrentBookIsDefined()
    {
        if (null === $this->currentBook) {
            throw new \LogicException('Current book must be defined!');
        }
    }
}
