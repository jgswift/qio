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
        
        function testReadIncrementalStream() {
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
            
            $value = '';
            
            while($v = $reader->read(1)) {
                $value .= $v;
            }
            
            $stream->close();
            
            $this->assertEquals('test',$value);
        }
        
        function testReadWritePipe() {
            $original_value = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'Mock'.DIRECTORY_SEPARATOR.'mockfile.txt');
            $file = new \qio\File(__DIR__.DIRECTORY_SEPARATOR.'Mock'.DIRECTORY_SEPARATOR.'mockfile.txt');
            $file2 = new \qio\File(__DIR__.DIRECTORY_SEPARATOR.'Mock'.DIRECTORY_SEPARATOR.'mockfile2.txt');
            
            $source = new \qio\File\Stream($file,\qio\Stream\Mode::Read);
            $target = new \qio\File\Stream($file2,\qio\Stream\Mode::ReadWriteTruncate);
            
            $reader = new \qio\File\Reader($source);
            $writer = new \qio\File\Writer($target);
            
            $source->open();
            $target->open();
            
            $reader->pipe($writer);
            
            $source->close();
            $target->close();
            
            $new_value = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'Mock'.DIRECTORY_SEPARATOR.'mockfile2.txt');
            
            $this->assertEquals($original_value,$new_value);
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
        
        function testSizeView() {
            $file = new \qio\File(__DIR__.DIRECTORY_SEPARATOR.'Mock'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'image.jpg');
            
            $size = $file->getSizeString();
            
            $this->assertEquals('7.76 Kb',$size);
            
            $size = $file->getSizeString(\qio\File\SizeView::Binary);
            
            $this->assertEquals('7.58 KB',$size);
        }
        
        function testFileTouch() {
            $file = new \qio\File(__DIR__.DIRECTORY_SEPARATOR.'Mock'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'image.jpg');
            
            $time1 = $file->getTime();
            
            $file->touch();
            
            $time2 = $file->getTime();
            
            $this->assertNotEquals($time1,$time2);
        }
    }
}