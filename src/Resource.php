<?php
namespace qio {
    interface Resource {
        /**
         * All resources must provide a path
         * @return string
         */
        function getPath();
        
        /**
         * All resources must provide a default mode, modes are located in the \qio\Stream\Mode enum
         */
        function getDefaultMode();
    }
}