<?php
namespace qio\Cache {
    use qio;
    
    /**
     * Rule interface
     */
    interface Rule {
        /**
         * Default constructor signature for rule
         */
        function __construct(qio\Asset $source, qio\Asset $target = null);
        
        /**
         * Rules implement execute to complete qtil\Executable
         */
        function execute(qio\Cache $cache);
        
        /**
         * Retrieve rule source
         */
        function getSource();
        
        /**
         * Retrieve rule target
         */
        function getTarget();
    }
}
