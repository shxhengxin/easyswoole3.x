<?php


namespace App\HttpController;

use EasySwoole\FastCache\Cache;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\Http\Request;
use EasySwoole\Http\AbstractInterface\AnnotationController;

class BaseController extends AnnotationController
{

    function index(){}

    /**
     * Created by PhpStorm.
     * @Desc:做权限认证
     * @User: shenhengxin
     * @Date: 2019/12/19
     * @Time: 17:42
     * @param string|null $action
     * @return bool|null
     */
    protected function onRequest(?string $action): ?bool
    {
        return true;
    }


    public function  onException(\Throwable $throwable): void
    {

        $this->writeJson(400,[],$throwable->getMessage());
    }

    /**
     * Created by PhpStorm.
     * @desc: 获取用户的真实IP
     * @User: shenhengxin
     * @Date: 2019/12/17
     * @Time: 11:55
     * @param string $headerName 代理服务器传递的标头名称
     * @return mixed|string
     */
    protected function clientRealIP($headerName = 'x-real-ip')
    {
        $server = ServerManager::getInstance()->getSwooleServer();
        $client = $server->getClientInfo($this->request()->getSwooleRequest()->fd);
        $clientAddress = $client['remote_ip'];

        $xri = $this->request()->getHeader($headerName);
        $xff = $this->request()->getHeader('x-forwarded-for');
        if ($clientAddress === '127.0.0.1') {
            if (!empty($xri)) {  // 如果有xri 则判定为前端有NGINX等代理
                $clientAddress = $xri[0];
            } elseif (!empty($xff)) {  // 如果不存在xri 则继续判断xff
                $list = explode(',', $xff[0]);
                if (isset($list[0])) $clientAddress = $list[0];
            }
        }
        return $clientAddress;
    }

    /**
     * Created by PhpStorm.
     * @Desc:获取当前的公网IP(从宝塔接口获取)
     * @User: shenhengxin
     * @Date: 2019/12/20
     * @Time: 16:54
     * @param mixed $default 获取失败时返回指定的值
     * @param bool $force 是否强制从宝塔获取
     * @return bool|false|string
     */
    public static function currentPublicIPAddress($default = false, $force = false)
    {
        $cache = Cache::getInstance()->get('localIPAddress');

        if (!$force && $cache && $cache['lifeTime'] > time()) {
            return $cache['localIPAddress'];
        }

        // 从宝塔获取当前的IP
        $ipAddress = file_get_contents('https://www.bt.cn/Route/getIpAddress');
        $ipAddressRegex = '/(2(5[0-5]{1}|[0-4]\d{1})|[0-1]?\d{1,2})(\.(2(5[0-5]{1}|[0-4]\d{1})|[0-1]?\d{1,2})){3}/';

        // 返回不是一个有效的IP
        if (!$ipAddress || !preg_match($ipAddressRegex, $ipAddress)) {
            return $default;
        }

        Cache::getInstance()->set('localIPAddress', [
            'lifeTime' => time() + 60 * 60 * 24,  // 86400 = 1day
            'localIPAddress' => $ipAddress
        ]);

        return $ipAddress;
    }

    /**
     * Created by PhpStorm.
     * @Desc:获取当前的域名
     * @User: shenhengxin
     * @Date: 2019/12/20
     * @Time: 16:53
     * @param Request $request
     * @param bool $default 获取失败时返回指定的值
     * @param string $headerName
     * @return mixed
     */
    public static function checkCurrentDomain(Request $request, $default = false, $headerName = 'host')
    {
        $server = ServerManager::getInstance()->getSwooleServer();
        $client = $server->getClientInfo($request->getSwooleRequest()->fd);
        $clientAddress = $client['remote_ip'];
        if ($clientAddress === '127.0.0.1') {
            if (!empty($xri)) {  // 如果有xri 则判定为前端有NGINX等代理
                $clientAddress = $xri[0];
            } elseif (!empty($xff)) {  // 如果不存在xri 则继续判断xff
                $list = explode(',', $xff[0]);
                if (isset($list[0])) $clientAddress = $list[0];
            }
        }
        return $clientAddress;
    }


}