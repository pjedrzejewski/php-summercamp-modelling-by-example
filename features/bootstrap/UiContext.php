<?php

use App\Domain\Book;
use App\Domain\BookTitle;
use App\Domain\Isbn;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Component\HttpKernel\KernelInterface;

class UiContext extends MinkContext implements SnippetAcceptingContext, KernelAwareContext
{
    private $kernel;

    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @BeforeScenario
     */
    public function purgeDatabase(BeforeScenarioScope $scope)
    {
        $entityManager = $this->get('doctrine.orm.entity_manager');

        $purger = new ORMPurger($entityManager);
        $purger->purge();

        $entityManager->clear();
    }

    /**
     * @Given a book with ISBN :isbn and title :title was added to the catalog
     */
    public function aBookWithIsbnAndTitleWasAddedToTheCatalog($isbn, $title)
    {
        $this->getCatalog()->add(Book::withTitleAndIsbn(new BookTitle($title), new Isbn($isbn)));
    }

    /**
     * @When I search catalog by ISBN number :isbn
     */
    public function iSearchCatalogByIsbnNumber($isbn)
    {
        $this->visit('/');
        $this->fillField('ISBN', $isbn);
        $this->pressButton('Search');
    }

    /**
     * @Then I should find a single book
     */
    public function iShouldFindASingleBook()
    {
        $this->assertPageContainsText('Found 1 result(s).');
    }

    /**
     * @return \App\Domain\BookCatalog\BookCatalogInterface
     */
    private function getCatalog()
    {
        return $this->get('app.catalog');
    }

    private function get($id)
    {
        return $this->kernel->getContainer()->get($id);
    }
}
