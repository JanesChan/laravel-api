<?php
/**
 * 拼多多联盟
 * User: chen
 * Date: 2020/1/22
 * Time: 19:26
 */

namespace App\Libraries\classes\DuoduoUnion;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DuoduoInterface
{
    /**
     * @var self[私有化实例]
     */
    protected static $instance;

    /**
     * 参数
     *
     * @var array
     */
    protected static $param = [];

    /**
     * 构造函数
     *
     * DuoduoInterface constructor.
     */
    public function __construct()
    {
    }

    /**
     * 实例化对象
     *
     * @param $param
     * @return DuoduoInterface
     */
    public static function getInstance($param)
    {
        self::$param = $param;

        if (!(self::$instance instanceof self)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * @param $params
     * @return string
     */
    private function signature($params) {
        ksort($params);		// 按照键名对关联数组进行升序排序
        $paramsStr = '';
        array_walk($params, function ($item, $key) use (&$paramsStr) {
            $paramsStr .= sprintf('%s%s', $key, $item);
        });

        $sign = strtoupper(md5(sprintf('%s%s%s',
            Config::baseConfig()["client_secret"],
            $paramsStr,
            Config::baseConfig()["client_secret"]
        )));

        return $sign;
    }

    /**
     * @param $method
     * @param $params
     * @param string $data_type
     * @return mixed|\Psr\Http\Message\ResponseInterface|\Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($method, $data_type = 'JSON') {
        $params['client_id'] = Config::baseConfig()["client_id"];
        $params['sign_method'] = 'md5';
        $params['type'] = $method;
        $params['data_type'] = $data_type;
        $params['timestamp'] = strval(time());
        $params['goods_id_list'] = self::$param["goods_id_list"];
        $params['sign'] = $this->signature($params);
        $client = new Client();
        try {
            $res = $client->request('POST', Config::API_URL, [
                'form_params' => $params,
                'timeout' => 1.5,
            ]);
            $res = $res->getBody();
            return $res;
        } catch(\Throwable $e) {
            Log::info('Fail to call api');
        }
    }
}