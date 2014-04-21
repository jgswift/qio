<?php
namespace qio\Object\Serial {
    use qio;
    
    class Writer extends qio\Stream\Writer
    {
        /**
         * Alias for serialize
         * @param object $object
         * @return string
         */
        function writeObject($object) {
            return serialize($object);
        }

        /**
         * Default serial writer
         * @param object $object
         */
        public function write($object) {
            if($this->isWrapped()) {
                $this->wrapper->write($this->writeObject($object));
            } else {
                $this->writeObject($object);
            }
        }
    }
}


