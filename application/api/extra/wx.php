<?php

return [
    'app_id' => 'wx4fcf14cf392268c7',
    'app_secret' => '93d3ae40ff0e46838bdbe031586c6eec',
    //根据code获取openid
    'login_url' => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',
    // 微信获取access_token的url地址
    'access_token_url' => "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s"
];