<?php
namespace qio\Resource {
    /**
     * Helper class to maintain uniform resource implementation
     */
    final class StdError implements \qio\Resource {
        /**
         * Default path
         * @return string
         */
        public function getPath() {
            return 'STDERR';
        }
        
        /**
         * Default mode
         * @return string
         */
        public function getDefaultMode() {
            return \qio\Stream\Mode::WriteOnly;
        }
    }
}