<?php
/**
 * Created by PhpStorm.
 *
 * @Author     : screamwolf@likingfit.com
 * @CreateTime 2018/11/5 10:43:40
 */

namespace component\utils;

class utils
{

    /**
     * @param $serviceId
     * @param $nodeId
     * @param $timestamp
     * @param $requestId
     *
     * @return string
     * @CreateTime 2018/11/5 15:31:03
     * @Author     : xingxiaohe@styd.cn
     */
    public static function getSign($serviceId, $nodeId, $timestamp, $requestId)
    {
        $tmp = [$serviceId, $nodeId, $timestamp, $requestId];
        sort($tmp);

        return sha1(implode('', $tmp));
    }

    /**
     * @param $key
     *
     * @return string
     * @CreateTime 2019-01-15 10:09:21
     * @Author     : xingxiaohe@styd.cn
     */
    public static function toSnakeCase($key)
    {
        return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $key));
    }

    /**
     * 下划线转驼峰
     * @param $uncamelized_words
     * @param string $separator
     * @return string
     *  @Author     : liutong@styd.cn
     */
    public static function toCamelize($uncamelized_words, $separator='_')
    {
        $uncamelized_words = $separator. str_replace($separator, " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator );
    }
}