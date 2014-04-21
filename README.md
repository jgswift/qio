qio
====
PHP 5.5+ I/O utility package

[![Build Status](https://travis-ci.org/jgswift/qio.png?branch=master)](https://travis-ci.org/jgswift/qio)

## Installation

Install via [composer](https://getcomposer.org/):
```sh
php composer.phar require jgswift/qio:dev-master
```

## Description

qio is a group of utilities meant to abstract stream applications in php. 
php already provides a large and robust implementation for handling streams and in many cases
qio just serves as an OOP abstraction around native stream handling.  However, qio also provides
supplemental implementations for bitwise streaming, directory caching, file uploading, and general asset management

## Usage

The following is a minimal example of file stream handling
```php
<?php
// WRITING DATA
$file = new qio\File('myfile.txt');
$stream = new qio\File\Stream($file,qio\Stream\Mode::ReadWriteTruncate);
$writer = new qio\File\Writer($stream);

$stream->open();

$writer->write('foobar');

$stream->close();

// READING DATA
$file = new qio\File('myfile.txt');
$stream = new qio\File\Stream($file,qio\Stream\Mode::Read);
$reader = new qio\File\Reader($stream);

$stream->open();

$value = $reader->readAll();

$stream->close();

var_dump($value); // prints "foobar"
```

Directory reading is very similar
```php
<?php
$dir = new \qio\Directory(__DIR__);
$stream = new \qio\Directory\Stream($dir);
$reader = new \qio\Directory\Reader($stream);

$stream->open();

while($info = $reader->read()) {
    echo $info->getPath()."\n"; // PRINTS PATH
}

$stream->close();
```

Here is a memory stream that handles bytes reading/writing
```php
<?php
// WRITING BYTES
$file = new qio\File('myfile.txt');
$stream = new qio\File\Stream($file,qio\Stream\Mode::ReadWriteTruncate);
$writer = new qio\Memory\Writer($stream);

$stream->open();

$writer->writeString('test');
$writer->writeInteger(4);
$writer->writeBoolean(true);

$stream->close();

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