<?php
namespace qio\Resource {
    /**
     * Helper class to maintain uniform resource implementation
     */
    final class StdIn implements \qio\Resource {
        public function getPath() {
            return 'STDIN';
        }

        public function getDefaultMode() {
            return \qio\Stream\Mode::Read;
        }
    }
}