<?php
namespace qio {
    interface Stream {
        const STATE_OPEN = 'open';
        const STATE_CLOSE = 'close';

        /**
         * Retrieve stream context
         * @return Context
         */
        public function getContext();

        /**
         * Update stream context
         * @return Context
         */
        public function setContext(Context $context);

        /**
         * Retrieve stream pointer or (resource)
         * @return resource
         */
        public function getPointer();

        /**
         * Update stream pointer or (resource)
         * @return resource
         */
        public function setPointer($pointer);
        
        /**
         * Open stream
         * @return boolean
         */
        public function open();
        
        /**
         * Close stream
         * @return null|boolean
         */
        public function close();

        /**
         * Check stream open
         * @return boolean
         */
        public function isOpen();

        /**
         * Retrieve stream default encoding
         */
        public function getDefaultEncoding();
    }
}