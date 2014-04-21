<?php
namespace qio {
    use kfiltr;
    
    interface Stream {
        const STATE_OPEN = 'open';
        const STATE_CLOSE = 'close';

        /**
         * Retrieve stream context
         */
        public function getContext();

        /**
         * Update stream context
         */
        public function setContext(Context $context);

        /**
         * Retrieve stream pointer or (resource)
         */
        public function getPointer();

        /**
         * Update stream pointer or (resource)
         */
        public function setPointer($pointer);
        
        /**
         * Open stream
         */
        public function open();
        
        /**
         * Close stream
         */
        public function close();

        /**
         * Check stream open
         */
        public function isOpen();

        /**
         * Retrieve stream default encoding
         */
        public function getDefaultEncoding();
    }
}