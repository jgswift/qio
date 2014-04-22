<?php
namespace qio\Resource {
    /**
     * Helper class to maintain uniform resource implementation
     */
    final class StdOut implements \qio\Resource {
        /**
         * Default path
         * @return string
         */
        public function getPath() {
            return 'STDOUT';
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