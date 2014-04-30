<?php
namespace qio\Cache {
    use observr;
    
    class Event extends observr\Event {
        /**
         * Path where item being cached is located
         * @var string
         */
        public $path;
        
        /**
         * Data to be cached
         * @var mixed
         */
        public $value;
    }
}