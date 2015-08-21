PHP Summer Camp 2015 - Modelling By Example
===========================================

In this file you can find all steps with their branch names.

Refer to the "Troubleshooting" section for README file if you are stuck at any step.

SETUP
-----

* Start the virtual machine. You can find steps [here](https://github.com/netgen/summercamp-2015/blob/master/README.md);
* SSH to the machine and enter appropriate directory:

```bash
vagrant ssh
cd /var/www/summercamp/
```

* Initialize git submodule and switch to the right dir:

```bash
git submodule update workshops/modelling
cd workshops/modelling
```

* Download the latest setup by runnning:

```bash
git fetch origin
git checkout master
git pull origin master

sudo ln -sf /var/www/summercamp/workshops/modelling/installation/vhost /etc/apache2/sites-enabled/modelling.conf &&
sudo sh ./installation/run.sh
```

* Verify everything works by running the following commands:

```bash
bin/phpspec run -fpretty
bin/behat --suite=domain
bin/behat --suite=ui
```

* Switch to your working branch and the first step:

```bash
git checkout -b workshop origin/step-01-fresh-start
```

* Finally, open ``http://modelling.phpsc`` in your browser. You should see "Good morning!" message.

Let's get started!

Step 1: Fresh start
-------------------

``step-01-fresh-start``

This step has a basic application setup in place, working and all!

Step 2: Setting up the features
-------------------------------

``step-02-setting-up-the-features``

* Copy ``behat.yml.dist`` to ``behat.yml``;
* Have a look at ``domain`` and ``ui`` suites;
* Run Behat's ``domain`` suite with the following command:

```bash
bin/behat --suite=domain
```

* Generate the step definitions with the following command:

```bash``
bin/behat --suite=domain --append-snippets
```

Step 3: First step definitions
------------------------------

``step-03-first-step-definitions``

* Start filling in the step definitions.

Step 4: Implementing the Book model
-----------------------------------

``step-04-implementing-the-book``

* Using PHPSpec, implement ``Book`` model with value objects ``BookTitle`` and ``Isbn``;
* To keep things simple, we assume that our library has only new books, with ISBN13;
* Use the following snippet of code for ISBN validation:

```php
$number = (string) $number;
$number = str_replace('-', '', $number);

if (!ctype_digit($number)) {
    return false;
}

$length = strlen($number);

if ($length !== 13) {
    return false;
}

$checkSum = 0;

for ($i = 0; $i < 13; $i += 2) {
    $checkSum += $number{$i};
}
for ($i = 1; $i < 12; $i += 2) {
    $checkSum += $number{$i} * 3;
}

if(0 !== $checkSum % 10) {
    return false;
}

return true;
```

Step 5: Make some steps green!
------------------------------

``step-05-make-some-steps-green``

* Use the newly created domain models for the first Behat steps.

Step 6: Initial book catalog
----------------------------

``step-06-initial-book-catalog``

* Define the ``BookCatalogInterface``;
* Implement ``InMemoryBookCatalog`` using PHPSpec;
* Use it in our catalog related steps.

Step 7: Removing books from the catalog
---------------------------------------

``step-07-removing-books-from-the-catalog``

* Fill the next step definitions;
* Write new specs;
* Add new method ``remove`` to our catalog interface;
* Make it green, baby!

Step 8: Implementing the search
-------------------------------

``step-08-the-search``

* Again, fill our step definitions;
* Write new specs for our InMemoryBookCatalog;
* Implement BookSearchResults;
* Make it green, wooohoo!

Step 9: ISBN uniqueness validation
----------------------------------

``step-09-isbn-validation``

* You know what to do, right?
* Fill next step definitions;
* Write specs for new behavior of the catalog;
* Verify it works!

Step 10: The UI context
-----------------------

``step-10-the-ui-context``

* Generate step definitions for the UI context using the following command:

bin/behat --suite=ui --append-snippets

* Fill the step definitions;
* Write a spec for DoctrineBookCatalog;
* Implement the DoctrineBookCatalog;
* Register it as a ``app.catalog`` service:

```yaml
# config/services.yml file!

services:
  app.doctrine.manager.book:
    alias: doctrine.orm.default_entity_manager
  app.doctrine.repository.book:
    class: Doctrine\ORM\EntityRepository
    factory: [@app.doctrine.manager.book, 'getRepository']
    arguments: ['App\Domain\Book']

  app.catalog:
    class: App\Bundle\BookCatalog\DoctrineBookCatalog
    arguments:
      - @app.doctrine.manager.book
      - @app.doctrine.repository.book
```

* Import it from the main ``config.yml`` file:

```yaml
# config/config.yml file!

imports:
  - { resource: services.yml }
```

* Map our Book domain model as Doctrine entity:

```yaml
# config/doctrine/Book.orm.yml file!

App\Domain\Book:
    type: entity
    table: app_book
    id:
        isbn:
            type: string
    fields:
        title:
            type: string
```

* Update the database schema:

```bash
./console doctrine:schema:create
```

* Implement the code for our simple controller and see green scenarios!
