<?php
namespace qio\Resource {
    /**
     * Helper class to maintain uniform resource implementation
     */
    final class StdIn implements \qio\Resource {
        /**
         * Default path
         * @return string
         */
        public function getPath() {
            return 'STDIN';
        }

        /**
         * Default mode
         * @return string
         */
        public function getDefaultMode() {
            return \qio\Stream\Mode::Read;
        }
    }
}