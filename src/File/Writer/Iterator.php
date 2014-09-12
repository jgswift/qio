<?php
namespace qio\File\Writer {
    use qio;
    use kfiltr;
    
    class Iterator extends qio\Stream\Wrapper\Iterator {
        use kfiltr\Filter, kfiltr\Hook;
        
        /**
         * Data to write to stream
         * @var array
         */
        protected $input;
        
        /**
         * Default constructor for writer iterator
         * @param qio\Stream\Wrapper $wrapper
         * @param mixed $input
         * @param array|\Traversable $filters
         */
        public function __construct(qio\Stream\Wrapper $wrapper = null, $input = null, $filters = []) {
            parent::__construct($wrapper,$filters);
            $this->setInput($input);
        }
        
        /**
         * Retrieve writer input
         * @return mixed
         */
        public function getInput() {
            return $this->input;
        }
        
        /**
         * Update writer input
         * @param mixed $input
         */
        public function setInput($input) {
            if(!is_array($input) && !($input instanceof \Traversable)) {
                $input = [$input];
            }
            
            $this->input = $input;
        }
        
        /**
         * Write next item to stream
         * @return mixed
         */
        public function current() {
            $result = null;
            if(isset($this->input[$this->position])) {
                $result = $this->translate($this->input[$this->position]);
                $this->wrapper->write($result);
            }
            
            return $result;
        }

        /**
         * Checks if input is available to write at current position
         * @return boolean
         */
        public function valid() {
            $stream = $this->wrapper->getStream();
            if($stream->isWrite()) {
                if(is_array($this->input)) {
                    if($this->position < count($this->input)) {
                        return true;
                    }
                }
            }
            
            return false;
        }
    }
}