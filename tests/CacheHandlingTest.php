<?php
namespace qio\Tests {
    class CacheHandlingTest extends qioTestCase {
        
        protected $directory;
        protected $target;
        protected $cache;
        
        protected function setUp() {
            parent::setUp();
            
            $this->directory = new \qio\Directory(__DIR__.DIRECTORY_SEPARATOR.'Mock'.DIRECTORY_SEPARATOR.'app');
            $this->target = new \qio\Directory(__DIR__.DIRECTORY_SEPARATOR.'Mock'.DIRECTORY_SEPARATOR.'cache');
            
            $this->cache = new \qio\Directory\Cache($this->directory,$this->target);
            $this->cache->enable();
        }
        
        protected function tearDown() {
            parent::tearDown();
            $path = __DIR__.DIRECTORY_SEPARATOR.'Mock'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'image.jpg';
            
            if(is_file($path)) {
                unlink($path);
            }
        }
        
        function testFileCacheUpdate() {
            $stream = new \qio\Directory\Stream($this->target);
            $reader = new \qio\Directory\Reader($stream);
            
            $state = new \qio\File\Asset\State($this->cache);
            
            $state->update([
                'image.jpg'
            ]);
            
            $list = $reader->scan();
            
            $this->assertEquals(2,count($list));
        }
        
        function testFileCacheUpdateAll() {
            $stream = new \qio\Directory\Stream($this->target);
            $reader = new \qio\Directory\Reader($stream);
            
            $state = new \qio\File\Asset\State($this->cache);
            
            $state->updateAll();
            
            $list = $reader->scan();
            
            $this->assertEquals(2,count($list));
        }
        
        function testFileCacheCRUD() {
            $user1 = new \qio\Tests\Mock\User;
            
            $this->cache->save('myuser',$user1);
            
            $user2 = $this->cache->load('myuser');
            
            $this->assertEquals($user1,$user2);
            
            $this->assertTrue($this->cache->has('myuser'));
            
            $this->cache->delete('myuser');
            
            $this->assertFalse($this->cache->has('myuser'));
            
            $this->assertTrue((strpos($this->cache->getPath('myuser'),'tests/Mock/app/myuser') !== false));
        }
        
        function testAssetCacheRules() {
            $this->assertEquals(true,$this->cache->isEnabled());
            
            $file = new \qio\File(__DIR__.DIRECTORY_SEPARATOR.'Mock'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'image.jpg');
            
            $asset = new \qio\File\Asset($this->cache, $file);
            
            $this->cache->setRules([
                new \qio\Directory\Cache\Rule\LastModified($asset)
            ]);
            
            $rules = $this->cache->getRules();
            
            $this->assertEquals(1,count($rules));
        }
    }
}