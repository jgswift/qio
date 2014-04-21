<?php
namespace qio\Stream\Wrapper {
    final class Registry {
        public static function register($protocol,$class,$flags=0) {
            return stream_wrapper_register($protocol,$class,$flags);
        }
        
        public static function unregister($protocol) {
            return stream_wrapper_unregister($protocol);
        }
        
        public static function restore($protocol) {
            return stream_wrapper_restore($protocol);
        }
    }
}