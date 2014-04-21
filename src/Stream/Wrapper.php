<?php
namespace qio\Stream {
    abstract class Wrapper
    {
        /**
         * Stores wrapped stream
         * @var \qio\Stream 
         */
        protected $stream;
        
        /**
         * Stores wrapper wrapping
         * @var \qio\Stream\Wrapper
         */
        protected $wrapper;

        /**
         * Accepts either stream or self as argument
         * @param self|\qio\Stream $streamOrWrapper
         */
        function __construct($streamOrWrapper) {
            if($streamOrWrapper instanceof \qio\Stream) {
                $this->stream = $streamOrWrapper;
            } elseif($streamOrWrapper instanceof self) {
                $this->wrapper = $streamOrWrapper;
            }
        }
        
        /**
         * Alias for isOpen
         * @return boolean
         */
        protected function isOpen() {
            return isset($this->stream) && $this->stream->isOpen();
        }
        
        /**
         * Retrieve wrapper
         * @return self
         */
        function getWrapper() {
            return $this->wrapper;
        }

        /**
         * Update wrapper
         * @param \qio\Stream\Wrapper $wrapper
         */
        public function setWrapper(Wrapper $wrapper) {
            if(is_null($this->stream)) {
                $this->setStream($wrapper->getStream());
            }
            
            $this->wrapper = $wrapper;
        }
        
        /**
         * Check if wrapper is wrapped
         * @return boolean
         */
        public function isWrapped() {
            return (isset($this->wrapper)) ? true : false;
        }
        
        /**
         * Compare wrapper using instanceof
         * @param string $type
         * @return boolean
         */
        public function isWrappedBy($type) {
            return ($this->wrapper instanceof $type);
        }
        
        /**
         * Retrieve wrapper stream
         * Attempts to find parent wrapper stream if none available on local
         * @return \qio\Stream
         */
        public function getStream() {
            if(is_null($this->stream) &&
               $this->isWrapped() ) {
                $this->stream = $this->wrapper->getStream();
            }
            
            return $this->stream;
        }

        /**
         * Update wrapper stream
         * Will update parent as well
         * @param \qio\Stream $stream
         */
        public function setStream(\qio\Stream $stream) {
            if($this->isWrapped()) {
                $this->wrapper->setStream( $stream );
            }
            
            $this->stream = $stream;
        }
        
        /**
         * Call magic to ensure wrapper implementation are pushed according to a logical, not just structural, hierarchy
         * @param string $method
         * @param array $arguments
         * @return mixed
         * @throws \BadMethodCallException
         */
        function __call($method, $arguments)
        {
            if($this->isWrapped() &&
               method_exists($this->wrapper, $method)) {
                return call_user_func_array([$this->wrapper, $method], $arguments);
            }
            
            throw new \BadMethodCallException;
        }

        /**
         * Alias for stream getEncoding
         * @return string
         */
        public function getEncoding() {
            return $this->getStream()->getEncoding();
        }

        /**
         * Alias for stream setEncoding
         * @param mixed $encoding
         */
        public function setEncoding($encoding) {
            $this->getStream()->setEncoding($encoding);
        }
    }
}