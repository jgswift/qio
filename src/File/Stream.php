<?php
namespace qio\File {
    use qio;
    
    class Stream extends qio\Resource\Stream {
        /**
         * Accepts file path or qio\File specifically
         * @param qio\File|string $file
         * @param string $mode
         * @param qio\Context $context
         */
        function __construct( 
                $file = null, 
                $mode = qio\Stream\Mode::Read, 
                qio\Context $context = null 
        ) {
            if(is_string($file)) {
                $file = new qio\File($file);
            } 
            
            parent::__construct($file, $mode, $context);
        }

        /**
         * Alias for getResource
         * @return \qio\File
         */
        function getFile() {
            return parent::getResource();
        }

        /**
         * Alias for setResource
         * @param qio\File $file
         * @return qio\File
         */
        function setFile(qio\File $file) {
            return parent::setResource($file);
        }
        
        /**
         * Retrieves length
         * Defaults to file size if needed
         * @return integer
         */
        public function getLength() {
            $length = parent::getLength();
            if($length < 1) {
                $length = $this->getFile()->getSize();
            }
            
            return $length;
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
                $result = fclose($this->pointer);
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
         * Truncates file to specified size
         * @param integer $size
         * @return boolean
         */
        function truncate($size) {
            if(is_numeric($size)) {
                return ftruncate($this->pointer,$size);
            }
            
            return false;
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
    }
}