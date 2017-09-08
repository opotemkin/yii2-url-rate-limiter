<?php
namespace api\controllers;

/**
 * Class DemoController
 * @package api\controllers
 */
class DemoController extends Controller
{
    public function behaviors()
    {
        return [
            'rateLimiter' => [
                'class' => RateLimiter::className(),
                'enableRateLimitHeaders' => false, // не передавать в хедере оставш. кол-во запросов и время
                'errorMessage' => 'Слишком много запросов',
                'only' => ['index'], // Определить экшн для применения
                'user' => new UrlRateLimiter(),
            ],
        ];
    }
    ...
    
}
