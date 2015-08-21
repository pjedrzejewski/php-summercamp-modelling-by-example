Feature: Borrowing books
  In order to register borrowed books
  As a Librarian
  I want to manage orders

  Scenario: Creating an order
    Given a book with ISBN "978-1-56619-909-4" and title "Wind" was added to the catalog
    And there is customer "Ricky Martin" with e-mail "ricky@martin.com"
    And my library charges 5$ per week
    When I create a new order for him, with expected return in 2 weeks
    And I add book "978-1-56619-909-4" to this order
    Then its grand total should be 10$
    And its status should be "ready"

  Scenario: Creating an order for book, which is not available
    Given a book with ISBN "978-1-56619-909-4" and title "Wind" was added to the catalog
    And this book is not available
    And there is customer "Ricky Martin" with e-mail "ricky@martin.com"
    When I create a new order for him
    And I add book "978-1-56619-909-4" to this order
    Then its status should be "waiting"
