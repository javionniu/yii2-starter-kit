<?php
/**
 * Yii2 Shortcuts
 * @author Eugene Terentev <eugene@terentev.net>
 * -----
 * This file is just an example and a place where you can add your own shortcuts,
 * it doesn't pretend to be a full list of available possibilities
 * -----
 */

/**
 * @return int|string
 */
function getMyId()
{
    return Yii::$app->user->getId();
}

/**
 * @param string $view
 * @param array $params
 * @return string
 */
function render($view, $params = [])
{
    return Yii::$app->controller->render($view, $params);
}

/**
 * @param $url
 * @param int $statusCode
 * @return \yii\web\Response
 */
function redirect($url, $statusCode = 302)
{
    return Yii::$app->controller->redirect($url, $statusCode);
}

/**
 * @param $form \yii\widgets\ActiveForm
 * @param $model
 * @param $attribute
 * @param array $inputOptions
 * @param array $fieldOptions
 * @return string
 */
function activeTextinput($form, $model, $attribute, $inputOptions = [], $fieldOptions = [])
{
    return $form->field($model, $attribute, $fieldOptions)->textInput($inputOptions);
}

/**
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function env($key, $default = false) {

    $value = getenv($key);

    if ($value === false) {
        return $default;
    }

    switch (strtolower($value)) {
        case 'true':
        case '(true)':
            return true;

        case 'false':
        case '(false)':
            return false;
    }

    return $value;
}

/**
 * 检测是否在微信客户端内的浏览器
 * @return boolean [是否微信客户端浏览器]
 */
function is_weixin()
{ 
    if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
        return true;
    }
    return false;
}

/**
 * 是否移动端访问访问
 *
 * @return bool
 */
function is_mobile()
{ 
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
    {
        return true;
    } 
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
    { 
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    } 
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT']))
    {
        $clientkeywords = ['nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile',
            'MicroMessenger',
            ]; 
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
        {
            return true;
        } 
    } 
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT']))
    { 
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        } 
    } 
    return false;
}
/**
 * 手机号格式验证
 * @param  integer $phone [description]
 * @return boolean        [description]
 */
function is_phone($phone=0)
{
    return preg_match("/^1[34578]{1}\d{9}$/",$phone);
}

/**
 * 邮箱格式验证
 * @param  integer $phone [description]
 * @return boolean        [description]
 */
function is_email($email='')
{
    return preg_match("/^[A-Za-z0-9-_.+%]+@[A-Za-z0-9-.]+\.[A-Za-z]{2,4}$/",$email);
}

/**
 * 身份证格式验证
 * @param  integer $id_number [description]
 * @return boolean        [description]
 */
function isCreditNo($vStr)
{
    $vCity = array(
        '11','12','13','14','15','21','22',
        '23','31','32','33','34','35','36',
        '37','41','42','43','44','45','46',
        '50','51','52','53','54','61','62',
        '63','64','65','71','81','82','91'
    );

    if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return false;

    if (!in_array(substr($vStr, 0, 2), $vCity)) return false;

    $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
    $vLength = strlen($vStr);

    if ($vLength == 18)
    {
        $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
    } else {
        $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
    }

    if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
    if ($vLength == 18)
    {
        $vSum = 0;

        for ($i = 17 ; $i >= 0 ; $i--)
        {
            $vSubStr = substr($vStr, 17 - $i, 1);
            $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
        }

        if($vSum % 11 != 1) return false;
    }

    return true;
}

/**
 * 获取字符串中的图片地址
 * @param  [type] $str [description]
 * @return [type]      [description]
 */
function getImgs($str) {
    $reg = '/((http|https)(.*?)(JPG|jpg|jpeg|JPEG|gif|png))/';
    $matches = $data = array();
    preg_match_all($reg, $str, $matches);
    //var_dump(1323);exit;
    foreach ($matches[0] as $value) {
        $data[] = $value;
    }
    return $data;
}
/**
 * 字符串截取
 * @param  [type]  $string [description]
 * @param  integer $length [description]
 * @param  string  $sign   [description]
 * @param  string  $char   [description]
 * @return [type]          [description]
 */
function substr_auto($string, $length=200, $sign = ' ...', $char='UTF-8'){
    if(empty($string)){ return;}
    return mb_strimwidth($string, 0,$length, $sign, $char);
    
    if(mb_strlen($string) > $length){
        return mb_substr($string, 0, $length, $char).$sign;
    }else{
        return $string;
    }
}

/**
 * 亿美软通
 * 平台网址：http://qiyechaxun.emay.cn:8000
 * 6SDK-EMY-6688-JCUML
 * 首页：http://www.emay.cn/  亿美软通
 * 亿美发送短信规则
 *   1 短信格式必须是【签名】+内容,签名在前。
 *   2 【签名】必须是3到8个字，不能为空。
 *   3 十分钟每个号码只能发送3次。
 *   4 每个手机号每天最多发送20条短信。
 *   5 短信字数最多500字，数字、字符都算一个字
 *   注：在测试发送短信时可将测试号码发给亿美服务人员解除第3条和第4条的限制
 * @return [type] [description]
 */
function ymSms($data)
{
    //Init curl
    $curl = new \linslin\yii2\curl\Curl();

    //get http://example.com/
    // $response = $curl->get('http://www.163.com/');

    $host = 'http://sdk4rptws.eucp.b2m.cn:8080/sdkproxy/sendsms.action?';
    $preSign = '【光大学校】 ';
    $params['cdkey'] = '6SDK-EMY-6688-JCUML';
    $params['password'] = '128921';
    $params['message'] = $preSign.$data['message'];
    $params['phone'] = $data['phone'];

    $url = $host.http_build_query($params);
    // echo $url;
    return $curl->get($url);
}