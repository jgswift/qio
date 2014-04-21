<?php
namespace qio\Resource {
    /**
     * Helper class to maintain uniform resource implementation
     */
    final class StdOut implements \qio\Resource {
        public function getPath() {
            return 'STDOUT';
        }
        
        public function getDefaultMode() {
            return \qio\Stream\Mode::WriteOnly;
        }
    }
}