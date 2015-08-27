<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;

class DomainContext implements Context, SnippetAcceptingContext
{
    /**
     * @Given I want to add a new book
     */
    public function iWantToAddANewBook()
    {
        throw new PendingException();
    }

    /**
     * @When I set the title to :title and ISBN to :isbn
     */
    public function iSetTheTitleToAndIsbnTo($title, $isbn)
    {
        throw new PendingException();
    }

    /**
     * @When I try add this book to the catalog
     */
    public function iTryAddThisBookToTheCatalog()
    {
        throw new PendingException();
    }

    /**
     * @Then this new book should be in the catalog
     */
    public function thisNewBookShouldBeInTheCatalog()
    {
        throw new PendingException();
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
}
