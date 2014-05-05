<?php
namespace qio\File\Reader {
    use qio;
    use kfiltr;
    
    class Iterator extends qio\Stream\Wrapper\Iterator {
        use kfiltr\Filter, kfiltr\Hook;

        /**
         * Retrieve current data from buffer and applies filters
         * @return mixed
         */
        public function current() {
            $result = $this->wrapper->read();

            $this->translate($result);

            return $result;
        }

        /**
         * Checks if stream is at end
         * @return boolean
         */
        public function valid() {
            $stream = $this->wrapper->getStream();
            if($stream->isRead()) {
                if(!$stream->eof()) {
                    return true;
                }
            }
            
            return false;
        }
    }
}