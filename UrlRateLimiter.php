<?php
namespace api\models;


use common\models\User;
use yii\filters\RateLimitInterface;

/**
 * Class UrlRateLimiter
 * Класс регулирует кол-во разрешенных запросов в с секунду для неавторизованных пользователей,
 * использующих API, а также для операторов, которые обрабатывают заказы в iframe
 *
 * Ключ для кеша строится по URL-запроса
 *
 * @package api\models
 */
class UrlRateLimiter extends User implements RateLimitInterface
{
    public $rateLimit = 6;
    public $allowance;
    public $allowance_updated_at;
    
    /**
     * Кол-во разрешенных запросов в секунду
     *
     * @param \yii\web\Request $request
     * @param \yii\base\Action $action
     * @return array
     */
    public function getRateLimit($request, $action)
    {
        // rateLimit - кол-во
        // 10 - это секунды
        return [$this->rateLimit, 10];
    }
    
    public function loadAllowance($request, $action)
    {
        $cache = \Yii::$app->cache;
        $key = sha1(serialize($request->url));
        return [
            $cache->get('user.ratelimit.ip.allowance.' . $key),
            $cache->get('user.ratelimit.ip.allowance_updated_at.' . $key),
        ];
    }
    
    /**
     * Метод сохранит в кеш
     * @param \yii\web\Request $request
     * @param \yii\base\Action $action
     * @param int $allowance
     * @param int $timestamp
     */
    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $cache = \Yii::$app->cache;
        $key = sha1(serialize($request->url));
        
        $cache->set('user.ratelimit.ip.allowance.' . $key, $allowance);
        $cache->set('user.ratelimit.ip.allowance_updated_at.' . $key, $timestamp);
        
    }
}
