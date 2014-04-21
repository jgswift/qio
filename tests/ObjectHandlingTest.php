<?php
namespace qio\Tests {
    class ObjectHandlingTest extends qioTestCase {
        function testReadWriteStream() {
            $user = new Mock\User;
            $user->name = 'test';
            
            $file = new \qio\File(__DIR__.DIRECTORY_SEPARATOR.'Mock'.DIRECTORY_SEPARATOR.'mockfile.txt');
            $stream = new \qio\File\Stream($file,\qio\Stream\Mode::ReadWriteTruncate);
            $filewriter = new \qio\File\Writer($stream);
            $objectwriter = new \qio\Object\Serial\Writer($filewriter);
            
            $stream->open();
            
            $objectwriter->write($user);
            
            $stream->close();
            
            $file = new \qio\File(__DIR__.DIRECTORY_SEPARATOR.'Mock'.DIRECTORY_SEPARATOR.'mockfile.txt');
            $stream = new \qio\File\Stream($file,\qio\Stream\Mode::Read);
            $filereader = new \qio\File\Reader($stream);
            $objectreader = new \qio\Object\Serial\Reader($filereader);
            
            $stream->open();
            
            $user_input = $objectreader->read();
            
            $stream->close();
            
            $this->assertEquals('test',$user_input->name);
        }
    }
}