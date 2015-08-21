Feature: Customer registration
  In order to create customer profiles
  As a Librarian
  I want to register new customers

  Scenario: Adding a customer
    Given a new customer has to be registered
    When I fill in his e-mail as "john@doe.com"
    And his name is "John Doe"
    And I register him as library customer
    Then he should appear in the customer registry

  Scenario: Removing a customer from the registry
    Given there is customer "Ricky Martin" with e-mail "ricky@martin.com"
    When I remove this customer
    Then he should not be a registered customer anymore

  Scenario: Searching customer by e-mail
    Given there is customer "Ricky Martin" with e-mail "ricky@martin.com"
    When I search customer registry by e-mail "ricky@martin.com"
    Then I should find a single customer record

  Scenario: Adding a customer with existing e-mail
    Given there is customer "Ricky Martin" with e-mail "ricky@martin.com"
    When I try to register a customer with the same e-mail
    Then I should receive an error that this customer already exists
