<?php
namespace qio\File {
    class Asset extends \qio\Asset\Base {
        /**
         * Ensures asset modified time is up to date
         * @param string $path
         * @return string
         */
        function setPath($path) {
            if(file_exists($path)) {
                $this->lastModified = filemtime($this->sourcePath);
            }
            
            return parent::setPath($path);
        }
    }
}

