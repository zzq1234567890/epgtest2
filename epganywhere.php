<?php
//header( 'Content-Type: text/plain; charset=UTF-8');
ini_set("max_execution_time", "333333000000");
ini_set('date.timezone','Asia/Shanghai');
$fp="epganywhere.xml";//压缩版本的扩展名后加.gz
$dt1=date('Y-m-d');//獲取當前日期
$dt2=date('Y-m-d',time()+24*3600);//第二天日期
$dt21=date('Ymd',time()+48*3600);//第三天日期
$dt3=date('Ymd',time()+6*24*3600);

$dt11=date('Ymd');//獲取當前日期
$dt12=date('Ymd',time()+24*3600);//第二天日期
$dt13=date('Ymd',time()+48*3600);//第二天日期

$w1=date("w");//當前第幾周
if ($w1<'1') {$w1=7;}
$w2=$w1+1;

function match_string($matches)
{
    return  iconv('UCS-2', 'UTF-8', pack('H4', $matches[1]));
    //return  iconv('UCS-2BE', 'UTF-8', pack('H4', $matches[1]));
    //return  iconv('UCS-2LE', 'UTF-8', pack('H4', $matches[1]));
}


function compress_html($string) {
    $string = str_replace("\r", '', $string); //清除换行符
    $string = str_replace("\n", '', $string); //清除换行符
    $string = str_replace("\t", '', $string); //清除制表符
    return $string;
}

function escape($str) 
{ 
preg_match_all("/[\x80-\xff].|[\x01-\x7f]+/",$str,$r); 
$ar = $r[0]; 
foreach($ar as $k=>$v) 
{ 
if(ord($v[0]) < 128) 
$ar[$k] = rawurlencode($v); 
else 
$ar[$k] = "%u".bin2hex(iconv("UTF-8","UCS-2",$v)); 
} 
return join("",$ar); 
} 




//適合php7以上
function replace_unicode_escape_sequence($match)
{       
		return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');     
}          


$chn="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<!DOCTYPE tv SYSTEM \"http://api.torrent-tv.ru/xmltv.dtd\">\n<tv generator-info-name=\"秋哥綜合\" generator-info-url=\"https://www.tdm.com.mo/c_tv/?ch=Satellite\">\n";


$idn5=700100;
$cid5=array(

array('J','翡翠台'),

array('JADE','翡翠台(国际版)'),
array('NEWS','无线新闻(国际版)'),
array('STYLE','TVB生活台'),

array('ENEW','娱乐新闻'),
array('XIHE','TVB星河'),

array('AACT','TVB功夫台'),
array('CLM','TVB粤语片'),

array('MN1','神州新闻'),

array('KID','Hands Up'),


 );

$nid5=sizeof($cid5);

for ($idm5 = 1; $idm5 <= $nid5; $idm5++){
 $idd5=$idn5+$idm5;
   $chn.="<channel id=\"".$cid5[$idm5-1][1]."\"><display-name lang=\"zh\">".$cid5[$idm5-1][1]."</display-name></channel>\n";
                                         }
for ($id5 = 1; $id5 <= $nid5; $id5++){
//https://apisfm.tvbanywhere.com.sg/epg/get/I-STYLE/2024-07-29/language/tc/+08
 $url5='https://apisfm.tvbanywhere.com.sg/epg/v2/get/I-'.$cid5[$id5-1][0].'/'.$dt1.'/language/tc/+8.0';
//https://apisfm.tvbanywhere.com.sg/epg/v2/get/I-JADE/2022-07-30/language/tc/+8.0
$idd5=$id5+$idn5;
    $ch5 = curl_init();
    curl_setopt($ch5, CURLOPT_URL, $url5);
    curl_setopt($ch5, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch5, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch5, CURLOPT_SSL_VERIFYHOST, FALSE);
     curl_setopt($ch5,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re5 = curl_exec($ch5);
  // $re5=compress_html($re5);
  $re5=str_replace('&','&amp;',$re5);
    curl_close($ch5);
//print $re5;



//$re5=replace('<','&lt',$re5);
preg_match_all('|start_datetime":(.*?),"|i',$re5,$us20,PREG_SET_ORDER);//播放開始時間
preg_match_all('|programme_name":"(.*?)",|i',$re5,$ue20,PREG_SET_ORDER);//播放結束時間

//print_r($us20);
//print_r($ue20);

$ryut20=count($us20);
for ( $i20=0 ; $i20<=$ryut20-2 ; $i20++ ) {
$chn.="<programme start=\"".str_replace(' ','',str_replace('-','',str_replace(':','',date('Y-m-d H:i:s', $us20[$i20][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace('-','',str_replace(':','',date('Y-m-d H:i:s', $us20[$i20+1][1])))).' +0800'."\" channel=\"".$cid5[$id5-1][1]."\">\n<title lang=\"zh\">". $ue20[$i20][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";

}

 $url51='https://apisfm.tvbanywhere.com.sg/epg/v2/get/I-'.$cid5[$id5-1][0].'/'.$dt2.'/language/tc/+8.0';
//https://apisfm.tvbanywhere.com.sg/epg/v2/get/I-JADE/2022-07-30/language/tc/+8.0
$idd5=$id5+$idn5;
    $ch51 = curl_init();
    curl_setopt($ch51, CURLOPT_URL, $url51);
    curl_setopt($ch51, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch51, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch51, CURLOPT_SSL_VERIFYHOST, FALSE);
     curl_setopt($ch51,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re51 = curl_exec($ch51);
  // $re5=compress_html($re5);
 $re51=str_replace('&','&amp;',$re51);
    curl_close($ch51);
//print $re5;



preg_match_all('|start_datetime":(.*?),"programme_name|i',$re51,$us201,PREG_SET_ORDER);//播放開始時間
preg_match_all('|programme_name":"(.*?)","programme_desc|i',$re51,$ue201,PREG_SET_ORDER);//播放結束時間

//print_r($us20);
//print_r($ue20);

$ryut201=count($us201);
for ( $i201=0 ; $i201<=$ryut201-2 ; $i201++ ) {
$chn.="<programme start=\"".str_replace(' ','',str_replace('-','',str_replace(':','',date('Y-m-d H:i:s', $us201[$i201][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace('-','',str_replace(':','',date('Y-m-d H:i:s', $us201[$i201+1][1])))).' +0800'."\" channel=\"".$cid5[$id5-1][1]."\">\n<title lang=\"zh\">". $ue201[$i201][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";

}

}
$chn.="<channel id=\"看看新聞直播\"><display-name lang=\"zh\">看看新聞直播</display-name></channel>\n";

$urlk13="https://live.kankanews.com/";
//$urlk13="https://api-app.kankanews.com/kankan/pc/live?nonce=".$nonce."&platform=pc&timestamp=".$t."&version=1.0&sign==".$sign;
//https://api-app.kankanews.com/kankan/pc/live?//nonce=vin4zynj&platform=pc&timestamp=1669687056&version=1.0&sign=5974b70fe1900fe67f89d853e9ba7cd4
$ch13=curl_init();
curl_setopt($ch13,CURLOPT_URL,$urlk13);
curl_setopt($ch13,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch13,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch13,CURLOPT_RETURNTRANSFER,1);
$hea13=[
'Host: live.kankanews.com',
'Connection: keep-alive',
'sec-ch-ua: "Not_A Brand";v="99", "Microsoft Edge";v="109", "Chromium";v="109"',
'sec-ch-ua-mobile: ?0',
'sec-ch-ua-platform: "Windows"',
'Upgrade-Insecure-Requests: 1',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36 Edg/109.0.1518.78',

];

curl_setopt($ch13,CURLOPT_HTTPHEADER,$hea13);
curl_setopt($ch13,CURLOPT_ENCODING,'Vary: Accept-Encoding');
$rek13=curl_exec($ch13);
curl_close($ch13);
//$rk13=stripslashes($rek13);
$rek13 = str_replace(array("\r\n", "\r", "\n"), "", $rek13);
//$rek13 = preg_replace("/(s*?r?ns*?)+/","n",$rek13);
//$rek13 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $rek13);// 適合php7
//print $rek13;

preg_match('/\<div class\="main\-box" data\-v\-(.*?)\>\<div/i', $rek13,$yy);// 適合php7
//print $yy[1];


 preg_match('/div class\="info" data\-v\-(.*?)div class\="duration" data\-v/i',$rek13,$date13);//
//preg_match('/div class\="video\-info" style\="display:none;" data\-v\-(.*?)div class\="duration" data\-v/i',$rek13,$date13);//
//print $date13[1];

$date13[1]=str_replace('</a>','',$date13[1]);

preg_match_all('/target\="_blank" data-v-'.$yy[1].'>(.*?)<\/div>/i',$date13[1],$um13,PREG_SET_ORDER);//播放标题
preg_match_all('/img src\=\"https:\/\/skin.kankanews.com\/kknews\/img\/icon_time.svg" data-v-'.$yy[1].'>(.*?)\<\/span>/i',$date13[1],$un13,PREG_SET_ORDER);//播放时间

$trm13=count($un13);
for ($k13 =1; $k13 <= $trm13 ; $k13++) {  
$chn.="<programme start=\"2024".str_replace('月','',str_replace(' ','',str_replace('日','',str_replace('AM','',str_replace('PM','',str_replace(':','',$un13[$k13-1][1])))))).'00 +0800'."\" stop=\"2024".(str_replace('月','',str_replace(' ','',str_replace('日','',str_replace('AM','',str_replace('PM','',str_replace(':','',$un13[$k13-1][1]))))))+100).'00 +0800'."\" channel=\"看看新聞直播\">\n<title lang=\"zh\">".$um13[($k13-1)*2][1]."</title>\n<desc lang=\"zh\">".$um13[($k13-1)*2][1]." </desc>\n</programme>\n";
}                                                                                                             
//第一财经官网直播
$url161='https://yicai.smgbb.cn/api/ajax/getlivelist?page=1&pagesize=30&action=mix';

$ch161 = curl_init();
    curl_setopt($ch161, CURLOPT_URL, $url161);
    curl_setopt($ch161, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch161, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch161, CURLOPT_SSL_VERIFYHOST, FALSE);
   $re161 = curl_exec($ch161);
    curl_close($ch161);

preg_match_all('|(.*?)"LiveState":0|i',$re161,$unk161,PREG_SET_ORDER);//播放狀態
//print_r($unk161);
$trm161=count($unk161);
preg_match_all('|"NewsTitle":"(.*?)"|i',$re161,$umk161,PREG_SET_ORDER);//節目標題
preg_match_all('|"LiveDate":"(.*?)"|i',$re161,$ulk161,PREG_SET_ORDER);//"LiveDate":"2021-08-09 09:30",

preg_match_all('|"NewsNotes":"(.*?)",|i',$re161,$unk161,PREG_SET_ORDER);//"LiveDate":"2021-08-09 09:30",


//print_r($umk161);
//print_r($ulk161);
$trm161=count($ulk161);
$chn.="<channel id=\"第一財經直播\"><display-name lang=\"zh\">第一財經直播</display-name></channel>\n";

for ($k16 = 0; $k16<=$trm161-1; $k16++) { 

  $chn.="<programme start=\"".str_replace('-','',str_replace(':','',str_replace(' ','',$ulk161[$k16][1])))
.'00 +0800'."\" stop=\"".(str_replace('-','',str_replace(':','',str_replace(' ','',$ulk161[$k16][1])))
+100).'00 +0800'."\" channel=\"第一財經直播\">\n<title lang=\"zh\">".trim($umk161[$k16][1])."</title>\n<desc lang=\"zh\"> ".trim($unk161[$k16][1])."</desc>\n</programme>\n";
 

}
                                                                                                               
//央视频
$id29=799999;//起始节目编号
$cid29=array(
   
   array('600001859','cctv1'),

    array('600001800','cctv2'),
    array('600001801','cctv3'),
   array('600001814','cctv4亚洲'),
 array('600001818','cctv5'),
   array('600001817','cctv5+'),
   array('600001802','cctv6'),
   array('600004092','cctv7'),
array('600001803','cctv8'),
   array('600004078','cctv9'),
array('600001805','cctv10'),
array('600001806','cctv11'),
array('600001807','cctv12'),
array('600001811','cctv13'),
array('600001809','cctv14'),
array('600001815','cctv15'),
array('600099502','cctv16'),
array('600001810','cctv17'),
array('600002264','cctv4k'),
array('600156816','cctv8k'),
array('600099655','cctv第一剧场'),
array('600099658','cctv风云剧场'),
array('600099620','cctv怀旧剧场'),
array('600099637','cctv世界地理'),
array('600099660','cctv风云音乐'),
array('600099649','cctv兵器科技'),
array('600099636','cctv风云足球'),
array('600099659','cctv高尔夫'),
array('600099650','cctv女性时尚'),
array('600099653','cctv文化精品'),
array('600099652','cctv台球'),
array('600099656','cctv电视指南'),
array('600099651','cctv卫生健康'),
array('600014550','cgtn'),
array('600084704','cgtn法语'),
array('600084758','cgtn俄语'),
array('600084782','cgtn阿拉伯语'),
array('600084744','cgtn西班牙语'),
array('600084781','cgtn英文记录片'),
array('600002309','北京卫视'),
array('600002483','东方卫视'),
array('600002521','江苏卫视'),
array('600002520','浙江卫视'),
array('600002475','湖南卫视'),
array('600002508','湖北卫视'),
array('600002485','广东卫视'),
array('600002509','广西卫视'),
array('600002498','黑龙江卫视'),
array('600002506','海南卫视'),
array('600002531','重庆卫视'),
array('600002481','深圳卫视'),
array('600002516','四川卫视'),
array('600002525','河南卫视'),
array('600002484','东南卫视'),
array('600002490','贵州卫视'),
array('600002503','江西卫视'),
array('600002505','辽宁卫视'),
array('600002532','安徽卫视'),
array('600002493','河北卫视'),
array('600002513','山东卫视'),
array('600152137','天津卫视'),
array('600190405','吉林卫视'),
array('600190400','陕西卫视'),
array('600190408','甘肃卫视'),
array('600190737','宁夏卫视'),
array('600190401','内蒙古卫视'),
array('600190402','云南卫视'),
array('600190407','山西卫视'),
array('600190406','青海卫视'),
array('600190403','西藏卫视'),
array('600171827','中国教育电视台-1'),
array('600152138','新疆卫视'),
array('600170344','兵团卫视'),





);

$nid29=sizeof($cid29);
for ($idm29 = 1; $idm29 <= $nid29; $idm29++){
 $idd29=$id29+$idm29;
   $chn.="<channel id=\"".$cid29[$idm29-1][1]."\"><display-name lang=\"zh\">".$cid29[$idm29-1][1]."</display-name></channel>\n";
         
}

for ($idm29 = 1; $idm29 <= $nid29; $idm29++){
//https://w.yangshipin.cn/video?type=1&pid=600099502
          
$url29='https://h5access.yangshipin.cn/web/tv_program?targetId=1&vappid=59306155&vsecret=b42702bf7309a179d102f3d51b1add2fda0bc7ada64cb801&raw=1&type=by_day&pid='.$cid29[$idm29-1][0].'&day='.$dt1;
//https://capi.yangshipin.cn/api/yspepg/program/600002264/20241025

$idd29=$id29+$idm29;
$ch29= curl_init ();
curl_setopt ( $ch29, CURLOPT_URL, $url29 );

curl_setopt($ch29,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch29,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt ( $ch29, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch29,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re29 = curl_exec($ch29);
   // $re29=str_replace('&','&amp;',$re29);
   curl_close($ch29);

$data29=json_decode($re29)->data->programs;

$ryut=count($data29);

for ($k29 =0; $k29 < $ryut; $k29++){


$start_time_stamp=json_decode($re29)->data->programs[$k29]->start_time_stamp;
$name=json_decode($re29)->data->programs[$k29]->name;
$duration=json_decode($re29)->data->programs[$k29]->duration;

   $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date('Ymd H:i:s', $start_time_stamp)
))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date('Ymd H:i:s', $start_time_stamp+$duration)
))).' +0800'."\" channel=\"".$cid29[$idm29-1][1]."\">\n<title lang=\"zh\">".$name."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";



}

$url291='https://h5access.yangshipin.cn/web/tv_program?targetId=1&vappid=59306155&vsecret=b42702bf7309a179d102f3d51b1add2fda0bc7ada64cb801&raw=1&type=by_day&pid='.$cid29[$idm29-1][0].'&day='.$dt2;
$idd29=$id29+$idm29;
$ch291= curl_init ();
curl_setopt ( $ch291, CURLOPT_URL, $url291 );

curl_setopt($ch291,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch291,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt ( $ch291, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch291,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re291 = curl_exec($ch291);
   // $re291=str_replace('&','&amp;',$re291);
   curl_close($ch291);

$data291=json_decode($re291)->data->programs;

$ryut1=count($data291);

for ($k291 =0; $k291 < $ryut1; $k291++){


$start_time_stamp1=json_decode($re291)->data->programs[$k291]->start_time_stamp;
$name1=json_decode($re291)->data->programs[$k291]->name;
$duration1=json_decode($re291)->data->programs[$k291]->duration;

   $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date('Ymd H:i:s', $start_time_stamp1)
))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date('Ymd H:i:s', $start_time_stamp1+$duration1)
))).' +0800'."\" channel=\"".$cid29[$idm29-1][1]."\">\n<title lang=\"zh\">".$name1."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";



}

                                                        }  

      
//香港电台
$headers10=[
'Host: www.rthk.hk',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:93.0) Gecko/20100101 Firefox/93.0',
'DNT: 1',
'Upgrade-Insecure-Requests: 1',
'Sec-Fetch-Dest: document',
'Sec-Fetch-Mode: navigate',
'Sec-Fetch-Site: none',
'Sec-Fetch-User: ?1',
'Sec-GPC: 1',
];

$url10='https://www.rthk.hk/timetable/main_timetable/'.$dt11;

    $ch10 = curl_init();
    curl_setopt($ch10, CURLOPT_URL, $url10);
    curl_setopt($ch10, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch10, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch10, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch10, CURLOPT_HTTPHEADER, $headers10);
   // curl_setopt($ch10,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re10 = curl_exec($ch10);
    curl_close($ch10);
//$re10 = preg_replace('/\s(?=)/', '',$re10);

$re10= preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re10);// 適合php7
//$re10 = preg_replace('/\s(?=)/', '',compress_html($re10));
$re10=compress_html($re10);
$re10 = preg_replace('/\s(?=)/', '',$re10);
$re10=str_replace('\t','',$re10);
$re10=str_replace('\r\n','',$re10);
//$re10=str_replace('\','',$re10);
$re10=stripslashes($re10);
//print $re10;

preg_match('/"name":"dtt31","type":"tv","short_eng_name":"TV31","key":"31","data":"(.*)\},\{"name":"dtt32",/i',$re10,$um10);
//print $um10[1];
preg_match_all('|<divclass="timeRow">(.*?)</div>|i',$um10[0],$un10,PREG_SET_ORDER);//播放時間
preg_match_all('|<aclass="showTit"title="(.*?)"|i',$um10[0],$ul10,PREG_SET_ORDER);//播放節目介紹



$trm10=count($ul10);
 $chn.="<channel id=\"香港電台31\"><display-name lang=\"zh\">香港電台31</display-name></channel>\n";
$chn.="<channel id=\"香港電台32\"><display-name lang=\"zh\">香港電台32</display-name></channel>\n";
$chn.="<channel id=\"香港電台33\"><display-name lang=\"zh\">香港電台33</display-name></channel>\n";


for ($k10 = 0; $k10 <= $trm10-2; $k10++) { 
    $chn.="<programme start=\"".$dt11.str_replace(':','',substr("".$un10[$k10][1]."",0,5)).'00 +0800'."\" stop=\"".$dt11.str_replace(':','',substr("".$un10[$k10][1]."",-5)).'00 +0800'."\" channel=\"香港電台31\">\n<title lang=\"zh\">".$ul10[$k10][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                 }

 $chn.="<programme start=\"".$dt11.str_replace(':','',substr("".$un10[$trm10-1][1]."",0,5)).'00 +0800'."\" stop=\"".$dt12.str_replace(':','',substr("".$un10[$trm10-1][1]."",-5)).'00 +0800'."\" channel=\"香港電台31\">\n<title lang=\"zh\">".$ul10[$trm10-1][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";


preg_match('/{"name":"dtt32","type":"tv","short_eng_name":"TV32","key":"32","data":"(.*)\},\{"name":"dtt33",/i',$re10,$umk10);
preg_match_all('|<divclass="timeRow">(.*?)</div>|i',$umk10[0],$unk10,PREG_SET_ORDER);//播放時間
preg_match_all('|<aclass="showTit"title="(.*?)"|i',$umk10[0],$ulk10,PREG_SET_ORDER);//播放節目介紹

 $trmk10=sizeof($ulk10);
 

for ($kk10 = 0; $kk10 <= $trmk10-2; $kk10++) { 
    $chn.="<programme start=\"".$dt11.str_replace(':','',substr("".$unk10[$kk10][1]."",0,5)).'00 +0800'."\" stop=\"".$dt11.str_replace(':','',substr("".$unk10[$kk10][1]."",-5)).'00 +0800'."\" channel=\"香港電台32\">\n<title lang=\"zh\">".$ulk10[$kk10][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                 }

 $chn.="<programme start=\"".$dt11.str_replace(':','',substr("".$unk10[$trmk10-1][1]."",0,5)).'00 +0800'."\" stop=\"".$dt12.str_replace(':','',substr("".$unk10[$trmk10-1][1]."",-5)).'00 +0800'."\" channel=\"香港電台32\">\n<title lang=\"zh\">".$ulk10[$trmk10-1][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";


preg_match('/{"name":"dtt33","type":"tv","short_eng_name":"TV33","key":"33","data":"(.*)\},\{"name":"dtt34",/i',$re10,$umkk10);              
//preg_match('/{"name":"dtt33","key":"33","data":"(.*)\},\{"name":"dtt34",/i',$re10,$umkk10);
//preg_match('/{"name":"dtt34","key":"34","data":"(.*)\},\{"name":"radio1",/i',$re10,$umkk10);
preg_match_all('|<divclass="timeRow">(.*?)</div>|i',$umkk10[0],$unkk10,PREG_SET_ORDER);//播放時間
preg_match_all('|<aclass="showTit"title="(.*?)"|i',$umkk10[0],$ulkk10,PREG_SET_ORDER);//播放節目介紹

 $trmkk10=sizeof($ulkk10);
 

for ($kkk10 = 0; $kkk10 <= $trmkk10-2; $kkk10++) { 
    $chn.="<programme start=\"".$dt11.str_replace(':','',substr("".$unkk10[$kkk10][1]."",0,5)).'00 +0800'."\" stop=\"".$dt11.str_replace(':','',substr("".$unkk10[$kkk10][1]."",-5)).'00 +0800'."\" channel=\"香港電台33\">\n<title lang=\"zh\">".$ulkk10[$kkk10][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                 }

 $chn.="<programme start=\"".$dt11.str_replace(':','',substr("".$unkk10[$trmkk10-1][1]."",0,5)).'00 +0800'."\" stop=\"".$dt12.str_replace(':','',substr("".$unkk10[$trmkk10-1][1]."",-5)).'00 +0800'."\" channel=\"香港電台33\">\n<title lang=\"zh\">".$ulkk10[$trmkk10-1][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";



$url1011='https://www.rthk.hk/timetable/main_timetable/'.$dt12;
    $ch1011 = curl_init();
    curl_setopt($ch1011, CURLOPT_URL, $url1011);
    curl_setopt($ch1011, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch1011, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch1011, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch1011, CURLOPT_HTTPHEADER, $headers10);
   // curl_setopt($ch10,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re1011 = curl_exec($ch1011);
    curl_close($ch1011);
//$re1011 = preg_replace('/\s(?=)/', '',$re1011);

$re1011= preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re1011);// 適合php7

$re1011=compress_html($re1011);

$re1011 = preg_replace('/\s(?=)/', '',$re1011);
$re1011=str_replace('\t','',$re1011);
$re1011=str_replace('\r\n','',$re1011);

$re1011=stripslashes($re1011);


preg_match('/"name":"dtt31","type":"tv","short_eng_name":"TV31","key":"31","data":"(.*)\},\{"name":"dtt32",/i',$re1011,$um1011);

preg_match_all('|<divclass="timeRow">(.*?)</div>|i',$um1011[0],$un1011,PREG_SET_ORDER);//播放時間
preg_match_all('|<aclass="showTit"title="(.*?)"|i',$um1011[0],$ul1011,PREG_SET_ORDER);//播放節目介紹

$trm1011=count($ul1011);
 

for ($k1011 = 0; $k1011 <= $trm1011-2; $k1011++) { 
    $chn.="<programme start=\"".$dt12.str_replace(':','',substr("".$un1011[$k1011][1]."",0,5)).'00 +0800'."\" stop=\"".$dt12.str_replace(':','',substr("".$un1011[$k1011][1]."",-5)).'00 +0800'."\" channel=\"香港電台31\">\n<title lang=\"zh\">".$ul1011[$k1011][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                 }

// $chn.="<programme start=\"".$dt12.str_replace(':','',substr("".$un1011[$trm1011-1][1]."",0,5)).'00 +0800'."\" stop=\"".$dt13.str_replace(':','',substr("".$un1011[$trm1011-1][1]."",-5)).'00 +0800'."\" channel=\"香港電台31\">\n<title lang=\"zh\">".$ul1011[$trm1011-1][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";




preg_match('/"name":"dtt32","type":"tv","short_eng_name":"TV32","key":"32","data":"(.*)\},\{"name":"dtt33",/i',$re1011,$umk1011);
//preg_match('/{"name":"dtt32","key":"32","data":"(.*)\},\{"name":"dtt33",/i',$re1011,$umk1011);
preg_match_all('|<divclass="timeRow">(.*?)</div>|i',$umk1011[0],$unk1011,PREG_SET_ORDER);//播放時間
preg_match_all('|<aclass="showTit"title="(.*?)"|i',$umk1011[0],$ulk1011,PREG_SET_ORDER);//播放節目介紹

 $trmk1011=sizeof($ulk1011);
 

for ($kk1011 = 0; $kk1011 <= $trmk1011-2; $kk1011++) { 
    $chn.="<programme start=\"".$dt12.str_replace(':','',substr("".$unk1011[$kk1011][1]."",0,5)).'00 +0800'."\" stop=\"".$dt12.str_replace(':','',substr("".$unk1011[$kk1011][1]."",-5)).'00 +0800'."\" channel=\"香港電台32\">\n<title lang=\"zh\">".$ulk1011[$kk1011][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                 }

 $chn.="<programme start=\"".$dt12.str_replace(':','',substr("".$unk1011[$trmk1011-1][1]."",0,5)).'00 +0800'."\" stop=\"".$dt13.str_replace(':','',substr("".$unk1011[$trmk1011-1][1]."",-5)).'00 +0800'."\" channel=\"香港電台32\">\n<title lang=\"zh\">".$ulk1011[$trmk1011-1][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";







//preg_match('/"name":"dtt32","type":"tv","short_eng_name":"TV32","key":"32","data":"(.*)\},\{"name":"dtt33",/i',$re1011,$umk1011);
preg_match('/"name":"dtt33","type":"tv","short_eng_name":"TV33","key":"33","data":"(.*)\},\{"name":"dtt34",/i',$re1011,$umkk1011);
//preg_match('/{"name":"dtt33","key":"33","data":"(.*)\},\{"name":"dtt34",/i',$re1011,$umkk1011);
preg_match_all('|<divclass="timeRow">(.*?)</div>|i',$umkk1011[0],$unkk1011,PREG_SET_ORDER);//播放時間
preg_match_all('|<aclass="showTit"title="(.*?)"|i',$umkk1011[0],$ulkk1011,PREG_SET_ORDER);//播放節目介紹

 $trmkk1011=count($ulkk1011);
 

for ($kkk1011 = 0; $kkk1011<= $trmkk1011-2; $kkk1011++) { 
    $chn.="<programme start=\"".$dt2.str_replace(':','',substr("".$unkk1011[$kkk1011][1]."",0,5)).'00 +0800'."\" stop=\"".$dt2.str_replace(':','',substr("".$unkk1011[$kkk1011][1]."",-5)).'00 +0800'."\" channel=\"香港電台33\">\n<title lang=\"zh\">".$ulkk1011[$kkk1011][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                 }

 $chn.="<programme start=\"".$dt2.str_replace(':','',substr("".$unkk1011[$trmkk1011-1][1]."",0,5)).'00 +0800'."\" stop=\"".$dt2.str_replace(':','',substr("".$unkk1011[$trmkk1011-1][1]."",-5)).'00 +0800'."\" channel=\"香港電台33\">\n<title lang=\"zh\">".$ulkk1011[$trmkk1011-1][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";

    
/*
//malysia astro

$id22=900399;//起始节目编号
$cid22=array(
array('115','8TV'),
array('501','TVB Magic'),
array('355','爱奇艺'),
array('187','天映经典'),
array('182','AEC'),
array('158','全佳'),
array('134','天映频道'),
array('172','AOD311'),
array('383','星河频道'),
array('162','欢喜台'),
array('87','AOD 352'),
array('65','AOD 354'),
array('236','beIN Sports 1'),
array('466','beIN Sports 2'),
array('313','beIN Sports 3'),
array('194','WWE Network HD'),
array('189','Golf Channel HD'),
array('393','Premier Sports'),
array('197','Astro Cricket HD'),
array('154','Astro SuperSport HD'),
array('138','Astro SuperSport2 HD'),
array('164','Astro SuperSport3 HD'),
array('241','Astro SuperSport4 HD'),
array('503','W-Sport'),
array('203','翡翠'),
array('383','星河频道'),
array('424','中天亚洲'),
array('162','欢喜台'),
array('266','K-Plus HD'),
array('251','BOO HD'),
array('395','TV1 HD'),
array('396','TV2 HD'),
array('106','TV3 HD'),

array('48','TV9 HD'),
array('467','TV Okey HD'),
array('429','TVS'),
array('193','Astro Ria HD'),
array('316','Astro Prima HD'),
array('315','Astro Oasis HD'),
array('272','Astro Warna HD'),
array('301','Astro Citra HD'),
array('401','Astro Rania HD'),
array('403','Astro Aura HD'),
array('149','Al-Hijrah'),
array('365','Colors Hindi HD'),
array('443','Awesome TV'),
array('397','Astro Vaanavil HD'),
array('167','Astro Vinmeen HD'),
array('399','Astro Vellithirai HD'),
array('358','SUN TV HD'),
array('417','Sun Music HD'),
array('67','Adithya'),
array('478','Sun News'),
array('357','Star Vijay HD'),
array('298','Colors Tamil HD'),
array('297','Zee Tamil HD'),
array('177','ABO Movies Thangathirai HD'),
array('490','Zee Cinema'),
array('172','Astro AOD 311'),
array('427','TVB娱乐新闻'),
array('427','TVB娱乐新闻'),
array('161','KBS World HD'),
array('133','ONE HD'),
array('190','tvN HD'),
array('196','K-Plus HD'),
array('428','NHK World Premium'),
array('391','HITS Movies HD'),
array('251','BOO HD'),
array('508','Astro Premier'),
array('454','SHOWCASE'),
array('274','tvN Movies HD'),
array('436','Astro Awani HD'),
array('160','BERNAMA'),
array('155','Sky News HD'),
array('461','ABC Australia HD'),
array('411','Astro Tutor TV'),
array('386','Astro Ceria HD'),
array('465','Moonbug'),
array('524','HITS NOW'),
array('448','Paramount Network'),
array('235','Astro Arena HD'),
array('457','Astro Arena 2 HD'),
array('486','Arena Bola'),
array('487','Arena Bola 2'),
array('308','Astro SuperSport UHD 1'),
array('189','Golf Channel HD'),
array('393','Premier Sports'),

);
$nid22=sizeof($cid22);
for ($idm22 = 1; $idm22 <= $nid22; $idm22++){
 $idd22=$id22+$idm22;
   $chn.="<channel id=\"".$cid22[$idm22-1][1]."\"><display-name lang=\"zh\">".$cid22[$idm22-1][1]."</display-name></channel>\n";       
}

for ($idm22 = 1; $idm22 <= $nid22; $idm22++){

//https://contenthub-api.eco.astro.com.my/channel/115.json
$url22="https://contenthub-api.eco.astro.com.my/channel/".$cid22[$idm22-1][0].".json";
 $idd22=$id22+$idm22;
    $ch22 = curl_init();
    curl_setopt($ch22, CURLOPT_URL, $url22);
    curl_setopt($ch22, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch22, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch22, CURLOPT_SSL_VERIFYHOST, FALSE);
 curl_setopt($ch22,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re22 = curl_exec($ch22);
  $re22=str_replace('&','&amp;',$re22);

    curl_close($ch22);
//print  $re22;
$schedule22=json_decode($re22)->response->schedule;


$tyuu22=json_decode($re22)->response->schedule->$dt1;
$trm22=count($tyuu22);

 for ($k22 = 0; $k22 < $trm22-1; $k22++) { 
$title22=json_decode($re22)->response->schedule->$dt1[$k22]->title;
$starttime22=json_decode($re22)->response->schedule->$dt1[$k22]->datetime;
$endtime22=json_decode($re22)->response->schedule->$dt1[$k22+1]->datetime;
$chn.="<programme start=\"".str_replace(':','',str_replace('-','',str_replace(' ','',str_replace('.0','',$starttime22))))." +0800\" stop=\"".str_replace(':','',str_replace('-','',str_replace(' ','',str_replace('.0','',$endtime22))))." +0800\"  channel=\"".$cid22[$idm22-1][1]."\">\n<title lang=\"zh\">". $title22."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";

}


$tyuu221=json_decode($re22)->response->schedule->$dt2;
$trm221=count($tyuu221);
 for ($k221 = 0; $k221 < $trm221-1; $k221++) { 
$title221=json_decode($re22)->response->schedule->$dt2[$k221]->title;
$starttime221=json_decode($re22)->response->schedule->$dt2[$k221]->datetime;
$endtime221=json_decode($re22)->response->schedule->$dt2[$k221+1]->datetime;
$chn.="<programme start=\"".str_replace(':','',str_replace('-','',str_replace(' ','',str_replace('.0','',$starttime221))))." +0800\" stop=\"".str_replace(':','',str_replace('-','',str_replace(' ','',str_replace('.0','',$endtime221))))." +0800\"  channel=\"".$cid22[$idm22-1][1]."\">\n<title lang=\"zh\">". $title221."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";

}

}


*/

//印尼epg



$id25=899999;
$cid25=array(
array('350','ABC Australia'),

array('331','Al Jazeera English '),
array('93','Al Quran Al Kareem'),
array('157','Animax '),
array('115','ANTV'),
array('351','Arirang '),

array('154','AXN'),
array('438','AXN HD '),
array('200','BBC Earth'),
array('461','BBC Earth HD '),
array('332','BBC World News '),
array('338','Bloomberg'),
array('103','BTV'),
array('41','CBeebies'),
array('22','CCM'),
array('96','Celebrities TV'),
array('20','Celestial Movies'),
array('353','CGTN '),
array('205','CGTN Documentary'),
array('330','Channel News Asia '),
array('7','CINEMACHI'),
array('8','CINEMACHI ACTION'),
array('402','CINEMACHI ACTION HD'),
array('401','CINEMACHI HD'),
array('9','CINEMACHI KIDS'),
array('403','CINEMACHI KIDS HD'),
array('10','CINEMACHI MAX '),
array('404','CINEMACHI MAX HD'),
array('6','CINEMACHI XTRA '),
array('405','CINEMACHI XTRA HD'),
array('337','CNBC'),
array('207','Crime Investigation'),
array('47','Dreamworks'),
array('357','DW English'),
array('86','Entertainment '),
array('333','EURONEWS'),
array('304','Fight Sports'),
array('150','FMN'),
array('335','FOX News'),
array('352','France 24 English'),
array('13','Galaxy'),
array('12','Galaxy Premium'),
array('201','Global Trekker'),
array('81','GTV '),
array('431','GTV HD'),
array('206','History '),
array('160','HITS '),
array('11','HITS MOVIES'),
array('409','HITS MOVIES HD'),
array('100','IDX '),
array('436','IDX HD'),
array('14','IMC '),
array('78','Indosiar'),
array('83','iNews'),
array('433','iNews HD '),
array('113','JAKTV'),
array('46','Kids TV '),
array('161','KIX'),
array('106','Kompas TV'),
array('91','LIFE'),
array('167','Lifetime '),
array('204','Love Nature '),
array('463','Love Nature HD'),
array('107','Metro TV'),
array('82','MNCTV'),
array('432','MNCTV HD '),
array('149','MTV 90'),
array('148','MTV LIVE '),
array('111','Music TV]'),
array('92','Muslim TV '),
array('16','My Cinema'),
array('17','My Cinema Asia '),
array('15','My Family '),
array('50','My Kidz'),
array('116','NET TV'),
array('355','NHK World '),
array('354','NHK World Premium'),
array('472','Nick Jr. HD'),
array('49','Nickelodeon'),
array('37','Nickelodeon Jr'),
array('95','OK TV '),
array('90','Okezone TV'),
array('164','ONE '),
array('445','ONE HD '),
array('202','Outdoor Channel '),
array('460','Outdoor channel HD'),
array('163','PARAMOUNT '),
array('441','PARAMOUNT HD'),
array('80','RCTI '),
array('430','RCTI HD'),
array('248','Rock Action'),
array('240','Rock Entertainment'),
array('89','SCTV'),
array('336','SEA TODAY '),
array('84','Sindo News TV '),
array('434','Sindo News TV HD]'),
array('101','Soccer Channel '),
array('420','Soccer Channel HD'),
array('102','Sportstars'),
array('98','Sportstars 2 '),
array('422','Sportstars 2 HD]'),
array('99','Sportstars 3 '),
array('88','Sportstars 4'),
array('424','Sportstars 4 HD'),
array('421','Sportstars HD '),
array('307','SPOTV '),
array('308','SPOTV 2 '),
array('428','SPOTV 2 HD '),
array('427','SPOTV HD '),
array('105','Tawaf TV'),
array('19','Thrill '),
array('110','Trans 7 '),
array('87','Trans TV'),
array('158','tvN '),
array('446','tvN HD'),
array('25','tvN Movies '),
array('415','tvN Movies HD'),
array('97','tvOne'),
array('118','TVRI '),
array('94','Vision Prime'),
array('1','Vision Prime HD'),
array('23','Zee Bioskop'),
array('39','Zoomoo'),

  );

$nid25=sizeof($cid25);
for ($idm25 = 1; $idm25 <= $nid25; $idm25++){
 $idd25=$id25+$idm25;
   $chn.="<channel id=\"".$cid25[$idm25-1][1]."\"><display-name lang=\"zh\">".$cid25[$idm25-1][1]."</display-name></channel>\n";
}
for ($idm25 = 1; $idm25 <= $nid25; $idm25++){
 $idd25=$id25+$idm25;
//$cookie='Cookie: s1nd0vL=3vmj1t4j1848vn68cspl5iuqaghl0v28';
$data25 =[
"search_model"=>'channel',
"af0rmelement"=>'aformelement',
"fdate"=>''.$dt1.'',
"fchannel"=>''.$cid25[$idm25-1][0].'',
"submit"=>'Cari',
];
$data251 =[
"search_model"=>'channel',
"af0rmelement"=>'aformelement',
"fdate"=>''.$dt2.'',
"fchannel"=>''.$cid25[$idm25-1][0].'',
"submit"=>'Cari',
];
$url25='https://www.mncvision.id/schedule/table';
$ch25 = curl_init();
    curl_setopt($ch25, CURLOPT_URL, $url25);

    curl_setopt($ch25, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch25, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch25, CURLOPT_SSL_VERIFYHOST, FALSE);
$hea25=[
'Referer: https://www.mncvision.id/schedule/table',
//'Content-Type: multipart/form-data',
'Origin: https://www.mncvision.id',
//'Content-Length: '.strlen($data25',
];

curl_setopt($ch25, CURLOPT_HTTPHEADER, $hea25);
curl_setopt($ch25,CURLOPT_POST,true);
//curl_setopt($ch, CURLOPT_POSTFIELDS,$data);

curl_setopt($ch25, CURLOPT_POSTFIELDS, http_build_query($data25)); // Post提交的数据包

//curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    $re25 = curl_exec($ch25);
$re25=str_replace('&','&amp;',$re25);
//$re25=str_replace('<','&lt;',$re25);
//$re25=str_replace('>','&gt;',$re25);








//$re25=str_replace(' ','',$re25);


   curl_close($ch25);

//print $re25;


preg_match_all('|<td class="text-center">(.*?)<\/td>|i',$re25,$um25,PREG_SET_ORDER);//播放時間
preg_match_all('|title="(.*?)" rel|i',$re25,$un25,PREG_SET_ORDER);//播放節目


$trm25=count($un25);






      for ($k25 =0; $k25<=$trm25-2;$k25++) {

        $chn.="<programme start=\"".$dt11.str_replace(':','',$um25[$k25*2][1]).'00 +0700'."\" stop=\"".$dt11.str_replace(':','',$um25[($k25+1)*2][1]).'00 +0700'."\" channel=\"".$cid25[$idm25-1][1]."\">\n<title lang=\"zh\">".$un25[$k25][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                                   }

// $chn.="<programme start=\"".$dt1.str_replace(':','',$um[$trm-1][1]).'00 +0700'."\" stop=\"".$dt1.'240000 +0700'."\" channel=\"".$cid25[$idm25-1][1]."\">\n<title lang=\"zh\">".$un[$trm-1][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";




$ch251 = curl_init();
    curl_setopt($ch251, CURLOPT_URL, $url25);

    curl_setopt($ch251, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch251 ,CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch251, CURLOPT_SSL_VERIFYHOST, FALSE);


curl_setopt($ch251, CURLOPT_HTTPHEADER, $hea25);
curl_setopt($ch251,CURLOPT_POST,true);
//curl_setopt($ch, CURLOPT_POSTFIELDS,$data);

curl_setopt($ch251, CURLOPT_POSTFIELDS, http_build_query($data251)); // Post提交的数据包

//curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    $re251 = curl_exec($ch251);
$re251=str_replace('&','&amp;',$re251);
//$re251=str_replace(' ','',$re251);
//$re251=str_replace('&','&amp;',$re251);
//$re251=str_replace('<','&lt;',$re251);
//$re251=str_replace('>','&gt;',$re251);




   curl_close($ch251);

preg_match_all('|<td class="text-center">(.*?)<\/td>|i',$re251,$um251,PREG_SET_ORDER);//播放時間
preg_match_all('|title="(.*?)" rel|i',$re251,$un251,PREG_SET_ORDER);//播放節目
$trm251=count($un251);






      for ($k251 =0; $k251<=$trm251-2;$k251++) {

        $chn.="<programme start=\"".$dt12.str_replace(':','',$um251[$k251*2][1]).'00 +0700'."\" stop=\"".$dt12.str_replace(':','',$um251[($k251+1)*2][1]).'00 +0700'."\" channel=\"".$cid25[$idm25-1][1]."\">\n<title lang=\"zh\">".$un251[$k251][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                                   }


}


$id4=800099;
$cid4=array(
array('第一财经','17'),
/*


  array('东方卫视','8'),

array('哈哈炫动','26'),
array('五年级','31'),
array('四年级','32'),
array('三年级','33'),
array('二年级','34'),
array('纪实人文','35'),
*/
array('新闻综合','36'),
/*
array('东方财经','37'),
*/
array('外语频道','38'),
array('都市频道','39'),
array('金色学堂','40'),
/*
array('东方影视',' 41'),
array('一年级','42'),
*/
array('五星体育','43'),
array('上海教育','46'),
array('法治天地','29'),
/*
array('欢笑剧场','47'),
array('动漫秀场','48'),
array('生活时尚','49'),
array('游戏风云','50'),
array('幸福彩','51'),
array('热门综艺','52'),
array('戏曲精选','53'),
array('快乐垂钓','54'),
array('陶瓷','55'),

array('茶频道','93'),


array('CCTV1','94'),
array('CCTV2','95'),
array('CCTV3','96'),
array('CCTV4','97'),
array('CCTV5','98'),
array('CCTV6','99'),
array('CCTV7','100'),
array('CCTV8','101'),
array('CCTV9','102'),
array('CCTV10','103'),
array('CCTV11','104'),
array('CCTV12','105'),
array('CCTV13','106'),
array('CCTV14','107'),
array('CCTV音乐','108'),
array('CCTV17','109'),
array('湖南卫视','110'),
array('北京卫视','111'),
array('浙江卫视','112'),
array('山东卫视','113'),
array('江苏卫视','114'),
array('贵州卫视','115'),
array('湖北卫视','116'),
array('深圳卫视','117'),
array('天津卫视','118'),
array('重庆卫视','119'),
array('安徽卫视','120'),
array('四川卫视','121'),
array('河南卫视','122'),
array('吉林卫视','123'),
array('云南卫视','124'),
array('辽宁卫视','125'),
array('黑龙江卫视','126'),
array('东南卫视','127'),
array('广西卫视','128'),
array('海南卫视','129'),
array('河北卫视','130'),
array('江西卫视','131'),
array('广东卫视','134'),

array('内蒙古卫视','132'),
array('山西卫视','133'),

array('青海卫视','135'),
array('甘肃卫视','136'),
array('宁夏卫视','137'),
array('兵团卫视','138'),
array('陕西卫视','139'),
array('卡酷卡通','140'),
array('金鹰卡通','141'),
array('新疆卫视','142'),
array('中国教育-1','143'),
array('西藏卫视','144'),


array('电竞天堂HD','78'),
array('电竞天堂HD','79'),
array('宝宝动画HD','81'),
array('青春动漫HD','83'),
array('百变课堂HD','84'),
array('热门剧场HD','85'),
array('谍战剧场HD','86'),
array('华语影院HD','87'),
array('星光影院HD','88'),
array('全球大片HD','89'),
array('健康养生HD','90'),
array('看天下精选HD','91'),
*/

    );
$nid4=sizeof($cid4);




for ($idm4 = 1; $idm4 <= $nid4; $idm4++){

 $idd4=$id4+$idm4;
   $chn.="<channel id=\"".$cid4[$idm4-1][0]."\"><display-name lang=\"zh\">".$cid4[$idm4-1][0]."</display-name></channel>\n";
}




for ($idm4 = 1; $idm4 <= $nid4; $idm4++){
 $idd4=$id4+$idm4;

//$urk4='https://www.kbro.com.tw/do/getpage_catvtable.php?callback=result&action=get_channelprogram&channelid='.$cid4[$idm4-1][0].'&showtime='.$dt1;

//$urk4='https://epg.bobo.itvsh.cn/epg/api/channel/channel_'.$cid4[$idm4-1][0].'.json';
$urk4='https://kylinapi.bbtv.cn/5g/v1/tv/programs/'.$cid4[$idm4-1][1].'?client=AcYLfgprMUuQu1rzaF2aGQ%3D%3D';

$hea=[
'Host: kylinapi.bbtv.cn', 
'packagename: cn.bc5g.sh ', 
'accept-encoding: gzip ', 
//cookie: _cl=4FIE4sUV1a1T2c%2BjZgVe4uKGi94kKz4lTX9VrLwSEV%2BM3tiW1cQ1ao%2BLE3UMKdnYi2Et3%2F8Hnv247cIUv9k8aMc0Xq8PJ6VkFfUi47k8Sz7c%2F9mDiHjiXyT1xBEFhMn82TaG1Lgu3JHxT7q2YmWPNGgqAK074KxVI870MdeYVHirmCZIgC3lukvQntMwXp96 
'user-agent: okhttp/3.12.0', 
];

$ch4=curl_init();
curl_setopt($ch4,CURLOPT_URL,$urk4);
curl_setopt($ch4,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch4,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch4,CURLOPT_RETURNTRANSFER,1);
 curl_setopt($ch4,CURLOPT_HTTPHEADER,$hea);

$rek4=curl_exec($ch4);
$rek4 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $rek4);// 適合php7
curl_close($ch4);
$rek4 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $rek4);// 適合php7
$rek4=stripslashes($rek4);

preg_match_all('|"endTime":(.*?),"available|i',$rek4,$ul4,PREG_SET_ORDER);//播放節目內容
preg_match_all('|"startTime":(.*?),"endTime|i',$rek4,$un4,PREG_SET_ORDER);//播放節目開始時間
preg_match_all('|"name":"(.*?)","startTime|i',$rek4,$uk4,PREG_SET_ORDER);//播放節目結束時間
$ryut4=count($uk4);



for ($k4 = 0; $k4 <= $ryut4-2; $k4++){
  //  $chn.="<programme start=\"".$un4[$k4+1][1].' +0800'."\" stop=\"".$ul4[$k4+1][1].' +0800'."\" channel=\"".$idd4."\">\n<title lang=\"zh\">".str_replace('<spanclass="live-btn">播放中</span>','',$uk4[$k4+1][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";


  $chn.="<programme start=\"".str_replace(' ','',str_replace('-','',str_replace(':','',date('Y-m-d H:i:s', $un4[$k4+1][1]))))." +0800\" stop=\"".str_replace(' ','',str_replace('-','',str_replace(':','',date('Y-m-d H:i:s', $ul4[$k4+1][1]))))." +0800\" channel=\"".$cid4[$idm4-1][0]."\">\n<title lang=\"zh\">".str_replace('<spanclass="live-btn">播放中</span>','',$uk4[$k4+1][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                 

                                                               }


                                                                                           }




$id22=900399;//起始节目编号
$cid22=array(
array('540','elta體育max 1'),

array('541','elta體育max 2'),

array('542','elta體育max 3'),
array('543','elta體育max 4'),
array('544','elta體育max5'),
array('545','elta體育max 6'),
array('546','elta體育max 7'),
array('547','elta體育max 8'),

array('108','elta日韓'),

);
$nid22=sizeof($cid22);
for ($idm22 = 1; $idm22 <= $nid22; $idm22++){
 $idd22=$id22+$idm22;
   $chn.="<channel id=\"".$cid22[$idm22-1][1]."\"><display-name lang=\"zh\">".$cid22[$idm22-1][1]."</display-name></channel>\n";       
}


$url22="https://piceltaott-elta.cdn.hinet.net/production/json/program_list/program_list_".$dt1.".json";

    $ch22 = curl_init();
    curl_setopt($ch22, CURLOPT_URL, $url22);
    curl_setopt($ch22, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch22, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch221, CURLOPT_SSL_VERIFYHOST, FALSE);
 curl_setopt($ch22,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re22 = curl_exec($ch22);
    curl_close($ch22);

$re22= preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re22);// 適合php7



//print $re221;



preg_match('/"540":\[\{(.*)\],"541"/i',$re22,$uk221);
preg_match('/"541":\[\{(.*)\],"542"/i',$re22,$uk222);
preg_match('/"542":\[\{(.*)\],"543"/i',$re22,$uk223);
preg_match('/"543":\[\{(.*)\],"544"/i',$re22,$uk224);
preg_match('/"544":\[\{(.*)\],"545"/i',$re22,$uk225);
preg_match('/"545":\[\{(.*)\],"546"/i',$re22,$uk226);
preg_match('/"546":\[\{(.*)\],"547"/i',$re22,$uk227);
preg_match('/"547":\[\{(.*)\],"102"/i',$re22,$uk228);
preg_match('/"108":\[\{(.*)\],"104"/i',$re22,$uk229);

preg_match_all('|"start_time":(.*?),|i',$uk221[1],$us221,PREG_SET_ORDER);//播放節目內容
preg_match_all('|"end_time":(.*?),|i',$uk221[1],$ue221,PREG_SET_ORDER);//播放節目開始時間
preg_match_all('|"program_desc":"(.*?)",|i',$uk221[1],$um221,PREG_SET_ORDER);//播放節目結束時間
$trm221=count($us221);
 for ($k22 = 0; $k22 < $trm221-1; $k22++) { 
  $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$us221[$k22][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$ue221[$k22][1])))).' +0800'."\" channel=\"elta體育max 1\">\n<title lang=\"zh\">". $um221[$k22][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}

preg_match_all('|"start_time":(.*?),|i',$uk222[1],$us222,PREG_SET_ORDER);//播放節目內容
preg_match_all('|"end_time":(.*?),|i',$uk222[1],$ue222,PREG_SET_ORDER);//播放節目開始時間
preg_match_all('|"program_desc":"(.*?)",|i',$uk222[1],$um222,PREG_SET_ORDER);//播放節目結束時間
$trm222=count($us222);
 for ($k32 = 0; $k32 < $trm222-1; $k32++) { 
  $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$us222[$k32][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$ue222[$k32][1])))).' +0800'."\" channel=\"elta體育max 2\">\n<title lang=\"zh\">". $um222[$k32][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}

preg_match_all('|"start_time":(.*?),|i',$uk223[1],$us223,PREG_SET_ORDER);//播放節目內容
preg_match_all('|"end_time":(.*?),|i',$uk223[1],$ue223,PREG_SET_ORDER);//播放節目開始時間
preg_match_all('|"program_desc":"(.*?)",|i',$uk223[1],$um223,PREG_SET_ORDER);//播放節目結束時間
$trm223=count($us223);
 for ($k42 = 0; $k42 < $trm223-1; $k42++) { 
  $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$us223[$k42][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$ue223[$k42][1])))).' +0800'."\" channel=\"elta體育max 3\">\n<title lang=\"zh\">". $um223[$k42][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}

preg_match_all('|"start_time":(.*?),|i',$uk224[1],$us224,PREG_SET_ORDER);//播放節目內容
preg_match_all('|"end_time":(.*?),|i',$uk224[1],$ue224,PREG_SET_ORDER);//播放節目開始時間
preg_match_all('|"program_desc":"(.*?)",|i',$uk224[1],$um224,PREG_SET_ORDER);//播放節目結束時間
$trm224=count($us224);
 for ($k52 = 0; $k52 < $trm224-1; $k52++) { 
  $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$us224[$k52][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$ue224[$k52][1])))).' +0800'."\" channel=\"elta體育max 4\">\n<title lang=\"zh\">". $um224[$k52][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}


preg_match_all('|"start_time":(.*?),|i',$uk225[1],$us225,PREG_SET_ORDER);//播放節目內容
preg_match_all('|"end_time":(.*?),|i',$uk225[1],$ue225,PREG_SET_ORDER);//播放節目開始時間
preg_match_all('|"program_desc":"(.*?)",|i',$uk225[1],$um225,PREG_SET_ORDER);//播放節目結束時間
$trm225=count($us225);
 for ($k62 = 0; $k62 < $trm225-1; $k62++) { 
  $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$us225[$k62][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$ue225[$k62][1])))).' +0800'."\" channel=\"elta體育max 5\">\n<title lang=\"zh\">". $um225[$k62][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}


preg_match_all('|"start_time":(.*?),|i',$uk226[1],$us226,PREG_SET_ORDER);//播放節目內容
preg_match_all('|"end_time":(.*?),|i',$uk226[1],$ue226,PREG_SET_ORDER);//播放節目開始時間
preg_match_all('|"program_desc":"(.*?)",|i',$uk226[1],$um226,PREG_SET_ORDER);//播放節目結束時間
$trm226=count($us226);
 for ($k72 = 0; $k72 < $trm226-1; $k72++) { 
  $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$us226[$k72][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$ue226[$k72][1])))).' +0800'."\" channel=\"elta體育max 6\">\n<title lang=\"zh\">". $um226[$k72][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}

preg_match_all('|"start_time":(.*?),|i',$uk227[1],$us227,PREG_SET_ORDER);//播放節目內容
preg_match_all('|"end_time":(.*?),|i',$uk227[1],$ue227,PREG_SET_ORDER);//播放節目開始時間
preg_match_all('|"program_desc":"(.*?)",|i',$uk227[1],$um227,PREG_SET_ORDER);//播放節目結束時間
$trm227=count($us227);
 for ($k82 = 0; $k82 < $trm227-1; $k82++) { 
  $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$us227[$k82][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$ue227[$k82][1])))).' +0800'."\" channel=\"elta體育max 7\">\n<title lang=\"zh\">". $um227[$k82][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}

preg_match_all('|"start_time":(.*?),|i',$uk228[1],$us228,PREG_SET_ORDER);//播放節目內容
preg_match_all('|"end_time":(.*?),|i',$uk228[1],$ue228,PREG_SET_ORDER);//播放節目開始時間
preg_match_all('|"program_desc":"(.*?)",|i',$uk228[1],$um228,PREG_SET_ORDER);//播放節目結束時間
$trm228=count($us228);
 for ($k92 = 0; $k92 < $trm228-1; $k92++) { 
  $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$us228[$k92][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$ue228[$k92][1])))).' +0800'."\" channel=\"elta體育max 8\">\n<title lang=\"zh\">". $um228[$k92][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}

preg_match_all('|"start_time":(.*?),|i',$uk229[1],$us229,PREG_SET_ORDER);//播放節目內容
preg_match_all('|"end_time":(.*?),|i',$uk229[1],$ue229,PREG_SET_ORDER);//播放節目開始時間
preg_match_all('|"program_desc":"(.*?)",|i',$uk229[1],$um229,PREG_SET_ORDER);//播放節目結束時間
$trm229=count($us229);
 for ($k102 = 0; $k102 < $trm229-1; $k102++) { 
  $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",
$us229[$k102][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$ue229[$k102][1])))).' +0800'."\" channel=\"elta日韓\">\n<title lang=\"zh\">". $um229[$k102][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}



$url221="https://piceltaott-elta.cdn.hinet.net/production/json/program_list/program_list_".$dt2.".json";

    $ch221 = curl_init();
    curl_setopt($ch221, CURLOPT_URL, $url221);
    curl_setopt($ch221, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch221, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch221, CURLOPT_SSL_VERIFYHOST, FALSE);
 curl_setopt($ch221,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re221 = curl_exec($ch221);
    curl_close($ch221);

$re221= preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re221);// 適合php7



//print $re221;



preg_match('/"540":\[\{(.*)\],"541"/i',$re221,$uk1221);
preg_match('/"541":\[\{(.*)\],"542"/i',$re221,$uk1222);
preg_match('/"542":\[\{(.*)\],"543"/i',$re221,$uk1223);
preg_match('/"543":\[\{(.*)\],"544"/i',$re221,$uk1224);
preg_match('/"544":\[\{(.*)\],"545"/i',$re221,$uk1225);
preg_match('/"545":\[\{(.*)\],"546"/i',$re221,$uk1226);
preg_match('/"546":\[\{(.*)\],"547"/i',$re221,$uk1227);
preg_match('/"547":\[\{(.*)\],"102"/i',$re221,$uk1228);
preg_match('/"108":\[\{(.*)\],"104"/i',$re221,$uk1229);

preg_match_all('|"start_time":(.*?),|i',$uk1221[1],$us1221,PREG_SET_ORDER);//播放節目內容
preg_match_all('|"end_time":(.*?),|i',$uk1221[1],$ue1221,PREG_SET_ORDER);//播放節目開始時間
preg_match_all('|"program_desc":"(.*?)",|i',$uk1221[1],$um1221,PREG_SET_ORDER);//播放節目結束時間
$trm1221=count($us1221);
 for ($k122 = 0; $k122 < $trm1221-1; $k122++) { 
  $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$us1221[$k122][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$ue1221[$k122][1])))).' +0800'."\" channel=\"elta體育max 1\">\n<title lang=\"zh\">". $um1221[$k122][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}

preg_match_all('|"start_time":(.*?),|i',$uk1222[1],$us1222,PREG_SET_ORDER);//播放節目內容
preg_match_all('|"end_time":(.*?),|i',$uk1222[1],$ue1222,PREG_SET_ORDER);//播放節目開始時間
preg_match_all('|"program_desc":"(.*?)",|i',$uk1222[1],$um1222,PREG_SET_ORDER);//播放節目結束時間
$trm1222=count($us1222);
 for ($k322 = 0; $k322 < $trm1222-1; $k322++) { 
  $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$us1222[$k322][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$ue1222[$k322][1])))).' +0800'."\" channel=\"elta體育max 2\">\n<title lang=\"zh\">". $um1222[$k322][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}

preg_match_all('|"start_time":(.*?),|i',$uk1223[1],$us1223,PREG_SET_ORDER);//播放節目內容
preg_match_all('|"end_time":(.*?),|i',$uk1223[1],$ue1223,PREG_SET_ORDER);//播放節目開始時間
preg_match_all('|"program_desc":"(.*?)",|i',$uk1223[1],$um1223,PREG_SET_ORDER);//播放節目結束時間
$trm1223=count($us1223);
 for ($k422 = 0; $k422 < $trm1223-1; $k422++) { 
  $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$us1223[$k422][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$ue1223[$k422][1])))).' +0800'."\" channel=\"elta體育max 3\">\n<title lang=\"zh\">". $um1223[$k422][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}

preg_match_all('|"start_time":(.*?),|i',$uk1224[1],$us1224,PREG_SET_ORDER);//播放節目內容
preg_match_all('|"end_time":(.*?),|i',$uk1224[1],$ue1224,PREG_SET_ORDER);//播放節目開始時間
preg_match_all('|"program_desc":"(.*?)",|i',$uk1224[1],$um1224,PREG_SET_ORDER);//播放節目結束時間
$trm1224=count($us1224);
 for ($k522 = 0; $k522 < $trm1224-1; $k522++) { 
  $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$us1224[$k522][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$ue1224[$k522][1])))).' +0800'."\" channel=\"elta體育max 4\">\n<title lang=\"zh\">". $um1224[$k522][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}


preg_match_all('|"start_time":(.*?),|i',$uk1225[1],$us1225,PREG_SET_ORDER);//播放節目內容
preg_match_all('|"end_time":(.*?),|i',$uk1225[1],$ue1225,PREG_SET_ORDER);//播放節目開始時間
preg_match_all('|"program_desc":"(.*?)",|i',$uk1225[1],$um1225,PREG_SET_ORDER);//播放節目結束時間
$trm1225=count($us1225);
 for ($k622 = 0; $k622 < $trm1225-1; $k622++) { 
  $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$us1225[$k622][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$ue1225[$k622][1])))).' +0800'."\" channel=\"elta體育max 5\">\n<title lang=\"zh\">". $um1225[$k622][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}


preg_match_all('|"start_time":(.*?),|i',$uk1226[1],$us1226,PREG_SET_ORDER);//播放節目內容
preg_match_all('|"end_time":(.*?),|i',$uk1226[1],$ue1226,PREG_SET_ORDER);//播放節目開始時間
preg_match_all('|"program_desc":"(.*?)",|i',$uk1226[1],$um1226,PREG_SET_ORDER);//播放節目結束時間
$trm1226=count($us1226);
 for ($k722 = 0; $k722 < $trm1226-1; $k722++) { 
  $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$us1226[$k722][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$ue1226[$k722][1])))).' +0800'."\" channel=\"elta體育max 6\">\n<title lang=\"zh\">". $um1226[$k722][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}

preg_match_all('|"start_time":(.*?),|i',$uk1227[1],$us1227,PREG_SET_ORDER);//播放節目內容
preg_match_all('|"end_time":(.*?),|i',$uk1227[1],$ue1227,PREG_SET_ORDER);//播放節目開始時間
preg_match_all('|"program_desc":"(.*?)",|i',$uk1227[1],$um1227,PREG_SET_ORDER);//播放節目結束時間
$trm1227=count($us1227);
 for ($k822 = 0; $k822 < $trm1227-1; $k822++) { 
  $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$us1227[$k822][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$ue1227[$k822][1])))).' +0800'."\" channel=\"elta體育max 7\">\n<title lang=\"zh\">". $um1227[$k822][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}

preg_match_all('|"start_time":(.*?),|i',$uk1228[1],$us1228,PREG_SET_ORDER);//播放節目內容
preg_match_all('|"end_time":(.*?),|i',$uk1228[1],$ue1228,PREG_SET_ORDER);//播放節目開始時間
preg_match_all('|"program_desc":"(.*?)",|i',$uk1228[1],$um1228,PREG_SET_ORDER);//播放節目結束時間
$trm1228=count($us1228);
 for ($k922 = 0; $k922 < $trm1228-1; $k922++) { 
  $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$us1228[$k922][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$ue1228[$k922][1])))).' +0800'."\" channel=\"elta體育max 8\">\n<title lang=\"zh\">". $um1228[$k922][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}

preg_match_all('|"start_time":(.*?),|i',$uk1229[1],$us1229,PREG_SET_ORDER);//播放節目內容
preg_match_all('|"end_time":(.*?),|i',$uk1229[1],$ue1229,PREG_SET_ORDER);//播放節目開始時間
preg_match_all('|"program_desc":"(.*?)",|i',$uk1229[1],$um1229,PREG_SET_ORDER);//播放節目結束時間
$trm1229=count($us1229);
 for ($k1022 = 0; $k1022 < $trm1229-1; $k1022++) { 
  $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",
$us1229[$k1022][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$ue1229[$k1022][1])))).' +0800'."\" channel=\"elta日韓\">\n<title lang=\"zh\">". $um1229[$k1022][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}


 $chn.="</tv>\n";
//print  $chn;

file_put_contents($fp, $chn);

?>
