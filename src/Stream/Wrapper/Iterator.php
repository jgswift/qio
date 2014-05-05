<?php
namespace qio\Stream\Wrapper {
    use qio;
    use qtil;
    
    abstract class Iterator implements \Iterator {
        /**
         * Position
         * @var integer
         */
        protected $position = 0;
        
        /**
         * Stream wrapper
         * @var \qio\Stream\Wrapper
         */
        protected $wrapper;
        
        /**
         * Filters
         * @var array 
         */
        public $filters;
        
        /**
         * Default wrapper constructor
         * @param qio\Stream\Wrapper $wrapper
         * @param array|\Traversable $filters
         */
        public function __construct(qio\Stream\Wrapper $wrapper = null, $filters = []) {
            if(!is_null($wrapper)) {
                $this->setWrapper($wrapper);
            }
            
            $this->filters = new qtil\Collection($filters);
        }
        
        /**
         * Alias for wrapper stream retrieval
         * @return \qio\Stream
         */
        public function getStream() {
            return $this->wrapper->getStream();
        }
        
        /**
         * Retrieve iterator wrapper
         * @return \qio\Stream\Wrapper
         */
        public function getWrapper() {
            return $this->wrapper;
        }
        
        /**
         * Update stream wrapper
         * @param qio\Stream\Wrapper $wrapper
         * @return qio\Stream\Wrapper
         */
        public function setWrapper(qio\Stream\Wrapper $wrapper) {
            return $this->wrapper = $wrapper;
        }
        
        /**
         * implements \Iterator::key
         * @return integer
         */
        public function key() {
            return $this->position;
        }

        /**
         * Increment position
         * implements \Iterator::next
         */
        public function next() {
            $this->position++;
        }

        /**
         * Reset position
         * implements \Iterator::rewind
         */
        public function rewind() {
            $this->position = 0;
        }
        
        /**
         * Retrieve position
         * implements \Iterator::position
         * @return integer
         */
        public function position() {
            return $this->position;
        }
        
        /**
         * Default translation/filter function
         * @param mixed $result
         * @return mixed
         */
        public function translate(&$result=null) {
            if(!is_null($result)) {
                foreach($this->filters as $filter) {
                    $result = $filter($result);
                }
            }
            
            return $result;
        }
    }
}