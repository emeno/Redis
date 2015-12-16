<?php
namespace Emeno\Redis;
use Emeno\ConnectManagers\File\ConnectManager;
class Redis
{
    public function get($key)
    {
        $cn = new ConnectManager();
        $cn->init();
        $res = $cn->request('');
        $cn->close();
        return $res;
    }
    public function setEx($key, $exp, $val)
    {
    }
    public function auth($pass)
    {
        
    }
    public function connect($str)
    {
    }
    public function delete($key)
    {
    }
    public function close()
    {
    }
}
?>