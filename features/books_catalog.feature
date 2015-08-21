Feature: Books catalog
  In order to create an offer for customers
  As a Librarian
  I want to manage the catalog

  Scenario: Adding a book to the catalog
    Given I want to add a new book
    When I set the title to "Winds of Winter" and ISBN to "978-1-56619-909-4"
    And I try add this book to the catalog
    Then this new book should be in the catalog

  Scenario: Removing a book from the catalog
    Given a book with ISBN "978-1-56619-909-4" and title "Winds of Winter" was added to the catalog
    When I try to remove it from the catalog
    Then this book should no longer be in the catalog

  @critical
  Scenario: Searching books by ISBN number
    Given a book with ISBN "978-1-56619-909-4" and title "Winds of Winter" was added to the catalog
    When I search catalog by ISBN number "978-1-56619-909-4"
    Then I should find a single book

  Scenario: Adding a book with existing ISBN number
    Given a book with ISBN "978-1-56619-909-4" and title "Winds of Winter" was added to the catalog
    When I try to create another book with the same ISBN number
    Then I should receive an error about non unique book