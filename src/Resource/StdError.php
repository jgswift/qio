<?php
namespace qio\Resource {
    /**
     * Helper class to maintain uniform resource implementation
     */
    final class StdError implements \qio\Resource {
        public function getPath() {
            return 'STDERR';
        }
        
        public function getDefaultMode() {
            return \qio\Stream\Mode::WriteOnly;
        }
    }
}