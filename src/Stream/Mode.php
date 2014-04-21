<?php
namespace qio\Stream {
    use kenum;
    
    class Mode extends kenum\Enum\Base
    {
        const Read = 'r';
        const ReadWrite = 'r+';
        
        const WriteOnly = 'w';
        const ReadWriteTruncate = 'w+';
        
        const WriteOnlyFromEnd = 'a';
        const ReadWriteFromEnd = 'a+';
        
        const WriteOnlyEnsureNew = 'x';
        const ReadWriteEnsureNew = 'x+';
        
        const WriteNoLock = 'c';
        const ReadWriteLock = 'c+';
    }
}