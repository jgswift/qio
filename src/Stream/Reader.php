<?php
namespace qio\Stream {
    abstract class Reader extends Wrapper
    {
        /**
         * Default reader method all derived readers must implement
         */
        abstract function read($length=null);
    }
}


