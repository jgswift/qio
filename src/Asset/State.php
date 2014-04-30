<?php 
namespace qio\Asset {
   
    /**
     * State interface
     */
    interface State {
        /**
         * Retrieve asset cache
         * @return \qio\Directory\Cache
         */
        function getCache();

        /**
         * Perform cache-wide update of all resources
         * @return void
         */
        public function updateAll($path=null,$recursive=false);
         
        /**
         * Perform selective update on specific resources
         * @return void
         */
        public function update($paths,$recursive=false);
    }
}
