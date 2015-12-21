<?php
namespace Emeno\RedisUtility;
class RedisArray extends RedisScalar
{
    protected $arrKey;
    public function __construct($arrKey = 'next_key', $expire = 36000, $redis_obj = null)
    {
        parent::__construct($expire, $redis_obj);
        $this->setArrKey($arrKey);
    }
    public function getArrKey()
    {
        return $this->arrKey;
    }
    public function setArrKey($val)
    {
        $val = trim($val);
        if(strlen($val) <= 0){
            throw new RedisCustomException('Array Redis superkey is not empty string!');
        }
        $this->arrKey = $val;
    }
    public function getArray($ignore_json_err = true)
    {
        $result = @json_decode($this->get($this->arrKey), true);
        if(!$ignore_json_err){
            if(!is_array($result)){
                throw new RedisCustomException('Redis structure with superkey='.($this->arrKey).' is not an array');
            }
        }
        return is_array($result) ? $result : array();
    }
    public function findByKey($key)
    {
        $arr = $this->getArray();
        return array_key_exists($key, $arr) ? $arr[$key] : false;
    }
    public function set($key, $val)
    {
        if(!is_array($val)){
            throw new RedisCustomException('Setted Value is not an array in ',get_class($this).'::set');
        }
        parent::set($key, @json_encode($val));
    }
    protected function fixArray($this_arr)
    {
        $this->set(
          $this->getArrKey(),
          $this_arr
        );
    }
    public function addToArray($val, $key = false)
    {
        $this_arr = $this->getArray();
        if($key !== false && array_key_exists($key, $this_arr))
        {
            return false;
        }
        if($key === false){
            $this_arr[] = $val;
        }
        else{
            $this_arr[$key] = $val;
        }
        $this->fixArray($this_arr);
        
    }
    public function replaceInArray($val, $key = false)
    {
        $this_arr = $this->getArray();
        if(!array_key_exists($key, $this_arr))
        {
            throw new RedisCustomException('Redis array key='.$key.' not exists');
        }        
        $this_arr[$key] = $val;
        $this->fixArray($this_arr);    
    }
    public function DeleteFromArray($key)
    {
        $this_arr = $this->getArray();
        if(!isset($this_arr[$key])){
            throw new RedisCustomException('Redis array key='.$key.' not exists');
        }
        unset($this_arr[$key]);
        if(count($this_arr) <= 0){
            $this->drop($this->getArrKey());
            return;
        }
        $this->fixArray($this_arr);
    }
}
?>