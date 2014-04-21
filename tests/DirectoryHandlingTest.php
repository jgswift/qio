<?php
namespace qio\Tests {
    class DirectoryHandlingTest extends qioTestCase {
        function testDirectoryReader() {
            $dir = new \qio\Directory(__DIR__);
            $stream = new \qio\Directory\Stream($dir);
            $reader = new \qio\Directory\Reader($stream);
            
            $stream->open();
            
            $value = $reader->read();
            
            $stream->close();
            
            $this->assertInstanceOf('qio\Resource',$value);
        }
        
        function testDirectoryScan() {
            $dir = new \qio\Directory(__DIR__);
            $stream = new \qio\Directory\Stream($dir);
            $reader = new \qio\Directory\Reader($stream);
            
            $stream->open();
            
            $scan = $reader->scan();
            
            $stream->close();
            
            $this->assertTrue(count($scan) > 0);
        }
    }
}