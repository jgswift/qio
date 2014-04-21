<?php
namespace qio {
    interface Context {
        const CURL = 'curl';
        const FTP = 'ftp';
        const File = 'file';
        const HTTP = 'http';
        const Phar = 'phar';
        const SSL = 'ssl';
        const Socket = 'socket';
        
        /**
         * Default constructor for IO context
         * @param array $options
         * @param array $params
         */
        function __construct(array $options = [],array $params = []);

        /**
         * Creates IO context from options and data stored locally
         */
        function create();
        
        /**
         * Checks if IO context is created
         */
        function isCreated();
    }
}


