<?php 
namespace qio\Asset {
   
    /**
     * State interface
     */
    interface State {
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
