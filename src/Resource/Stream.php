<?php
namespace qio\Resource {
    use qio;
    
    class Stream extends qio\Stream\Base {
        /**
         * Stores stream resource
         * @var resource
         */
        protected $resource;
        
        /**
         * Stores stream mode
         * @var \qio\Stream\Mode
         */
        protected $mode;
        
        /**
         * Stores stream chunk size
         * @var integer
         */
        protected $length = 1024;

        /**
         * constructor for resource stream
         * @param qio\Resource $resource
         * @param string $mode
         * @param qio\Context $context
         */
        function __construct( 
                $resource = null, 
                $mode = null, 
                qio\Context $context = null 
        ) {
            parent::__construct();
            if(is_resource($resource)) {
                $this->setPointer($resource);
            } elseif($resource instanceof qio\Resource) {
                $this->resource = $resource;
            }

            if(!is_null($mode)) {
                $this->mode = new qio\Stream\Mode($mode); 
            } elseif($resource instanceof qio\Resource) {
                $this->mode = new qio\Stream\Mode($resource->getDefaultMode());
            }

            if($context instanceof qio\Context) {
                $this->setContext($context);
            }
        }
        
        /**
         * Alias for setEncoding
         * Additionally calls stream_encoding
         * @param mixed $encoding
         */
        public function setEncoding($encoding) {
            parent::setEncoding($encoding);
            
            if($this->isOpen()) {
                stream_encoding($this->pointer,(string)$this->encoding);
            }
        }

        /**
         * Retrieve local stream resource
         * @return qio\Resource
         */
        function getResource() {
            return $this->resource;
        }

        /**
         * Update local stream resource
         * @param qio\Resource $file
         * @return qio\Resource
         */
        function setResource(qio\Resource $resource) {
            return $this->resource = $resource;
        }

        /**
         * Retrieve stream mode
         * @return \qio\Stream\Mode
         */
        function getMode() {
            return $this->mode;
        }

        /**
         * Examines mode for write characteristics
         * @return boolean
         */
        function isWrite() {
            return  $this->mode->equals('w')  || 
                    $this->mode->equals('r+') || 
                    $this->mode->equals('w+') ||
                    $this->mode->equals('a')  || 
                    $this->mode->equals('a+') || 
                    $this->mode->equals('x')  || 
                    $this->mode->equals('x+') ||
                    $this->mode->equals('c')  ||
                    $this->mode->equals('c+');
        }

        /**
         * Examines mode for read characteristics
         * @return boolean
         */
        function isRead() {
            return  $this->mode->equals('r')  || 
                    $this->mode->equals('r+') || 
                    $this->mode->equals('w+') ||
                    $this->mode->equals('a+') || 
                    $this->mode->equals('x+') ||
                    $this->mode->equals('c+');
        }

        /**
         * Opens up stream
         * @return resource
         */
        function open() {
            if(isset($this->context)) {
                $this->pointer = fopen($this->resource->getPath(), $this->mode->value(), false, $this->context);
            } else {
                $this->pointer = fopen($this->resource->getPath(), $this->mode->value());
            }
            
            if($this->pointer) {
                parent::open();
            }

            return $this->pointer;
        }

        /**
         * Closes down stream
         * @return boolean
         */
        function close() {
            if(is_resource($this->pointer)) {
                $result = fclose( $this->pointer );
                parent::close();
                return $result;
            }

            return false;
        }

        /**
         * Alias for feof
         * @return bool
         */
        function eof() {
            return feof($this->pointer);
        }

        /**
         * Alias for fseek
         * @param integer $offset
         * @param integer $whence
         * @return integer
         */
        function seek($offset = 0, $whence = SEEK_SET) {
            return fseek($this->pointer, $offset, $whence);
        }

        /**
         * Alias for rewind
         * @return boolean
         */
        function rewind() {
            return rewind($this->pointer);
        }

        /**
         * Alias for flock
         * @param integer $operation
         * @param integer $wouldblock
         * @return boolean
         */
        function lock($operation = LOCK_EX, &$wouldblock = null) {
            return flock($this->pointer, $operation, $wouldblock);
        }

        /**
         * Alias for flock with LOCK_UN provided by default
         * @return boolean
         */
        function unlock() {
            return $this->lock(LOCK_UN);
        }

        /**
         * Alias for ftell
         * @return integer
         */
        function position() {
            return ftell($this->pointer);
        }

        /**
         * Retrieve stream chunk size
         * @return integer
         */
        function getLength() {
            return $this->length;
        }

        /**
         * Update stream chunk size
         * @param integer $length
         * @return integer
         */
        function setLength($length) {
            return $this->length = $length;
        }
        
        /**
         * Alias for setTimeout
         * Additionally calls stream_set_timeout
         * @param integer $timeout
         * @return integer
         */
        public function setTimeout($timeout)
        {
            parent::setTimeout($timeout);
            if($this->isOpen()) {
                stream_set_timeout($this->pointer,$timeout);
            }
            
            return $timeout;
        }
        
        /**
         * Alias for stream_get_meta_data
         * @return array
         */
        public function getMetaData() {
            if($this->isOpen()) {
                return stream_get_meta_data($this->pointer);
            }
            
            return [];
        }
        
        /**
         * Alias for stream_set_read_buffer
         * @param integer $bufferSize
         * @return integer
         */
        public function setReadBuffer($bufferSize) {
            if($this->isOpen()) {
                stream_set_read_buffer($this->pointer, $bufferSize);
            }
            
            return $bufferSize;
        }
        
        /**
         * Alias for stream_set_write_buffer
         * @param integer $bufferSize
         * @return integer
         */
        public function setWriteBuffer($bufferSize) {
            if($this->isOpen()) {
                stream_set_write_buffer($this->pointer, $bufferSize);
            }
            
            return $bufferSize;
        }
        
        /**
         * Alias for stream_set_chunk_size
         * @param integer $chunkSize
         * @return integer
         */
        public function setChunkSize($chunkSize) {
            if($this->isOpen()) {
                stream_set_chunk_size($this->pointer, $chunkSize);
            }
            
            return $chunkSize;
        }
        
        /**
         * Alias for stream_set_blocking
         * @param boolean $blocking
         * @return boolean
         */
        public function setBlocking($blocking)
        {
            $blocking = (bool)$blocking;
            if($this->isOpen()) {
                stream_set_blocking($this->pointer, $blocking);
            }
            
            return $blocking;
        }

        /**
         * Alias for stream_is_local
         * @return boolean
         */
        public function isLocal() {
            if($this->isOpen()) {
                return stream_is_local($this->pointer);
            }
            
            return false;
        }
        
        /**
         * Alias for stream_supports_lock
         * @return boolean
         */
        public function isLockable() {
            if($this->isOpen()) {
                return stream_supports_lock($this->pointer);
            }
            
            return false;
        }
        
        /**
         * Helper function using stream_copy_to_stream
         * @param \qio\Resource\Stream $stream
         * @param integer $maxlength
         * @param integer $offset
         */
        public function copyTo(Stream $stream, $maxlength = -1, $offset = 0) {
            if($this->isOpen()) {
                $destination = $stream->getPointer();
                stream_copy_to_stream($this->pointer, $destination, $maxlength, $offset);
            }
        }
        
        /**
         * Default notification
         * @param string $notification_code
         * @param string $severity
         * @param string $message
         * @param integer $message_code
         * @param integer $bytes_transferred
         * @param integer $bytes_max
         */
        public function defaultNotificationCallback($notification_code, $severity, $message, $message_code, $bytes_transferred, $bytes_max) {
            $e = new observr\Event($this,[
                'severity' => $severity,
                'message' => $message,
                'code' => $message_code,
                'transfered' => $bytes_transferred,
                'max' => $bytes_max
            ]);
            
            if($this->hasObservers($notification_code)) {
                $this->setState($notification_code,$e);
            }
        }
        
        /**
         * Register notification callback
         * @param callable $callback
         */
        function addNotificationCallback(callable $callback = null) {
            if(is_null($callback)) {
                $callback = [$this,'defaultNotificationCallback'];
            }
            
            $this->context->parameters->insert('notification',$callback);
        }
    }
}

