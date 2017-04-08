# West\\Log

A PHP event manager implementation for PHP 7.1+.


## Autoloading

This package is [PSR-4][] autoloadable via composer or otherwise mapping the `West\Event`
namespace to the `src/` directory.  To run the tests the `West\Event` namespace should map
to the `tests/` directory.


## Dependencies

This package requires PHP 7.1 or later; interfaces required by the package are
documented in the [composer.json][] file.


## Code Quality

To run the unit tests and generate a coverage report with [PHPUnit][] run
`composer install` followed by `composer test` at the command line.

This package should comply with the recommendations set out in [PSR-1][], [PSR-2][]
and [PSR-4][].

To run the benchmarks and generate a report with [PHPBench][] run `composer install`
followed by `composer benchmark` at the command line.  For details about the
reports see the the [composer.json][] file and the [PHPBenchDocs][].


## Documentation

This package is documented [here](./docs/index.md).  To generate the docs run
run `composer install --no-dev`, ensure [Doxygen][] is installed and available
as `doxygen` and run `composer docs`.


## Principles

The aim of this package is to implement some of the principles of object oriented programming
described in David West's [Object Thinking][].  Many of the ideas
come from [Yegor Bugayenko][]'s distillations of West's book, and [Alan Kay][]'s comments on [Squeak][]
and [Smalltalk][].  In particular:

- Everything is an object.

  _Some exceptions are permitted, for example the introduction of resources, in part to compensate
  for the lack of OOP in earlier versions of the language, are allowed._

- An object exists in real life.

- An object is unique.

  _Some exceptions are permitted, for example two objects encapsulating distinct URIs may point to the
  same resource without aggressive [URI normalization](https://en.wikipedia.org/wiki/URL_normalization)._

- An object is immutable.

  _Various definitions of immutability can be contended, e.g. a file object representing a file in a
  mutable filesystem are considered mutable regardless of whether an changes to the file are visible through the object._

- A class name does not end in '-er'.

  _An object is a thing, and a class name described what objects of that class are, not what they do.
  Verbs are used for method names._

- There are no `null` values.

  _The special value `null` is not an instance of any class, and does not implement any (useful) interface. An object that does not
  implement the interfaces expected of it's fellow class members does not exist._

- A class does not have any static properties or methods.

  _An application consists of objects interacting through interfaces, and classes are used to describe sets of objects, not
  to implement logic for those objects._

- A class is either abstract or final. Only abstract methods are protected.

  _An object is the master of it's internal behavior, and works with other objects only through it's public interface. Extending a 'completed' class
  may break the logic of the parent class. Allowing abstract objects at all is a concession with which the author is increasingly unhappy._

- Objects interact through interfaces.

  _Objects judge objects based on what they do, not what they are.  A public method not documented by an interface would be redundant._


[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-3]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md
[Composer]: http://getcomposer.org/
[Doxygen]: http://www.stack.nl/~dimitri/doxygen/
[PHPUnit]: http://phpunit.de/
[PHPBench]: https://github.com/phpbench/phpbench
[PHPBenchDocs]: http://phpbench.readthedocs.io/en/latest/
[west-php/log]: https://packagist.org/packages/aura/di
[composer.json]: ./composer.json
[Object Thinking]: http://davewest.us/product/object-thinking/
[Yegor Bugayenko]: http://www.yegor256.com/
[Alan Kay]: https://en.wikipedia.org/wiki/Alan_Kay/
[Squeak]: http://squeak.org/
[Smalltalk]: https://en.wikipedia.org/wiki/Smalltalk
