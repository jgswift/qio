<?php
namespace qio\Stream {
    abstract class Writer extends Wrapper
    {
        /**
         * Default write method all derived writers must implement
         */
        abstract function write($data);
    }
}

