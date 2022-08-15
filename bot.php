<?php
function SJ($file,$content){
	return file_put_contents($file,json_encode($content,JSON_PRETTY_PRINT));
}
if(!file_exists('admin.json')){
$token = readline('- token: ');
$id = readline('- id: ');
$save['info'] = [
'token'=>$token,
'id'=>$id
];
file_put_contents('admin.json',json_encode($save),64|128|256);
}
function save($array){
file_put_contents('admin.json',json_encode($array),64|128|256);
}
$token = json_decode(file_get_contents('admin.json'),true)['info']['token'];
$id = json_decode(file_get_contents('admin.json'),true)['info']['id'];
include 'index.php';
if($id == ""){
echo "Error Id";
}
try {
 $callback = function ($update, $bot) {
  global $id;
  if($update != null){
$message = $update->message;
$text = $message->text; 
$data = $update->callback_query->data; 
$user = $update->message->from->username; 
$user2 = $update->callback_query->from->username; 
$name = $update->message->from->first_name; 
$name2 = $update->callback_query->from->first_name; 
$message_id = $message->message_id;
$mid = $update->callback_query->message->message_id; 
$chat_id = $message->chat->id; 
$chat_id1 = $update->callback_query->message->chat->id; 
$from_id = $message->from->id;
$from_id2 = $update->callback_query->message->from->id; 
$type = $update->message->chat->type;
$id = json_decode(file_get_contents('admin.json'),true)['info']['id'];
$JS1 = json_decode(file_get_contents('data.json'),true);
if($text == '/start' && $from_id == $id){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"*
مرحبا بك عزيزي المستخدم،
يمكنك التحكم في شريحة هاتفك ( الاسياسيل ) من خلال هذا البوت.
*",
'parse_mode'=>'markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'تسجيل الدخول','callback_data'=>'login']],
[['text'=>'لوحة التحكم','callback_data'=>'portal']],
]
])
]);
}
if($text && $JS1[$chat_id]['status'] == 'Number'){
$JS1[$chat_id]['Number'] = $text;
SJ('data.json',$JS1);
$gg = bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
يرجى الآنتظار قليلاً.
",
]);
sleep(1);
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$gg->result->message_id,
]);
$getCaptcha = getCaptcha($chat_id);
bot('sendPhoto',[
'chat_id'=>$chat_id,
'photo'=>$getCaptcha,
'caption'=>"
أرسل رمز التحقق الموجود في الصورة اعلاه.
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id]['status'] = 'Captcha';
SJ('data.json',$JS1);
return false;
}
if($text && $JS1[$chat_id]['status'] == 'Captcha'){
$gg = bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
يرجى الآنتظار قليلاً.
",
]);
sleep(1);
$number = $JS1[$chat_id]['Number'];
$login = Login($chat_id,$number,$text);
bot('editMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$gg->result->message_id,
'text'=>"
أرسل رمز تسجيل الدخول الذي تم أرساله الى هاتفك.
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id]['pid'] = $login;
SJ('data.json',$JS1);
$JS1[$chat_id]['status'] = 'SMScode';
SJ('data.json',$JS1);
return false;
}
if($text != '/start' && $JS1[$chat_id]['status'] == 'SMScode'){
$number = $JS1[$chat_id]['Number'];
$pid = $JS1[$chat_id]['pid'];
$verify = json_decode(Verify($pid,$text,$chat_id),true);
sleep(5);
if($verify['success'] == true){
$token =$verify['access_token'];
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تم تسجيل الدخول في هذا الهاتف 
( $number )",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'لوحة التحكم','callback_data'=>'portal']],
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id]['status'] = null;
SJ('data.json',$JS1);
$JS1[$chat_id]['token'] = $token;
SJ('data.json',$JS1);
}elseif($verify['success'] != true){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"هناك خطب ما، حاول مجدداً في وقت لاحق.",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id]['status'] = null;
SJ('data.json',$JS1);
}
}
if($text && $JS1[$chat_id]['status'] == 'TransferTo'){
$JS1[$chat_id]['TransNumber'] = $text;
SJ('data.json',$JS1);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text' => "أرسل عدد الرصيد المُراد أرسالهُ بهذهِ الصيغة:

25000",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id]['status'] = 'AmountTransfer';
SJ('data.json',$JS1);
return false;
}
if($text && $JS1[$chat_id]['status'] == 'AmountTransfer'){
$JS1[$chat_id]['TransferAmount'] = $text;
SJ('data.json',$JS1);
$TransTo = $JS1[$chat_id]['TransNumber'];
bot('sendMessage',[
'chat_id'=>$chat_id,
'text' => "سيتم تحويل ( ".$text." ) ديناراً الى هذا الرقم ( ".$TransTo." )،

هل آنت متآكد من هذهِ العملية ؟",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'نعم، أنا متآكد','callback_data'=>'sure']],
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
}
if($text && $JS1[$chat_id]['status'] == 'confTrans'){
$number = $JS1[$chat_id]['TransNumber'];
$token = $JS1[$chat_id]['token'];
$pid = $JS1[$chat_id]['pid'];
$amount = $JS1[$chat_id]['TransferAmount'];
$Conf = json_decode(DoTransfer($token,$text,$pid,$chat_id),true);
sleep(5);
if($Conf['success'] == true){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
تم تحويل الرصيد بنجاح
رقم المستلم : ( $number )
العدد : ( $amount )
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id]['status'] = 'null';
SJ('data.json',$JS1);
}elseif($Conf['success'] != true){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"هناك خطب ما، حاول مجدداً في وقت لاحق.",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id]['status'] = null;
SJ('data.json',$JS1);
}
}
if($data == 'login'){
bot('editMessageText',[
'chat_id'=>$chat_id1,
'message_id'=>$mid,
'text'=>"
حسناً، قم بأرسال رقم الهاتف بهذهِ الصيغة:
077********
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id1]['status'] = 'Number';
SJ('data.json',$JS1);
}
if($data == 'portal'){
$number = $JS1[$chat_id1]['Number'];
$JS1[$chat_id1]['status'] = 'null';
SJ('data.json',$JS1);
bot('editMessageText',[
'chat_id'=>$chat_id1,
'message_id'=>$mid,
'text' => "
اهلاً بك في لوحة التحكم 

الرقم المستخدم ( $number )

يمكنك تحويل و تعبئه الرصيد هنا
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'تعبئة الرصيد','callback_data'=>'charge'],['text'=>'تحويل الرصيد','callback_data'=>'transfer']],
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
}
if($data == 'transfer'){
bot('editMessageText',[
'chat_id'=>$chat_id1,
'message_id'=>$mid,
'text' => "حسناً، قم بأرسال رقم الهاتف المراد تحويل الرصيد لهُ بهذهِ الصيغة:

077********",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id1]['status'] = 'TransferTo';
SJ('data.json',$JS1);
}
if($data == 'sure'){
$token = $JS1[$chat_id1]['token'];
$reciver = $JS1[$chat_id1]['TransNumber'];
$amount = $JS1[$chat_id1]['TransferAmount'];
$Start1 = TransferStart($token,$amount,$chat_id1,$reciver);
$Start = json_decode($Start1,true);
sleep(5);
$pid = $Start['PID'];
$JS1[$chat_id1]['pid'] = $pid;
SJ('data.json',$JS1);
if($Start['success'] == true){
bot('editMessageText',[
'chat_id'=>$chat_id1,
'message_id'=>$mid,
'text'=>"أرسل رمز التحقق الذي تم أرساله الى هاتفك",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id1]['status'] = 'confTrans';
SJ('data.json',$JS1);
}elseif($Start['success'] != true){
bot('editMessageText',[
'chat_id'=>$chat_id1,
'message_id'=>$mid,
'text'=>"هناك خطب ما، حاول مجدداً في وقت لاحق.",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id1]['status'] = null;
SJ('data.json',$JS1);    
}
}
if($data == 'charge'){
bot('editMessageText',[
'chat_id'=>$chat_id1,
'message_id'=>$mid,
'text'=>"حسناً، قم بأرسال رقم بطاقة التعبئة",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id1]['status'] = 'charge';
SJ('data.json',$JS1);
}
if($text != '/start' && $JS1[$chat_id]['status'] == 'charge'){
$JS1[$chat_id]['vouch'] = $text;
SJ('data.json',$JS1);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"قم بتآكيد الآمر رجاءً",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'نعم ، انا متآكد','callback_data'=>'surec']],
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id]['status'] = null;
SJ('data.json',$JS1);
}
if($data == 'surec'){
$token = $JS1[$chat_id1]['token'];
$vouch = $JS1[$chat_id1]['vouch'];
$do = json_decode(Vouch($vouch,$token,$chat_id1),true);
sleep(5);
if($do['success'] == true){
bot('editMessageText',[
'chat_id'=>$chat_id1,
'message_id'=>$mid,
'text'=>"تم تعبئة البطاقة بنجاح،
يمكنك أرسال رقم بطاقة آخر لتعبئتهُ.",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
}elseif($do['success'] != true){
bot('editMessageText',[
'chat_id'=>$chat_id1,
'message_id'=>$mid,
'text'=>"هناك خطب ما، حاول مجدداً في وقت لاحق.",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
}
}
if($data == 'back'){
bot('editMessageText',[
'chat_id'=>$chat_id1,
'message_id'=>$mid,
'text'=>"*
مرحبا بك عزيزي المستخدم،
يمكنك التحكم في شريحة هاتفك ( الاسياسيل ) من خلال هذا البوت.
*",
'parse_mode'=>'markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'تسجيل الدخول','callback_data'=>'login']],
[['text'=>'لوحة التحكم','callback_data'=>'portal']],
]
])
]);
$JS1[$chat_id1]['status'] = 'null';
SJ('data.json',$JS1);
}
}
    };
         $bot = new EzTG(array('throw_telegram_errors'=>true,'token' => $token, 'callback' => $callback));
  }
    catch(Exception $e){
 echo $e->getMessage().PHP_EOL;
 sleep(1);
}

<?php
function SJ($file,$content){
	return file_put_contents($file,json_encode($content,JSON_PRETTY_PRINT));
}
if(!file_exists('admin.json')){
$token = readline('- token: ');
$id = readline('- id: ');
$save['info'] = [
'token'=>$token,
'id'=>$id
];
file_put_contents('admin.json',json_encode($save),64|128|256);
}
function save($array){
file_put_contents('admin.json',json_encode($array),64|128|256);
}
$token = json_decode(file_get_contents('admin.json'),true)['info']['token'];
$id = json_decode(file_get_contents('admin.json'),true)['info']['id'];
include 'index.php';
if($id == ""){
echo "Error Id";
}
try {
 $callback = function ($update, $bot) {
  global $id;
  if($update != null){
$message = $update->message;
$text = $message->text; 
$data = $update->callback_query->data; 
$user = $update->message->from->username; 
$user2 = $update->callback_query->from->username; 
$name = $update->message->from->first_name; 
$name2 = $update->callback_query->from->first_name; 
$message_id = $message->message_id;
$mid = $update->callback_query->message->message_id; 
$chat_id = $message->chat->id; 
$chat_id1 = $update->callback_query->message->chat->id; 
$from_id = $message->from->id;
$from_id2 = $update->callback_query->message->from->id; 
$type = $update->message->chat->type;
$id = json_decode(file_get_contents('admin.json'),true)['info']['id'];
$JS1 = json_decode(file_get_contents('data.json'),true);
if($text == '/start' && $from_id == $id){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"*
مرحبا بك عزيزي المستخدم،
يمكنك التحكم في شريحة هاتفك ( الاسياسيل ) من خلال هذا البوت.
*",
'parse_mode'=>'markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'تسجيل الدخول','callback_data'=>'login']],
[['text'=>'لوحة التحكم','callback_data'=>'portal']],
]
])
]);
}
if($text && $JS1[$chat_id]['status'] == 'Number'){
$JS1[$chat_id]['Number'] = $text;
SJ('data.json',$JS1);
$gg = bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
يرجى الآنتظار قليلاً.
",
]);
sleep(1);
bot('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$gg->result->message_id,
]);
$getCaptcha = getCaptcha($chat_id);
bot('sendPhoto',[
'chat_id'=>$chat_id,
'photo'=>$getCaptcha,
'caption'=>"
أرسل رمز التحقق الموجود في الصورة اعلاه.
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id]['status'] = 'Captcha';
SJ('data.json',$JS1);
return false;
}
if($text && $JS1[$chat_id]['status'] == 'Captcha'){
$gg = bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
يرجى الآنتظار قليلاً.
",
]);
sleep(1);
$number = $JS1[$chat_id]['Number'];
$login = Login($chat_id,$number,$text);
bot('editMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$gg->result->message_id,
'text'=>"
أرسل رمز تسجيل الدخول الذي تم أرساله الى هاتفك.
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id]['pid'] = $login;
SJ('data.json',$JS1);
$JS1[$chat_id]['status'] = 'SMScode';
SJ('data.json',$JS1);
return false;
}
if($text != '/start' && $JS1[$chat_id]['status'] == 'SMScode'){
$number = $JS1[$chat_id]['Number'];
$pid = $JS1[$chat_id]['pid'];
$verify = json_decode(Verify($pid,$text,$chat_id),true);
sleep(5);
if($verify['success'] == true){
$token =$verify['access_token'];
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تم تسجيل الدخول في هذا الهاتف 
( $number )",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'لوحة التحكم','callback_data'=>'portal']],
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id]['status'] = null;
SJ('data.json',$JS1);
$JS1[$chat_id]['token'] = $token;
SJ('data.json',$JS1);
}elseif($verify['success'] != true){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"هناك خطب ما، حاول مجدداً في وقت لاحق.",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id]['status'] = null;
SJ('data.json',$JS1);
}
}
if($text && $JS1[$chat_id]['status'] == 'TransferTo'){
$JS1[$chat_id]['TransNumber'] = $text;
SJ('data.json',$JS1);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text' => "أرسل عدد الرصيد المُراد أرسالهُ بهذهِ الصيغة:

25000",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id]['status'] = 'AmountTransfer';
SJ('data.json',$JS1);
return false;
}
if($text && $JS1[$chat_id]['status'] == 'AmountTransfer'){
$JS1[$chat_id]['TransferAmount'] = $text;
SJ('data.json',$JS1);
$TransTo = $JS1[$chat_id]['TransNumber'];
bot('sendMessage',[
'chat_id'=>$chat_id,
'text' => "سيتم تحويل ( ".$text." ) ديناراً الى هذا الرقم ( ".$TransTo." )،

هل آنت متآكد من هذهِ العملية ؟",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'نعم، أنا متآكد','callback_data'=>'sure']],
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
}
if($text && $JS1[$chat_id]['status'] == 'confTrans'){
$number = $JS1[$chat_id]['TransNumber'];
$token = $JS1[$chat_id]['token'];
$pid = $JS1[$chat_id]['pid'];
$amount = $JS1[$chat_id]['TransferAmount'];
$Conf = json_decode(DoTransfer($token,$text,$pid,$chat_id),true);
sleep(5);
if($Conf['success'] == true){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
تم تحويل الرصيد بنجاح
رقم المستلم : ( $number )
العدد : ( $amount )
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id]['status'] = 'null';
SJ('data.json',$JS1);
}elseif($Conf['success'] != true){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"هناك خطب ما، حاول مجدداً في وقت لاحق.",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id]['status'] = null;
SJ('data.json',$JS1);
}
}
if($data == 'login'){
bot('editMessageText',[
'chat_id'=>$chat_id1,
'message_id'=>$mid,
'text'=>"
حسناً، قم بأرسال رقم الهاتف بهذهِ الصيغة:
077********
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id1]['status'] = 'Number';
SJ('data.json',$JS1);
}
if($data == 'portal'){
$number = $JS1[$chat_id1]['Number'];
$JS1[$chat_id1]['status'] = 'null';
SJ('data.json',$JS1);
bot('editMessageText',[
'chat_id'=>$chat_id1,
'message_id'=>$mid,
'text' => "
اهلاً بك في لوحة التحكم 

الرقم المستخدم ( $number )

يمكنك تحويل و تعبئه الرصيد هنا
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'تعبئة الرصيد','callback_data'=>'charge'],['text'=>'تحويل الرصيد','callback_data'=>'transfer']],
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
}
if($data == 'transfer'){
bot('editMessageText',[
'chat_id'=>$chat_id1,
'message_id'=>$mid,
'text' => "حسناً، قم بأرسال رقم الهاتف المراد تحويل الرصيد لهُ بهذهِ الصيغة:

077********",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id1]['status'] = 'TransferTo';
SJ('data.json',$JS1);
}
if($data == 'sure'){
$token = $JS1[$chat_id1]['token'];
$reciver = $JS1[$chat_id1]['TransNumber'];
$amount = $JS1[$chat_id1]['TransferAmount'];
$Start1 = TransferStart($token,$amount,$chat_id1,$reciver);
$Start = json_decode($Start1,true);
sleep(5);
$pid = $Start['PID'];
$JS1[$chat_id1]['pid'] = $pid;
SJ('data.json',$JS1);
if($Start['success'] == true){
bot('editMessageText',[
'chat_id'=>$chat_id1,
'message_id'=>$mid,
'text'=>"أرسل رمز التحقق الذي تم أرساله الى هاتفك",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id1]['status'] = 'confTrans';
SJ('data.json',$JS1);
}elseif($Start['success'] != true){
bot('editMessageText',[
'chat_id'=>$chat_id1,
'message_id'=>$mid,
'text'=>"هناك خطب ما، حاول مجدداً في وقت لاحق.",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id1]['status'] = null;
SJ('data.json',$JS1);    
}
}
if($data == 'charge'){
bot('editMessageText',[
'chat_id'=>$chat_id1,
'message_id'=>$mid,
'text'=>"حسناً، قم بأرسال رقم بطاقة التعبئة",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id1]['status'] = 'charge';
SJ('data.json',$JS1);
}
if($text != '/start' && $JS1[$chat_id]['status'] == 'charge'){
$JS1[$chat_id]['vouch'] = $text;
SJ('data.json',$JS1);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"قم بتآكيد الآمر رجاءً",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'نعم ، انا متآكد','callback_data'=>'surec']],
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
$JS1[$chat_id]['status'] = null;
SJ('data.json',$JS1);
}
if($data == 'surec'){
$token = $JS1[$chat_id1]['token'];
$vouch = $JS1[$chat_id1]['vouch'];
$do = json_decode(Vouch($vouch,$token,$chat_id1),true);
sleep(5);
if($do['success'] == true){
bot('editMessageText',[
'chat_id'=>$chat_id1,
'message_id'=>$mid,
'text'=>"تم تعبئة البطاقة بنجاح،
يمكنك أرسال رقم بطاقة آخر لتعبئتهُ.",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
}elseif($do['success'] != true){
bot('editMessageText',[
'chat_id'=>$chat_id1,
'message_id'=>$mid,
'text'=>"هناك خطب ما، حاول مجدداً في وقت لاحق.",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'الرجوع','callback_data'=>'back']],
]
])
]);
}
}
if($data == 'back'){
bot('editMessageText',[
'chat_id'=>$chat_id1,
'message_id'=>$mid,
'text'=>"*
مرحبا بك عزيزي المستخدم،
يمكنك التحكم في شريحة هاتفك ( الاسياسيل ) من خلال هذا البوت.
*",
'parse_mode'=>'markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'تسجيل الدخول','callback_data'=>'login']],
[['text'=>'لوحة التحكم','callback_data'=>'portal']],
]
])
]);
$JS1[$chat_id1]['status'] = 'null';
SJ('data.json',$JS1);
}
}
    };
         $bot = new EzTG(array('throw_telegram_errors'=>true,'token' => $token, 'callback' => $callback));
  }
    catch(Exception $e){
 echo $e->getMessage().PHP_EOL;
 sleep(1);
}

