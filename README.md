# Simpletest Live

This is a development-only module used for testing tests themselves.

It should be used as a drop-in replacement for DrupalWebTestCase.

## How it works

Simpletest usually runs setUp and tearDown functions that set up a totally
  virtual website inside a specially-prefixed database, and runs its tests
  inside that.
That's good for real-world usage, but when you are actually developing the
  tests, it's hard to get visibility on what has and has not happened inside
  the test setups themselves.

## No more temporary virtual database

THIS MODULE wil work around the database virtualization, and let the tests
  run on the actual current database.

## More visibility on the site during tests

This allows you to do manual setups beforehand to identify assumptions,
  and inspect the state of the site after tests have run to verify what happened.

## Scratch site only

**It should only be run on a scratch site**, as Drupal simpletests are designed
  without proper tearDown routines, and will leave trash behind in your site
  such as randomized roles and user accounts.
  Only use this on a site you are prepared to throw away.

You may find it neccessary to rebuild your harness site a few times during
  development just to clean things up and remove assumptions.

## Usage

During development, where your code would usually go

    <?php
    class SubmenuFieldTestCase extends DrupalWebTestCase {
    ...
    ?>

Instead use

    <?php
    class SubmenuFieldTestCase extends DrupalWebTestCaseLive {
    ...
    ?>

This should place this modules version of the simpletest harness
  behaviour in place.
As there is no expectation that a tearDown of database rebuild will happen,
  you may have to update your test procedures slightly to include look-ahead
  behaviours to check if certain tasks have already been run once.