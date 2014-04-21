<?php
namespace qio {
    interface Asset extends \kfiltr\Interfaces\Hook, Resource
    {
        /**
         * Default asset constructor
         */
        function __construct(Cache $cache, $sourcePath = null);

        /**
         * Retrieves asset caching mechanism
         */
        function getCache();
        
        /**
         * Retrieves asset content
         */
        function getContent();

        /**
         * Updates asset content
         */
        function setContent($content);

        /**
         * Retrieves asset directory
         */
        function getDirectory();

        /**
         * Retrieves asset directory
         */
        function getPath();

        /**
         * Update asset path
         */
        function setPath($path);
        
        /**
         * Retrieve time asset was last modified
         */
        function getLastModified();

        /**
         * Updates asset timestamp
         */
        function setLastModified($lastModified);
    }
}

