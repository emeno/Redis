<?php
namespace Emeno\RedisUtility;
use Emeno\Redis\Redis;
class RedisScalar
{
    protected $redisObj = null;
    protected $expire;
    public function __construct($expire = 3600, $redis_obj = null)
    {
        if(!is_null($redis_obj)){
            $this->redisObj = $redis_obj;
        }
        else{
            $this->redisObj = new Redis();
        }
        $this->setExpire($expire);
    }
    public function getExpire()
    {
        return $this->expire;
    }
    public function setExpire($val)
    {
        $val = intval($val);
        if($val <= 0){
            throw new RedisCustomException('Expire must be > 0');
        }
        $this->expire = $val;
    }
    final public function connect($server, $port, $pass)
    {
        $this->redisObj->connect($server.':'.$port);
        $this->redisObj->auth($pass);
    }
    final public function close()
    {
        $this->redisObj->close();
    }
    public function set($key, $val)
    {
        $this->redisObj->setEx($key, $this->getExpire(), $val);
    }
    final public function get($key)
    {
        return $this->redisObj->get($key);
    }
    final public function drop($key)
    {
        $this->redisObj->delete($key);
    }
}
?>