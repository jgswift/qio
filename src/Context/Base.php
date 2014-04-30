<?php
namespace qio\Context {
    use qtil;
    use observr;
    use kenum;
    
    abstract class Base extends kenum\Enum\Base implements \qio\Context {
        use qtil\ArrayAccess;
        
        const CURL = 'curl';
        const FTP = 'ftp';
        const File = 'file';
        const HTTP = 'http';
        const Phar = 'phar';
        const SSL = 'ssl';
        const Socket = 'socket';
        
        /**
         * Stores context options
         * @var \observr\Collection
         */
        public $options;
        
        /**
         * Stores context data
         * @var \observr\Collection 
         */
        protected $data;
        
        /**
         * Alias for data
         * @var \observr\Collection
         */
        public $parameters;
        
        /**
         * Default constructor for IO context
         * @param array $options
         * @param array $params
         */
        function __construct(array $options = [],array $params = []) {
            $this->options = new observr\Collection($options);
            $this->data = new observr\Collection($params);
            $this->parameters = &$this->data;
        }
    }
}


