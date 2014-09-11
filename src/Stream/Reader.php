<?php
namespace qio\Stream {
    abstract class Reader extends Wrapper
    {
        /**
         * Default reader method all derived readers must implement
         */
        abstract function read($length=null);
        
        function pipe(Writer $writer) {
            while($v = $this->read()) {
                $writer->write($v);
            }
        }
    }
}


