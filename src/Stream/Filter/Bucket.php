<?php
namespace qio\Stream\Filter {
    class Bucket {
        /**
         * Alias for @stream_bucket_prepend
         * @param resource $brigade
         * @param resource $bucket
         */
        public static function prepend($brigade, $bucket) {
            return stream_bucket_prepend($brigade,$bucket);
        }
        
        /**
         * Alias for @stream_bucket_create
         * @param resource $stream
         * @param string $buffer
         */
        public static function create($stream, $buffer) {
            return stream_bucket_new($stream, $buffer);
        }
        
        /**
         * Alias for @stream_bucket_append
         * @param resource $brigade
         * @param resource $bucket
         */
        public static function append($brigade, $bucket) {
            return stream_bucket_append($brigade, $bucket);
        }
        
        /**
         * Alias for @stream_bucket_make_writable
         * @param resource $brigade
         */
        public static function make_writable($brigade) {
            return stream_bucket_make_writeable($brigade);
        }
    }
}