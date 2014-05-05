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
        
        function testWrapperIterator() {
            $file = new \qio\File(__DIR__.DIRECTORY_SEPARATOR.'Mock'.DIRECTORY_SEPARATOR.'mockfile.txt');
            $stream = new \qio\File\Stream($file,\qio\Stream\Mode::ReadWriteTruncate);
            $writer = new \qio\File\Writer($stream);

            $it = new \qio\File\Writer\Iterator($writer, 'test');
            
            $stream->open();
            
            foreach($it as $buffer) {
                $this->assertEquals('test',$buffer);
            }
            
            $stream->close();
            
            $file = new \qio\File(__DIR__.DIRECTORY_SEPARATOR.'Mock'.DIRECTORY_SEPARATOR.'mockfile.txt');
            $stream = new \qio\File\Stream($file,\qio\Stream\Mode::Read);
            $reader = new \qio\File\Reader($stream);
            
            $it = new \qio\File\Reader\Iterator($reader);
            
            $stream->open();
            foreach($it as $buffer) {
                $this->assertEquals('test',$buffer);
            }
            $stream->close();
        }
    }
}