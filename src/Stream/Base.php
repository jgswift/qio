<?php
namespace qio\Stream {
    use observr;
    use qio;
    use observr\Subject\SubjectInterface;
    
    abstract class Base implements qio\Stream, SubjectInterface {
        use observr\Subject;
        
        /**
         * Stream timeout
         * @var integer
         */
        protected $timeout;
        
        /**
         * Stream encoding (Default UTF-8)
         * @var string
         */
        protected $encoding;
        
        /**
         * Stream pointer or resource
         * @var resource
         */
        protected $pointer;
        
        /**
         * Stream context
         * @var \qio\Context
         */
        protected $context;
        
        /**
         * Default stream constructor
         * @param resource|qio\Resource $pointer
         * @param string $encoding
         * @param integer $timeout
         * @param \qio\Context $context
         */
        public function __construct($pointer = null, $encoding = null, $timeout = -1, qio\Context $context = null)
        {
            if(!is_null($pointer) && is_resource($pointer)) {
                $this->setPointer($pointer);
            }

            if(!is_null($context)) {
                $this->setContext($context);
            }
            
            if(!is_null($encoding)) {
                $this->setEncoding($encoding);
            }
            
            $this->setTimeout($timeout);
        }
        
        /**
         * Alias for stream_get_wrappers
         * @return array
         */
        public static function getWrappers() {
            return stream_get_wrappers();
        }
        
        /**
         * Alias for stream_get_transports
         * @return array
         */
        public static function getTransports() {
            return stream_get_transports();
        }
        
        /**
         * Alias for stream_get_filters
         * @return array
         */
        public static function getFilters() {
            return stream_get_filters();
        }

        /**
         * Retrieve stream context
         * @return \qio\Context
         */
        public function getContext() {
            return $this->context;
        }

        /**
         * Update stream context
         * @param qio\Context $context
         * @return qio\Context
         */
        public function setContext(qio\Context $context) {
            $create = function()use($context) {
                if(!$context->isCreated()) {
                    $context->create();
                }
            };
            
            if(!$this->isOpen()) {
                $this->attach('open',function()use($create) {
                    $create();
                });
            } else {
                $create();
            }
            
            return $this->context = $context;
        }

        /**
         * Retrieve stream pointer or resource
         * @return resource
         */
        public function getPointer() {
            return $this->pointer;
        }

        /**
         * Update stream pointer or resource
         * @param resource $pointer
         * @return resource
         */
        public function setPointer($pointer) {
            return $this->pointer = $pointer;
        }
        
        /**
         * Event dispatcher and default return value given for open sequence
         * Most derived streams should call this method and not fully override it
         * without also following the signature here
         * @return boolean
         */
        public function open() {
            $this->setState(self::STATE_OPEN);
            return $this->isOpen();
        }
        
        /**
         * Event dispatcher and default return value given for close sequence
         * Most derived streams should call this method and not fully override it
         * without also following the signature here
         */
        public function close() {
            $this->setState(self::STATE_CLOSE);
            unset($this->pointer);
        }

        /**
         * Checks if resource is available
         * @return boolean
         */
        public function isOpen() {
            return isset($this->pointer);
        }

        /**
         * Defaults to UTF-8 encoding
         * @return \qio\Stream\Stream\Encoding
         */
        public function getDefaultEncoding() {
            return new Stream\Encoding(Stream\Encoding::UTF_8);
        }

        /**
         * Defaults to infinity
         * @return integer
         */
        public function getDefaultTimeout() {
            return -1;
        }

        /**
         * Retrieve stream encoding
         * @return \qio\Stream\Encoding
         */
        public function getEncoding() {
            if(is_null($this->encoding)) {
                $this->encoding = $this->getDefaultEncoding();
            }

            return $this->encoding->value();
        }

        /**
         * Update stream encoding
         * @param \qio\Stream\Encoding|string $encoding
         * @return \qio\Stream\Encoding
         */
        public function setEncoding($encoding) {
            if(is_string($encoding)) {
                $encoding = new Stream\Encoding($encoding);
            }

            return $this->encoding = $encoding;
        }

        /**
         * Retrieve stream timeout
         * Attempts to supply default timeout if none available
         * @return integer
         */
        public function getTimeout() {
            if(is_null($this->timeout)) {
                $this->timeout = $this->getDefaultTimeout();
            }

            return $this->timeout;
        }

        /**
         * Update stream timeout
         * @param integer $timeout
         * @return integer
         */
        public function setTimeout($timeout) {
            return $this->timeout = $timeout;
        }
        
        /**
         * Helps ensure unreferenced streams arent open
         */
        function __destruct() {
            if($this->isOpen()) {
                $this->close();
            }
        }
    }
}