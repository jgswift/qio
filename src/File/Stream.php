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
    }
}

