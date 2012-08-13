# Dianode\Events

## Overview
Dianode\Events provides a simple event management and dispatch system for PHP 5.3. It provides listener registration 
mechanisms through PHP interfaces and docblock annotations, along with support for event propagation control and event 
namespacing (similar to jQuery) for fine-grained control of the event dispatch process.

## Installation
Composer is used for package and dependency management. The only dependency is Doctrine Common, used for annotation 
reading.

If you use Composer in the project you want to use Dianode\Events in, simply add a line into your composer.json file.

```json
{
    ...
    "require": {
        "dianode/events": "dev-master"
    },
    ...
}
```

## Usage
The best way to see all the use cases for the library is to just look at the test suite. Further documentation will
appear here over time.
