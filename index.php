<?php

function getCaptcha($dev){

$curl = curl_init();

curl_setopt_array($curl,[

CURLOPT_URL => 'https://www.asiacell.com/api/v1/captcha?lang=en',

CURLOPT_RETURNTRANSFER => true,

CURLOPT_POST => 1,

CURLOPT_HTTPHEADER => explode("\n",'Host: www.asiacell.com

Accept: application/json, text/plain, */*

X-TS-AJAX-Request: true

Accept-Language: en-US,en;q=0.9

Accept-Encoding: gzip, deflate, br

Content-Type: application/json

Origin: https://www.asiacell.com

DeviceID: '.$dev.'

User-Agent: Mozilla/5.0 (iPad; CPU OS 15_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.2 Mobile/15E148 Safari/604.1

Referer: https://www.asiacell.com/en/personal/my-account/login?redirect=%2Fpersonal%2Fmy-account

Content-Length: 2

Connection: keep-alive

'),

CURLOPT_CUSTOMREQUEST => 'POST',

CURLOPT_POSTFIELDS => '{}',

CURLOPT_ENCODING => 'gzip'

]);

$response = curl_exec($curl);

$link1 = json_decode($response,true)['captcha']['originSource'];

$link2 = json_decode($response,true)['captcha']['resourceUrl'];

$link = "$link1".$link2;

return $link;

}

function Login($dev,$number,$cap){

$curl = curl_init();

curl_setopt_array($curl,[

CURLOPT_URL => 'https://www.asiacell.com/api/v1/loginV2?lang=en',

CURLOPT_POST => true,

CURLOPT_RETURNTRANSFER => true,

CURLOPT_HTTPHEADER => explode("\n",'Host: www.asiacell.com

Accept: application/json, text/plain, */*

X-TS-AJAX-Request: true

Accept-Language: en-US,en;q=0.9

Accept-Encoding: gzip, deflate, br

Content-Type: application/json

Origin: https://www.asiacell.com

DeviceID: '.$dev.'

User-Agent: Mozilla/5.0 (iPad; CPU OS 15_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.2 Mobile/15E148 Safari/604.1

Referer: https://www.asiacell.com/personal/my-account/login?redirect=%2Fpersonal%2Fmy-account

Content-Length: 49

Connection: keep-alive'),

CURLOPT_POSTFIELDS => '{"username":"'.$number.'","captchaCode":"'.$cap.'"}',

  ]);

  $response = curl_exec($curl);

  $json = json_decode($response,true)['nextUrl'];

  $ex1 = explode("PID=",$json)['1'];

  $ex2 = explode('"',$ex1)['0'];

  return $ex2;

}

function Verify($pid,$code,$dev){

	$curl = curl_init();	curl_setopt_array($curl,[

	CURLOPT_URL => 'https://www.asiacell.com/api/v1/smsvalidation?lang=en',

	CURLOPT_POST => true,

	CURLOPT_RETURNTRANSFER => true,

	CURLOPT_HTTPHEADER => explode("\n",'Host: www.asiacell.com

Accept: application/json, text/plain, */*

X-TS-AJAX-Request: true

Accept-Language: en-US,en;q=0.9

Accept-Encoding: gzip, deflate, br

Content-Type: application/json

Origin: https://www.asiacell.com

DeviceID: '.$dev.'

User-Agent: Mozilla/5.0 (iPad; CPU OS 15_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.2 Mobile/15E148 Safari/604.1

Referer: https://www.asiacell.com/personal/my-account/login?redirect=%2Fpersonal%2Fmy-account

Content-Length: 66

Connection: keep-alive'),

CURLOPT_POSTFIELDS => '{"PID":"'.$pid.'","passcode":"'.$code.'"}',

	]);

	$response = curl_exec($curl);

        curl_close($curl);

	return $response;

}

function TransferStart($token, $amount, $dev, $reciver){

$curl = curl_init();

curl_setopt_array($curl,[

CURLOPT_URL => 'https://www.asiacell.com/api/v1/credit-transfer/start?lang=ar',

CURLOPT_POST => true,

CURLOPT_RETURNTRANSFER => true,

CURLOPT_HTTPHEADER => array(

        "Accept: application/json, text/plain, */*",

        "Accept-Language: ar-MA,ar;q=0.9,en-US;q=0.8,en;q=0.7,zh-CN;q=0.6,zh;q=0.5",

        "Authorization: Bearer $token",

        "Connection: keep-alive",

        "Content-Type: application/json",

       "DeviceID: $dev",

        "Origin: https://www.asiacell.com",

        "Referer: https://www.asiacell.com/personal/my-account",

        "Sec-Fetch-Dest: empty",

        "Sec-Fetch-Mode: cors",

        "Sec-Fetch-Site: same-origin",

        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.54 Safari/537.36",

        "X-TS-AJAX-Request: true",

        "sec-ch-ua-mobile: ?0"

),

CURLOPT_POSTFIELDS => '{"receiverMsisdn":"'.$reciver.'","amount":"'.$amount.'"}',

]);

$response = curl_exec($curl);

return $response;

}

function DoTransfer($token,$code,$pid,$dev){

$curl = curl_init();

curl_setopt_array($curl,[

CURLOPT_URL => 'https://www.asiacell.com/api/v1/credit-transfer/do-transfer?lang=ar',

CURLOPT_POST => true,

CURLOPT_RETURNTRANSFER => true,

CURLOPT_HTTPHEADER =>   array(

   "Accept: application/json, text/plain, */*",

   "Accept-Language: ar-MA,ar;q=0.9,en-US;q=0.8,en;q=0.7,zh-CN;q=0.6,zh;q=0.5",

   "Authorization: Bearer $token",

   "Connection: keep-alive",

   "Content-Type: application/json",

   "DeviceID: $dev",

   "Origin: https://www.asiacell.com",

   "Referer: https://www.asiacell.com/personal/my-account",

   "Sec-Fetch-Dest: empty",

   "Sec-Fetch-Mode: cors",

   "Sec-Fetch-Site: same-origin",

   "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.54 Safari/537.36",

   "X-TS-AJAX-Request: true",

   "sec-ch-ua-mobile: ?0",

),

CURLOPT_POSTFIELDS => '{"PID":"'.$pid.'","passcode":"'.$code.'"}',

]);

$response = curl_exec($curl);

curl_close($curl);

return $response;

}

function Vouch($voucher,$token,$dev){

	$curl = curl_init();

	curl_setopt_array($curl,[

	CURLOPT_URL => 'https://www.asiacell.com/api/v1/top-up?lang=ar',

	CURLOPT_POST => true,

	CURLOPT_RETURNTRANSFER => true,

	CURLOPT_HTTPHEADER =>explode("\n",'Accept: application/json, text/plain, */*

Authorization: Bearer '.$token.'

Content-Type: application/json;charset=UTF-8

DeviceID: '.$dev.'

Referer: https://www.asiacell.com/personal/my-account

sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="101", "Microsoft Edge";v="101"

sec-ch-ua-mobile: ?0

sec-ch-ua-platform: "Windows"

User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.64 Safari/537.36 Edg/101.0.1210.53

X-TS-AJAX-Request: true

'),

CURLOPT_POSTFIELDS =>'{"iccid": "","msisdn": "", "rechargeType": 1,"voucher": "'.$voucher.'"}']);

	$response = curl_exec($curl);

        curl_close($curl);

	return $response;

}

/*

function GUID(){

    if (function_exists('com_create_guid') === true){

        return trim(com_create_guid(), '{}');

    }

    }

*/

class EzTGException extends Exception

{

}

function bot($method,$datas=[]){

    global $token;

$url = "https://api.telegram.org/bot".$token."/".$method;

$ch = curl_init();

curl_setopt($ch,CURLOPT_URL,$url);

curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);

$res = curl_exec($ch);

if(curl_error($ch)){

var_dump(curl_error($ch));

}else{

return json_decode($res);

}

}

class EzTG

{

    private $settings;

    private $offset;

    private $json_payload;

    public function __construct($settings, $base = false)

    {

        $this->settings = array_merge(array(

      'endpoint' => 'https://api.telegram.org',

      'token' => '1234:abcd',

      'callback' => function ($update, $EzTG) {

          echo 'no callback' . PHP_EOL;

      },

      'objects' => true,

      'allow_only_telegram' => true,

      'throw_telegram_errors' => true,

      'magic_json_payload' => false

    ), $settings);

        if ($base !== false) {

            return true;

        }

        if (!is_callable($this->settings['callback'])) {

            $this->error('Invalid callback.', true);

        }

        if (php_sapi_name() === 'cli') {

            $this->settings['magic_json_payload'] = false;

            $this->offset = -1;

            $this->get_updates();

        } else {

            if ($this->settings['allow_only_telegram'] === true and $this->is_telegram() === false) {

                http_response_code(403);

                echo '403 - You are not Telegram,.,.';

                return 'Not Telegram';

            }

            if ($this->settings['magic_json_payload'] === true) {

                ob_start();

                $this->json_payload = false;

                register_shutdown_function(array($this, 'send_json_payload'));

            }

            if ($this->settings['objects'] === true) {

                $this->processUpdate(json_decode(file_get_contents('php://input')));

            } else {

                $this->processUpdate(json_decode(file_get_contents('php://input'), true));

            }

        }

    }

    private function is_telegram()

    {

        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) { //preferisco non usare x-forwarded-for xk si può spoof

            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];

        } else {

            $ip = $_SERVER['REMOTE_ADDR'];

        }

        if (($ip >= '149.154.160.0' && $ip <= '149.154.175.255') || ($ip >= '91.108.4.0' && $ip <= '91.108.7.255')) { //gram'''s ip : https://core.telegram.org/bots/webhooks

            return true;

        } else {

            return false;

        }

    }

    private function get_updates()

    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->settings['endpoint'] . '/bot' . $this->settings['token'] . '/getUpdates');

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        while (true) {

            curl_setopt($ch, CURLOPT_POSTFIELDS, 'offset=' . $this->offset . '&timeout=10');

            if ($this->settings['objects'] === true) {

                $result = json_decode(curl_exec($ch));

                if (isset($result->ok) and $result->ok === false) {

                    $this->error($result->description, false);

                } elseif (isset($result->result)) {

                    foreach ($result->result as $update) {

                        if (isset($update->update_id)) {

                            $this->offset = $update->update_id + 1;

                        }

                        $this->processUpdate($update);

                    }

                }

            } else {

                $result = json_decode(curl_exec($ch), true);

                if (isset($result['ok']) and $result['ok'] === false) {

                    $this->error($result['description'], false);

                } elseif (isset($result['result'])) {

                    foreach ($result['result'] as $update) {

                        if (isset($update['update_id'])) {

                            $this->offset = $update['update_id'] + 1;

                        }

                        $this->processUpdate($update);

                    }

                }

            }

        }

    }

    public function processUpdate($update)

    {

        $this->settings['callback']($update, $this);

    }

    protected function error($e, $throw = 'default')

    {

        if ($throw === 'default') {

            $throw = $this->settings['throw_telegram_errors'];

        }

        if ($throw === true) {

            throw new EzTGException($e);

        } else {

            echo 'Telegram error: ' . $e . PHP_EOL;

            return array(

        'ok' => false,

        'description' => $e

      );

        }

    }

    public function newKeyboard($type = 'keyboard', $rkm = array('resize_keyboard' => true, 'keyboard' => array()))

    {

        return new EzTGKeyboard($type, $rkm);

    }

    public function __call($name, $arguments)

    {

        if (!isset($arguments[0])) {

            $arguments[0] = array();

        }

        if (!isset($arguments[1])) {

            $arguments[1] = true;

        }

        if ($this->settings['magic_json_payload'] === true and $arguments[1] === true) {

            if ($this->json_payload === false) {

                $arguments[0]['method'] = $name;

                $this->json_payload = $arguments[0];

                return 'json_payloaded'; //xd

            } elseif (is_array($this->json_payload)) {

                $old_payload = $this->json_payload;

                $arguments[0]['method'] = $name;

                $this->json_payload = $arguments[0];

                $name = $old_payload['method'];

                $arguments[0] = $old_payload;

                unset($arguments[0]['method']);

                unset($old_payload);

            }

        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->settings['endpoint'] . '/bot' . $this->settings['token'] . '/' . urlencode($name));

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($arguments[0]));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($this->settings['objects'] === true) {

            $result = json_decode(curl_exec($ch));

        } else {

            $result = json_decode(curl_exec($ch), true);

        }

        curl_close($ch);

        if ($this->settings['objects'] === true) {

            if (isset($result->ok) and $result->ok === false) {

                return $this->error($result->description);

            }

            if (isset($result->result)) {

                return $result->result;

            }

        } else {

            if (isset($result['ok']) and $result['ok'] === false) {

                return $this->error($result['description']);

            }

            if (isset($result['result'])) {

                return $result['result'];

            }

        }

        return $this->error('Unknown error', false);

    }

    public function send_json_payload()

    {

        if (is_array($this->json_payload)) {

            ob_end_clean();

            echo json_encode($this->json_payload);

            header('Content-Type: application/json');

            ob_end_flush();

            return true;

        }

    }

}

class EzTGKeyboard

{

    public function __construct($type = 'keyboard', $rkm = array('resize_keyboard' => true, 'keyboard' => array()))

    {

        $this->line = 0;

        $this->type = $type;

        if ($type === 'inline') {

            $this->keyboard = array(

        'inline_keyboard' => array()

      );

        } else {

            $this->keyboard = $rkm;

        }

        return $this;

    }

    public function add($text, $callback_data = null, $type = 'auto')

    {

        if ($this->type === 'inline') {

            if ($callback_data === null) {

                $callback_data = trim($text);

            }

            if (!isset($this->keyboard['inline_keyboard'][$this->line])) {

                $this->keyboard['inline_keyboard'][$this->line] = array();

            }

            if ($type === 'auto') {

                if (filter_var($callback_data, FILTER_VALIDATE_URL)) {

                    $type = 'url';

                } else {

                    $type = 'callback_data';

                }

            }

            array_push($this->keyboard['inline_keyboard'][$this->line], array(

        'text' => $text,

        $type => $callback_data

      ));

        } else {

            if (!isset($this->keyboard['keyboard'][$this->line])) {

                $this->keyboard['keyboard'][$this->line] = array();

            }

            array_push($this->keyboard['keyboard'][$this->line], $text);

        }

        return $this;

    }

    public function newline()

    {

        $this->line++;

        return $this;

    }

    public function done()

    {

        if ($this->type === 'remove') {

            return '{"remove_keyboard": true}';

        } else {

            return json_encode($this->keyboard);

        }

    }

}
