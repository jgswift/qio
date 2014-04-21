<?php
namespace qio\Directory\Cache\Rule {
    use qio;
    
    class LastModified extends qio\Cache\Rule\Base
    {
        /**
         * Rule source asset
         * @var \qio\Asset 
         */
        protected $source;
        
        /**
         * Rule target asset
         * @var \qio\Asset
         */
        protected $target;

        /**
         * Default constructor for LastModified rule
         * @param qio\Asset $source
         * @param qio\Asset $target
         */
        function __construct(qio\Asset $source, qio\Asset $target = null) {
            $this->source = $source;
            if(!is_null($target)) {
                $this->target = $target;
            }
        }
        
        /**
         * Retrieve source asset
         * @return \qio\Asset 
         */
        function getSource() {
            return $this->source;
        }
        
        /**
         * Retrieve target asset
         * @return \qio\Asset 
         */
        function getTarget() {
            return $this->target;
        }

        /**
         * Attach rule validation
         * @param qio\Cache $cache
         */
        function execute(qio\Cache $cache) {
            $sourcePath = $this->source->getPath();
            if(empty($this->target)) {
                return;
            }
            
            $cachePath = $this->target->getPath();

            if(!is_file($sourcePath) ||
               !is_file($cachePath)) {
                return;
            }
            
            $sourceLastModified = $rule->source->getLastModified();
            $targetLastModified = $rule->target->getLastModified();
            
            $cache->attach('has', function($sender, $e) use($sourceLastModified,$targetLastModified) {
                if($sourceLastModified > $targetLastModified) {
                    $e->cancel();
                    return false;
                }

                return true;
            });
        }
    }
}