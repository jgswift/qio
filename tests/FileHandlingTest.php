<?php
namespace qio\Tests {
    class FileHandlingTest extends qioTestCase {
        function testReadWriteStream() {
            $file = new \qio\File(__DIR__.DIRECTORY_SEPARATOR.'Mock'.DIRECTORY_SEPARATOR.'mockfile.txt');
            $stream = new \qio\File\Stream($file,\qio\Stream\Mode::ReadWriteTruncate);
            $writer = new \qio\File\Writer($stream);
            
            $stream->open();
            
            $writer->write('test');
            
            $stream->close();
            
            $file = new \qio\File(__DIR__.DIRECTORY_SEPARATOR.'Mock'.DIRECTORY_SEPARATOR.'mockfile.txt');
            $stream = new \qio\File\Stream($file,\qio\Stream\Mode::Read);
            $reader = new \qio\File\Reader($stream);
            
            $stream->open();
            
            $value = $reader->readAll();
            
            $stream->close();
            
            $this->assertEquals('test',$value);
        }
        
        function testFilePermissions() {
            $file = new \qio\File(__DIR__.DIRECTORY_SEPARATOR.'Mock'.DIRECTORY_SEPARATOR.'mockfile.txt');
            
            $this->assertEquals(10,strlen((string)$file->getMode()));
        }
    }
}