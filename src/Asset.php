<?php
namespace qio {
    interface Asset extends \kfiltr\Interfaces\Hook, Resource
    {
        /**
         * Default asset constructor
         * @param string $sourcePath
         * @return void
         */
        function __construct(Cache $cache, $sourcePath = null);

        /**
         * Retrieves asset caching mechanism
         * @return Cache
         */
        function getCache();
        
        /**
         * Retrieves asset content
         * @return string
         */
        function getContent();

        /**
         * Updates asset content
         * @return void
         */
        function setContent($content);

        /**
         * Retrieves asset directory
         * @return Directory
         */
        function getDirectory();

        /**
         * Retrieves asset directory
         * @return string
         */
        function getPath();

        /**
         * Update asset path
         * @return null|string
         */
        function setPath($path);
        
        /**
         * Retrieve time asset was last modified
         * @return void
         */
        function getLastModified();

        /**
         * Updates asset timestamp
         * @return void
         */
        function setLastModified($lastModified);
    }
}

