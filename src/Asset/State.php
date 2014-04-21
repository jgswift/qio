<?php 
namespace qio\Asset {
   
    /**
     * State interface
     */
    interface State {
        /**
         * Default constructor signature for asset state
         */
        function __construct(\qio\Cache $cache);
        
        /**
         * Retrieve asset cache
         */
        function getCache();

        /**
         * Perform cache-wide update of all resources
         */
        public function updateAll($path=null,$recursive=false);
         
        /**
         * Perform selective update on specific resources
         */
        public function update($paths,$recursive=false);
    }
}
