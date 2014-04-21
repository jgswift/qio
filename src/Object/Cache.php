<?php
namespace qio\Object {
    use qio;
    
    class Cache extends qio\Directory\Cache
    {
        /**
         * Loads cached object using unserialize
         * @param string $name
         * @return object
         */
        protected function load($name) {
            $stream = $this->getStream($name);
            $reader = new qio\Object\Serial\Reader($stream);

            $stream->open();
            $input = $reader->readObject();
            $stream->close();

            return $input;
        }

        /**
         * Caches value using serialization
         * @param string $name
         * @param mixed $value
         * @throws qio\Exception
         */
        protected function save($name, $value) {
            if(!is_object($value)) {
                throw new qio\Exception('Object\Cache can only cache objects, '. 
                                         gettype($value) .' provided');
            }

            $stream = $this->getStream($name , qio\Stream\Mode::ReadWriteTruncate);
            $writer = new qio\Object\Serial\Writer($stream);

            $stream->open();

            $writer->writeObject($value);

            $stream->close();
        }
    }
}