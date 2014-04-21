<?php
namespace qio\Object\Serial {
    use qio;
    
    class Reader extends qio\Stream\Reader
    {
        /**
         * Alias for unserialize
         * @param string $str
         * @return object
         */
        function readObject($str) {
            return unserialize($str);
        }

        /**
         * Default serial reader
         * @param integer $length
         * @return object
         */
        public function read($length=null) {
            if($this->isWrapped()) {
                if($this->isWrappedBy('qio\File\Reader')) {
                    return $this->readObject($this->wrapper->readAll());
                } else {
                    return $this->readObject($this->wrapper->read($length));
                }
            }
        }
    }
}