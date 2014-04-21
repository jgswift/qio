<?php
namespace qio\Stream {
    use qtil;
    
    abstract class Filter extends \php_user_filter {
        use qtil\Executable;
        
        /**
         * Alias for \php_user_filter::filter
         */
        abstract function filter($in, $out, &$consumed, $closing);
        
        /**
         * Provides base implementation of php_user_filter using qtil commands
         * @return mixed
         */
        public function execute() {
            return call_user_func_array([$this,'filter'],func_get_args());
        }
    }
}