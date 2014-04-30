<?php
namespace qio\File\Asset {
    use qtil;
    
    class State implements \qio\Asset\State {
        use qtil\Factory;
        
        /**
         * Stores local state cache
         * @var \qio\Directory\Cache
         */
        protected $cache;
        
        /**
         * Default file asset constructor
         * @param \qio\Directory\Cache $cache
         */
        function __construct(\qio\Directory\Cache $cache) {
            $this->cache = $cache;
        }
        
        /**
         * Retrieve file asset cache
         * @return \qio\Directory\Cache
         */
        function getCache() {
            return $this->cache;
        }
        
        /**
         * if directory is provided instead of explicit paths
         * add all files in directory to path list
         * use recursive argument to add files in subdirectories
         * @param string $path
         * @param boolean $recursive
         * @return array
         */
        protected function scanTarget($path,$recursive=false) {
            $newpaths = [];
            $dir = $this->cache->getDestination();

            $stream = new \qio\Directory\Stream($dir);
            $reader = new \qio\Directory\Reader($stream);
            $list = $reader->scan();

            foreach($list as $item) {
                $fullItemPath = $path.DIRECTORY_SEPARATOR.$item;
                if(is_file($fullItemPath)) {
                    $newpaths[] = $fullItemPath;
                } elseif($recursive && 
                         is_dir($fullItemPath)) {
                    $newpaths = array_merge($newpaths, $this->scanTarget($fullItemPath,$recursive));
                }
            }

            return $newpaths;
        }
        
        /**
         * Aggregate interface to perform system-wide cache update
         * @param string|array $path
         * @param boolean $recursive
         * @throws \InvalidArgumentException
         */
        public function updateAll($path=null,$recursive=false) {
            if(!is_null($path)) {
                if(is_string($path)) {
                    $dir = new \qio\Directory($path);
                } elseif($path instanceof \qio\Directory) {
                    $dir = $path;
                } else {
                    throw new \InvalidArgumentException;
                }
            } else {
                $dir = $this->cache->getDirectory();
            }
            
            $path = $dir->getPath();
            
            $stream = new \qio\Directory\Stream($dir);
            $reader = new \qio\Directory\Reader($stream);
            
            if($stream->open()) {
                $files = [];
                while($value = $reader->readPath()) {
                    if(is_file($path.$value)) {
                        $files[] = $value;
                    } elseif(is_dir($path.$value) && $recursive) {
                        $files = array_merge($files, $this->updateAll(new \qio\Directory($value),$recursive));
                    }
                }
                
                $stream->close();
                $this->update($files);
            }
        }
        
        /**
         * Main interface to perform specific cache updates
         * @param string|array $paths
         * @param boolean $recursive
         * @return array
         */
        public function update($paths,$recursive=false) {
            if( !is_array( $paths )) {
                $paths = [ $paths ];
            }

            $newpaths = [];

            foreach($paths as $k => $path) {
                if(is_dir($path)) {
                    //$newpaths = array_merge($newpaths, $this->scanTarget($path,$recursive));
                } else {
                    $newpaths[] = $path;
                }
            }

            if(!empty($newpaths)) {
                $paths = $newpaths;
            }
            
            $assets = $this->cache->getAssets();
            $pCount = count($paths);
            for( $i=0; $i< $pCount;$i++)
            {
                $path = $paths[$i];
                $path = str_replace('\\',DIRECTORY_SEPARATOR,$path);
                $path = $this->cache->getDirectory()->getPath().$path;
                $path = substr($path,strlen(getcwd().DIRECTORY_SEPARATOR));
                
                $asset = new \qio\File\Asset($this->cache, $path);
                
                $assets->add($asset);

                $target = $this->cache->getTarget($asset);

                if($this->cache->isEnabled())
                {
                    $this->cache->getRules()->fromArray([
                        new \qio\Directory\Cache\Rule\LastModified($asset)
                    ]);
                    
                    $this->cache->applyRules();
                    
                    if(isset($this->cache[$path])) {
                        $this->commit($path,$target->getPath());
                    } 
                }

                $paths[$i] = $target;
            }

            return $paths;
        }
                
        /**
         * Helper method to perform save sequence
         * @param string $path
         * @param string $target
         * @throws \InvalidArgumentException
         */
        protected function commit($path,$target=null) {
            if(is_array($path) && is_null($target)) {
                foreach($path as $k => $v) {
                    $this->commit($k,$v);
                }
                
                return;
            } elseif(!is_string($path) || !is_string($target)) {
                throw new \InvalidArgumentException();
            }
            
            $fileStream = new \qio\File\Stream($path);
            $fileReader = new \qio\File\Reader($fileStream);
            if(!$fileStream->open()) {
                return;
            }
            
            $cacheData = $fileReader->readAll();
            $fileStream->close();

            if(!empty(trim($cacheData)))
            {
                $fileStream = new \qio\File\Stream($target, \qio\Stream\Mode::ReadWriteTruncate);
                $fileWriter = new \qio\File\Writer($fileStream);
                if(!$fileStream->open()) {
                    return;
                }
                
                $fileWriter->write($cacheData);
                $fileStream->close();
            }
        }
    }
}