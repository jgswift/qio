<?php
namespace qio\Directory {
    use qio;
    
    class Stream extends qio\Stream\Base {
        /**
         * Stores stream directory
         * @var \qio\Directory
         */
        protected $directory;
        
        /**
         * Default directory stream constructor
         * @param qio\Directory $directory
         */
        public function __construct(qio\Directory $directory) {
            $this->directory = $directory;
        }
        
        /**
         * Retrieve stream directory
         * @return \qio\Directory
         */
        public function getDirectory() {
            return $this->directory;
        }
        
        /**
         * Open up directory stream
         * @return boolean
         */
        public function open() {
            $this->setPointer(opendir($this->directory->getPath()));
            return parent::open();
        }
        
        /**
         * Close down directory stream
         */
        public function close() {
            if($this->isOpen()) {
                closedir($this->getPointer());
                parent::close();
            }
        }
    }
}