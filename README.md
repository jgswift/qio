qio
====
PHP 5.5+ I/O utility package 

[![Build Status](https://travis-ci.org/jgswift/qio.png?branch=master)](https://travis-ci.org/jgswift/qio)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jgswift/qio/badges/quality-score.png?s=ccb4e11e7fee14345782e105875289cf6c42f5d4)](https://scrutinizer-ci.com/g/jgswift/qio/)
[![Latest Stable Version](https://poser.pugx.org/jgswift/qio/v/stable.svg)](https://packagist.org/packages/jgswift/qio)
[![License](https://poser.pugx.org/jgswift/qio/license.svg)](https://packagist.org/packages/jgswift/qio)
[![Coverage Status](https://coveralls.io/repos/jgswift/qio/badge.png?branch=master)](https://coveralls.io/r/jgswift/qio?branch=master)

## Installation

Install via cli using [composer](https://getcomposer.org/):
```sh
php composer.phar require jgswift/qio:0.1.*
```

Install via composer.json using [composer](https://getcomposer.org/):
```json
{
    "require": {
        "jgswift/qio": "0.1.*"
    }
}
```

## Description

qio is a group of utilities meant to abstract stream applications in php. 
php already provides a large and robust implementation for handling streams and in many cases
qio mainly serves as an OOP abstraction around native stream handling.  However, qio also provides
supplemental implementations for bitwise streaming, directory caching, file uploading, general asset management, and piping

## Dependency

* php 5.5+

## Usage

### File Writer

The following is a minimal example of file stream handling
```php
// WRITING DATA
$file = new qio\File('myfile.txt');
$stream = new qio\File\Stream($file,qio\Stream\Mode::ReadWriteTruncate);
$writer = new qio\File\Writer($stream);

$stream->open();

$writer->write('foobar');

$stream->close();
```

### File Reader

```php
// READING DATA
$file = new qio\File('myfile.txt');
$stream = new qio\File\Stream($file,qio\Stream\Mode::Read);
$reader = new qio\File\Reader($stream);

$stream->open();

$value = $reader->readAll();

$stream->close();

var_dump($value); // prints "foobar"
```

### Directory Reader

Directory reading is conceptual similar to the above file operations
```php
$dir = new qio\Directory(__DIR__);
$stream = new qio\Directory\Stream($dir);
$reader = new qio\Directory\Reader($stream);

$stream->open();

while($info = $reader->read()) {
    echo $info->getPath()."\n"; // PRINTS PATH
}

$stream->close();
```

### Memory Writer

Here is a memory stream that handles bytes reading/writing
```php
// WRITING BYTES
$file = new qio\File('myfile.txt');
$stream = new qio\File\Stream($file,qio\Stream\Mode::ReadWriteTruncate);
$writer = new qio\Memory\Writer($stream);

$stream->open();

$writer->writeString('test');
$writer->writeInteger(4);
$writer->writeBoolean(true);

$stream->close();
```

### Memory Reader

```php
// READING BYTES
$file = new qio\File('myfile.txt');
$stream = new qio\File\Stream($file,qio\Stream\Mode::Read);
$reader = new qio\Memory\Reader($stream);

$stream->open();

$string = $reader->readString();
$int = $reader->readInteger();
$bool = $reader->readBoolean();

$stream->close();

var_dump($string,$int,$bool); // PRINTS 'test', 4, true
```

### Object Writer

Serialize data on the fly by wrapping the file writer inside of a serial writer

```php
class User {
    public $name;
}

$user = new User;
$user->name = 'test';

$file = new qio\File('myfile.txt');
$stream = new qio\File\Stream($file,qio\Stream\Mode::ReadWriteTruncate);
$writer = new qio\Object\Serial\Writer(
                  new qio\File\Writer($stream)
              );

$stream->open();

$writer->write($user); // write user to stream

$stream->close();
```

### Object Reader

Unserialize serial data by wrapping a file reader with a serial reader

```php
$file = new qio\File('myfile.txt');
$stream = new qio\File\Stream($file,\qio\Stream\Mode::Read);
$reader = new qio\Object\Serial\Reader(
                  new qio\File\Reader($stream)
              );

$stream->open();

$user = $reader->read(); // read user from stream

$stream->close();

var_dump($user); // User#object { "name" => "test" }
```

### Reader Piping

Pipe reads input data from a source stream and writes it to an output stream automatically

```php
$myfile = new qio\File('myfile.txt');
$otherfile = new qio\File('otherfile.txt');

$source = new qio\File\Stream($file,qio\Stream\Mode::Read);
$target = new qio\File\Stream($otherfile,qio\Stream\Mode::ReadWriteTruncate);

$reader = new qio\File\Reader($source);
$writer = new qio\File\Writer($target);

$source->open();
$target->open();

$reader->pipe($writer);

$source->close();
$target->close();
```