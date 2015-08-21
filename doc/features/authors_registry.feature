Feature: Authors registry
  In order to make my offer searchable more easily
  As a Librarian
  I want to manage the author registry and assign authors to books

  Scenario: Adding an author to the catalog
    Given I want to add a new author
    When his name is "George R. R. Martin"
    And I try add him
    Then this new author should be in the registry

  Scenario: Removing an author from the catalog
    Given there is an author "J. K. Rowlng"
    When I try to remove her
    Then this author should no longer be in the registry

  Scenario: Searching author by name
    Given there is an author "George R. R. Martin"
    When I search author by phrase "Martin"
    Then I should find a single author "George R. R. Martin"

  Scenario: Searching books by author
    Given a book with ISBN "978-1-56619-909-4" and title "Winds of Winter" was added to the catalog
    And it is authored by "George R. R. Martin"
    And there is another book, authored by "J. K. Rowling"
    When I search catalog by author "George R. R. Martin"
    Then I should find a single book, written by this author
