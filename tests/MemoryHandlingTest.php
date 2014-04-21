<?php
namespace qio\Tests {
    class MemoryHandlingTest extends qioTestCase {
        function testReadWriteStream() {
            $file = new \qio\File(__DIR__.DIRECTORY_SEPARATOR.'Mock'.DIRECTORY_SEPARATOR.'mockfile.txt');
            $stream = new \qio\File\Stream($file,\qio\Stream\Mode::ReadWriteTruncate);
            $writer = new \qio\Memory\Writer($stream);
            
            $stream->open();
            
            $writer->writeString('test');
            
            $stream->close();
            
            $file = new \qio\File(__DIR__.DIRECTORY_SEPARATOR.'Mock'.DIRECTORY_SEPARATOR.'mockfile.txt');
            $stream = new \qio\File\Stream($file,\qio\Stream\Mode::Read);
            $reader = new \qio\Memory\Reader($stream);
            
            $stream->open();
            
            $value = $reader->readString();
            
            $stream->close();
            
            $this->assertEquals('test',$value);
        }
    }
}