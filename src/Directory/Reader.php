<?php
namespace qio\Directory {
    class Reader extends \qio\File\Reader {
        
        /**
         * Stores reader stream
         * @var \qio\Directory\Stream 
         */
        protected $stream;
        
        /**
         * Default directory reader
         * @param integer $length
         * @return \qio\Directory|\qio\File
         */
        public function read($length=null) {
            $path = $this->stream->getDirectory()->getPath().$this->readPath();
            if(is_file($path)) {
                return new \qio\File($path);
            } elseif(is_dir($path)) { 
                return new \qio\Directory($path);
            }
        }
        
        /**
         * Alias for readdir
         * @return string
         */
        public function readPath() {
            return readdir($this->stream->getPointer());
        }
        
        /**
         * Alias for scandir
         * @param integer $sorting_order
         * @return array
         */
        function scan($sorting_order = 0) {
            $scan = scandir($this->stream->getDirectory()->getPath(), $sorting_order);

            // removes . and ..
            array_shift($scan);
            array_shift($scan);

            return $scan;
        }
    }
}