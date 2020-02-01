<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 2020/1/28
 * Time: 10:27
 */

namespace App\Logic\V1\Admin\Robot;


use App\Http\Middleware\ClientIp;
use App\Libraries\classes\ProxyIP\GetProxyIP;
use App\Logic\V1\Admin\Base\BaseLogic;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class LoginLogic extends BaseLogic
{
    protected $uuid = '';

    protected $wxId = '';

    /**
     * 获取QRCode
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getQrcode()
    {
        $data = ["num" => 1, "type" => 2, "pro" => 440000, "city" => 440900, "yys" => 0, "port" => 1, "pack" => 81937, "ts" => 0, "ys" => 0, "cs" => 0, "lb" => 1, "sb" => 0, "pb" => 4, "mr" => 1, "regions" => ''];
        $getProxyIp = GetProxyIP::getInstance($data)->execute();
        $client = new Client();
        try {
            $res = $client->request('POST', 'http://106.15.235.187:1925/api/Login/GetQrCode', [
                'form_params' => ["getQrCode" => '{"proxyIp": "'.$getProxyIp[0]['ip'].":".$getProxyIp[0]['port'].'","proxyUserName": "zhima","proxyPassword": "zhima","deviceID": "eedebe19-958b-4687-8096-d43a96fb51a7","deviceName": "ipad"}']
            ]);
            $res = json_decode($res->getBody()->getContents(),true);
            return $res["Data"];
        } catch(\Throwable $e) {
            Log::info('Fail to call api');
        }
    }

    /**
     * 检查是否登录
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function checkLogin()
    {
        $client = new Client();
        try {
            $res = $client->request('POST', 'http://106.15.235.187:1925/api/Login/CheckLogin/'.$this->uuid, [
                'form_params' => ["uuid" => $this->uuid]
            ]);
            $res = json_decode($res->getBody()->getContents(),true);
            if ($res["Code"] == 401){
                return ["code" => $res["Code"],"message" => $res['Message']];
            }
            if ($res["Data"]["WxId"] == null){
                return ["code" => "4000","message" => "等待扫描!"];
            }
            return ["data" => $res["Data"]];
        } catch(\Throwable $e) {
            Log::info('Fail to call api');
        }
    }

    /**
     * 退出登录
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function loginOut()
    {
        $client = new Client();
        try {
            $res = $client->request('POST', 'http://106.15.235.187:1925/api/Login/LogOut/'.$this->wxId);
            $res = $res->getBody()->getContents();
            return $res;
        } catch(\Throwable $e) {
            Log::info('Fail to call api');
        }
    }

    /**
     * 检查心跳
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function heartBeat()
    {
        $client = new Client();
        try {
            $res = $client->request('POST', 'http://106.15.235.187:1925/api/Login/HeartBeat/'.$this->wxId);
            $res = $res->getBody()->getContents();
            return $res;
        } catch(\Throwable $e) {
            Log::info('Fail to call api');
        }
    }

    /**
     * 二次登录
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function twiceLogin()
    {
        $client = new Client();
        try {
            $res = $client->request('POST', 'http://106.15.235.187:1925/api/Login/TwiceLogin', [
                'form_params' => ["wxId" => $this->wxId]
            ]);
            $res = $res->getBody()->getContents();
            return $res;
        } catch(\Throwable $e) {
            Log::info('Fail to call api');
        }
    }

    /**
     * 初始化好友
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function initUser()
    {
        $client = new Client();
        try {
            $res = $client->request('POST', 'http://106.15.235.187:1925/api/Login/InitUser', [
                'form_params' => ["initMsg" => $this->initMsg]
            ]);
            $res = $res->getBody()->getContents();
            return $res;
        } catch(\Throwable $e) {
            Log::info('Fail to call api');
        }
    }

    /**
     * 初始化用户
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function newInit()
    {
        $client = new Client();
        try {
            $res = $client->request('POST', 'http://106.15.235.187:1925/api/Login/NewInit', [
                'form_params' => ["wxId" => $this->wxId]
            ]);
            $res = $res->getBody()->getContents();
            return $res;
        } catch(\Throwable $e) {
            Log::info('Fail to call api');
        }
    }


}