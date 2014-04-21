<?php
namespace qio\File {
    class Writer extends \qio\Stream\Writer
    {
        /**
         * Default file writer
         * @param string $value
         * @return integer
         */
        function write($value) {
            $value = (string)$value;

            return fwrite($this->stream->getPointer(),
                          $value,
                          strlen($value));
        }
    }
}


