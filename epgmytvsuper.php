<?php
header( 'Content-Type: text/plain; charset=UTF-8');
//header( 'Content-Type:text /html; charset=UTF-8');
ini_set("max_execution_time", "3000000");
//htaccess php_value max_execution_time 0;
ini_set('date.timezone','Asia/Shanghai');
$fp="epgmytvsuper.xml";//压缩版本的扩展名后加.gz
$dt1=date('Ymd');//獲取當前日期
$dt2=date('Ymd',time()+24*3600);//第二天日期
$dt21=date('Ymd',time()+48*3600);//第三天日期
$dt22=date('Ymd',time()-24*3600);//前天日期
$dt3=date('Ymd',time()+7*24*3600);
$dt4=date("Y/n/j");//獲取當前日期
$dt5=date('Y/n/j',time()+24*3600);//第二天日期
$dt7=date('Y');//獲取當前日期
$dt6=date('YmdHi',time());
$dt11=date('Y-m-d');
$time111=strtotime(date('Y-m-d',time()))*1000;
$dt12=date('Y-m-d',time()+24*3600);
$dt10=date('Y-m-d',time()-24*3600);
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



$dt1=date('Ymd');//獲取當前日期
$dt2=date('Ymd',time()+24*3600);//第二天日期
$w1=date("w");//當前第幾周

$chn="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<!DOCTYPE tv SYSTEM \"http://api.torrent-tv.ru/xmltv.dtd\">\n<tv generator-info-name=\"秋哥綜合\" generator-info-url=\"https://www.tdm.com.mo/c_tv/?ch=Satellite\">\n";


$id4=100200;
$cid4=array(
array('cmclassic','tv','天映经典香港'),

array('celestialmovies','com','天映频道马来西亚'),
array('cmplus-tv','com','cmplus新加坡'),
);
$nid4=sizeof($cid4);

for ($idm4 = 1; $idm4 <= $nid4; $idm4++){
 $idd4=$id4+$idm4;
   $chn.="<channel id=\"".$cid4[$idm4-1][2]."\"><display-name lang=\"zh\">".$cid4[$idm4-1][2]."</display-name></channel>\n";
}

for ($idm4 = 1; $idm4 <= $nid4; $idm4++){
$idd4=$id4+$idm4;
    $url4='https://www.'.$cid4[$idm4-1][0].'.'.$cid4[$idm4-1][1].'/schedule.php?lang=tc&date/'.$dt11;
     $ch4 = curl_init ();
curl_setopt ( $ch4, CURLOPT_URL, $url4  );
curl_setopt($ch4,CURLOPT_USERAGENT,'Mozilla/5.0 (Linux; Android 8.1.0; JKM-AL00b) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Mobile Safari/537.36):');
curl_setopt($ch4,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch4,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt ( $ch4, CURLOPT_RETURNTRANSFER, 1 );
$hea4=array(
'Host: www.'.$cid4[$idm4-1][0].'.'.$cid4[$idm4-1][0],
'Connection: keep-alive',
'Upgrade-Insecure-Requests: 1',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0',
'Referer: '.  $url4,
//'Accept-Encoding: gzip, deflate, br, zstd',
//'Accept-Language: en-US,en;q=0.9,zh-CN;q=0.8,zh;q=0.7,en-GB;q=0.6',
);
curl_setopt ( $ch4, CURLOPT_HEADER, $hea4 );
//curl_setopt($ch4, CURLOPT_COOKIE, $cookie4);
curl_setopt($ch4,CURLOPT_ENCODING,'Vary: Accept-Encoding');
//curl_setopt($ch4,CURLOPT_USERAGENT, " user-agent:Mozilla/5.0 (Windows NT 6.1; rv:62.0) Gecko/20100101 Firefox/62.0");//浏览器头信息
$re4 = curl_exec ( $ch4 );
//$re4=str_replace('00:00','24:00',$re4);
curl_close ( $ch4 );
$re4 = preg_replace('/\s(?=)/', '',$re4);
//$re4 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re4);// 適合php7
preg_match_all('|<pclass="programme-title">(.*?)</p>|i',$re4,$un4,PREG_SET_ORDER);//播放節目名称
preg_match_all('/schedule-time">(.*?)<\/div>/i',$re4,$um4,PREG_SET_ORDER);//播放時間
preg_match_all('/schedule-description">(.*?)<\/div>/i',$re4,$uk4,PREG_SET_ORDER);//播放節目介绍
$trm4=count($un4);
 for ($k4 = 1; $k4 <= $trm4-1; $k4++) { 
    // $chn.="<programme start=\"".$dt1.str_replace(':','',DateTime::createFromFormat('h:i A', $um4[$k4-1][1])->format('H:i')).'00 +0700'."\" stop=\"".$dt1.str_replace(':','',DateTime::createFromFormat('h:i A', $um4[$k4][1])->format('H:i')).'00 +0700'."\" channel=\"".$cid4[$idm4-1][2]."\">\n<title lang=\"zh\">".str_replace('<spanclass="live-btn">播放中</span>','',$un4[$k4-1][1])."</title>\n<desc lang=\"zh\">".$uk4[$k4-1][1]." </desc>\n</programme>\n";
       $chn.="<programme start=\"".$dt1.date("Hi", strtotime($um4[$k4-1][1])).'00 +0800'."\" stop=\"".$dt1.date("Hi", strtotime($um4[$k4][1])).'00 +0800'."\" channel=\"".$cid4[$idm4-1][2]."\">\n<title lang=\"zh\">".str_replace('<spanclass="live-btn">播放中</span>','',$un4[$k4-1][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
       

                                                                                                                                       }

 $url41='https://www.'.$cid4[$idm4-1][0].'.'.$cid4[$idm4-1][1].'/schedule.php?lang=tc&date/'.$dt12;
     $ch41 = curl_init ();
curl_setopt ( $ch41, CURLOPT_URL, $url41  );
curl_setopt($ch41,CURLOPT_USERAGENT,'Mozilla/5.0 (Linux; Android 8.1.0; JKM-AL00b) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Mobile Safari/537.36):');
curl_setopt($ch41,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch41,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt ( $ch41, CURLOPT_RETURNTRANSFER, 1 );
$hea41=array(
'Host: www.'.$cid4[$idm4-1][0].'.'.$cid4[$idm4-1][0],
'Connection: keep-alive',
'Upgrade-Insecure-Requests: 1',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0',
'Referer: '.  $url41,
//'Accept-Encoding: gzip, deflate, br, zstd',
//'Accept-Language: en-US,en;q=0.9,zh-CN;q=0.8,zh;q=0.7,en-GB;q=0.6',
);
curl_setopt ( $ch41, CURLOPT_HEADER, $hea41 );
//curl_setopt($ch4, CURLOPT_COOKIE, $cookie4);
curl_setopt($ch41,CURLOPT_ENCODING,'Vary: Accept-Encoding');
//curl_setopt($ch41,CURLOPT_USERAGENT, " user-agent:Mozilla/5.0 (Windows NT 6.1; rv:62.0) Gecko/20100101 Firefox/62.0");//浏览器头信息
$re41 = curl_exec ( $ch41 );
//$re41=str_replace('00:00','24:00',$re4);
curl_close ( $ch41 );
$re41 = preg_replace('/\s(?=)/', '',$re41);
//$re41 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re41);// 適合php7
preg_match_all('|<pclass="programme-title">(.*?)</p>|i',$re41,$un41,PREG_SET_ORDER);//播放節目名称
preg_match_all('/schedule-time">(.*?)<\/div>/i',$re41,$um41,PREG_SET_ORDER);//播放時間
preg_match_all('/schedule-description">(.*?)<\/div>/i',$re41,$uk41,PREG_SET_ORDER);//播放節目介绍
$trm41=count($un41);
//$chn.="<programme start=\"".$dt1.str_replace(':','',DateTime::createFromFormat('h:i A', $um4[$trm4-1][1])->format('H:i')).'00 +0700'."\" stop=\"".$dt2.str_replace(':','',DateTime::createFromFormat('h:i A', $um41[0][1])->format('H:i')).'00 +0700'."\" channel=\"".$cid4[$idm4-1][2]."\">\n<title lang=\"zh\">".str_replace('<spanclass="live-btn">播放中</span>','',$un4[$trm4-1][1])."</title>\n<desc lang=\"zh\">".$uk4[$k4-1][1]." </desc>\n</programme>\n";
$chn.="<programme start=\"".$dt1.date("Hi", strtotime($um4[$trm41-1][1])).'00 +0800'."\" stop=\"".$dt2.date("Hi", strtotime($um41[0][1])).'00 +0800'."\" channel=\"".$cid4[$idm4-1][2]."\">\n<title lang=\"zh\">".str_replace('<spanclass="live-btn">播放中</span>','',$un4[$trm41-1][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
       


 for ($k41 = 1; $k41 <= $trm41-1; $k41++) { 
    // $chn.="<programme start=\"".$dt2.str_replace(':','',DateTime::createFromFormat('h:i A', $um41[$k41-1][1])->format('H:i')).'00 +0700'."\" stop=\"".$dt2.str_replace(':','',DateTime::createFromFormat('h:i A', $um41[$k41][1])->format('H:i')).'00 +0700'."\" channel=\"".$cid4[$idm4-1][2]."\">\n<title lang=\"zh\">".str_replace('<spanclass="live-btn">播放中</span>','',$un41[$k41-1][1])."</title>\n<desc lang=\"zh\">".$uk41[$k41-1][1]." </desc>\n</programme>\n";
       $chn.="<programme start=\"".$dt2.date("Hi", strtotime($um41[$k41-1][1])).'00 +0800'."\" stop=\"".$dt2.date("Hi", strtotime($um41[$k41][1])).'00 +0800'."\" channel=\"".$cid4[$idm4-1][2]."\">\n<title lang=\"zh\">".str_replace('<spanclass="live-btn">播放中</span>','',$un41[$k41-1][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
       

                                                                                                                                       }

//$chn.="<programme start=\"".$dt2.str_replace(':','',DateTime::createFromFormat('h:i A', $um41[$trm41-1][1])->format('H:i')).'00 +0700'."\" stop=\"".$dt2.'240000 +0700'."\" channel=\"".$cid4[$idm4-1][2]."\">\n<title lang=\"zh\">".str_replace('<spanclass="live-btn">播放中</span>','',$un41[$trm41-1][1])."</title>\n<desc lang=\"zh\">".$uk41[$k41-1][1]." </desc>\n</programme>\n";
$chn.="<programme start=\"".$dt2.date("Hi", strtotime($um41[$trm41-1][1])).'00 +0800'."\" stop=\"".$dt2.'240000 +0800'."\" channel=\"".$cid4[$idm4-1][2]."\">\n<title lang=\"zh\">".str_replace('<spanclass="live-btn">播放中</span>','',$un4[$k4-1][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
       

}




$idn5=69999;
$cid5=array(
array('CWIN','SUPER FREE'),
array('C18','myTV SUPER 18台'),
array('C28','28 AI 智慧賽馬'),
array('TVG','黃金翡翠台'),
array('J','翡翠台'),
array('B','TVB-Plus'),
array('C','無綫新聞台'),
array('P','明珠台'),
array('CTVC','千禧經典台'),
array('CTVS','亞洲劇台'),
array('CDR3','華語劇台'),
array('TVO','黃金華劇台'),
array('CTVE','娛樂新聞台'),
//array('CWIN','綜藝旅遊台'),
array('CCOC','戲曲台'),
array('KID','SUPER-Kids-Channel'),
array('ZOO','ZooMoo'),
array('CNIKO','Nickelodeon'),
array('CNIJR','Nick Jr'),
array('CCLM','粵語片台'),
array('CMAM','美亞電影台'),
array('CTHR','Thrill'),
array('CCCM','天映經典頻道'),
array('CMC','中國電影頻道'),
array('CRTX','ROCK Action'),
array('POPC','PopC'),


array('CKIX','KIX'),

array('LNH','Love Nature HD'),
array('LN4','Love Nature 4K'),
array('SMS','Global Trekker'),

array('CRTE','ROCK 綜藝娛樂'),
array('CAXN','AXN'),
array('CGD','CGTN-(中國環球電視網)記錄頻道'),
array('CGE','CGTN-(中國環球電視網)英語頻道'),


array('CANI','Animax'),
array('CJTV','tvN'),
array('CTS1','無線衛星亞洲台'),
array('CRE','創世電視'),
array('FBX','FASHION ONE'),
array('CMEZ','Mezzo Live HD'),
array('CC1','中央電視台綜合頻道-(港澳版)'),


array('DTV','東方衛視海外版'),

array('PCC','鳳凰中文'),
array('PIN','鳳凰資訊'),
array('PHK','鳳凰香港'),
array('CMN1','神州新聞台'),
array('CTN2','TVBS新聞台'),
array('CCNA','亞洲新聞台'),
array('CJAZ','半島電視台英語頻道'),
array('CF24','France 24 '),
array('CDW1','DW新聞台'),
array('CNHK','NHK World-Japan'),
array('CARI','Arirang TV'),



array('EVT1','myTV SUPER直播足球1台'),
array('EVT2','myTV SUPER直播足球2台'),
array('EVT3','myTV SUPER直播足球3台'),
array('EVT4','myTV SUPER直播足球4台'),
array('EVT5','myTV SUPER直播足球5台'),
array('EVT6','myTV SUPER直播足球6台'),
array('EVT5','myTV SUPER直播足球5台'),
array('EVT5','myTV SUPER直播足球5台'),
array('EVT5','myTV SUPER直播足球6台'),
array('EV10','2024年WBSC五人制棒球世界盃直播1台'),
array('EV11','2024年WBSC五人制棒球世界盃直播2台'),
array('TEST','測試頻道'),
 );

$nid5=sizeof($cid5);

for ($idm5 = 1; $idm5 <= $nid5; $idm5++){
 $idd5=$idn5+$idm5;
   $chn.="<channel id=\"".$cid5[$idm5-1][1]."\"><display-name lang=\"zh\">".$cid5[$idm5-1][1]."</display-name></channel>\n";

                                         }
for ($idm5 = 1; $idm5 <= $nid5; $idm5++){
 
$idd5=$idn5+$idm5;
  $url5='https://content-api.mytvsuper.com/v1/epg?platform=web&country_code=TW&network_code='.$cid5[$idm5-1][0].'&from='.$dt1.'&to='.$dt1;
//https://content-api.mytvsuper.com/v1/epg?platform=web&country_code=TW&network_code=EVT1&from=20240912&to=20240919   

 $ch5 = curl_init();
    curl_setopt($ch5, CURLOPT_URL, $url5);
    curl_setopt($ch5, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch5, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch5, CURLOPT_SSL_VERIFYHOST, FALSE);
     curl_setopt($ch5,CURLOPT_ENCODING,'Vary: Accept-Encoding');
   
 $re5 = curl_exec($ch5);
   $re5=compress_html($re5);
  $re5=str_replace('&','&amp;',$re5);
    curl_close($ch5);
//print $re5;

preg_match_all('/"start_datetime":"(.*?)",/i',$re5,$um5,PREG_SET_ORDER);//播放時間
preg_match_all('/programme_title_tc":"(.*?)",/i',$re5,$un5,PREG_SET_ORDER);//播放節目
preg_match_all('/"episode_synopsis_tc":"(.*?)",/i',$re5,$uk5,PREG_SET_ORDER);//播放節目介绍
 $url51='https://content-api.mytvsuper.com/v1/epg?platform=web&country_code=TW&network_code='.$cid5[$idm5-1][0].'&from='.$dt2.'&to='.$dt2;


    $ch51 = curl_init();
    curl_setopt($ch51, CURLOPT_URL, $url51);
    curl_setopt($ch51, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch51, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch51, CURLOPT_SSL_VERIFYHOST, FALSE);
     curl_setopt($ch51,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re51 = curl_exec($ch51);
   $re51=compress_html($re51);
  $re51=str_replace('&','&amp;',$re51);
    curl_close($ch51);
//print  $re51;

preg_match_all('/"start_datetime":"(.*?)",/i',$re51,$um51,PREG_SET_ORDER);//播放時間
preg_match_all('/programme_title_tc":"(.*?)",/i',$re51,$un51,PREG_SET_ORDER);//播放節目
preg_match_all('/"episode_synopsis_tc":"(.*?)",/i',$re51,$uk51,PREG_SET_ORDER);//播放節目介绍

//print_r($um51);
//print_r($un51);
//print_r($uk51);


//$re5 = preg_replace('/\s(?=)/', '',$re5);
//preg_match('/divclass="epg-list(.*?)epg-list"/i',$re5,$u5);//


//print_r($um5);
//print_r($un5);
//print_r($uk5);



$trm5=count($um5);
for ($k5 = 1; $k5 <= $trm5-1 ; $k5++) {  


 $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',$um5[$k5-1][1]))).'00 +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',$um5[$k5][1]))).'00 +0800'."\" channel=\"".$cid5[$idm5-1][1]."\">\n<title lang=\"zh\">".str_replace('<','&lt;',str_replace('&','&amp;',str_replace('>',' &gt;',trim($un5[$k5-1][1]))))."</title>\n<desc lang=\"zh\">".str_replace('<','&lt;',str_replace('&','&amp;',str_replace('>',' &gt;',trim($uk5[$k5-1][1]))))." </desc>\n</programme>\n";
                                                                                                                              

   }                                                                                                                                                                                                                       

 
   $chn.="<programme start=\"".str_replace(':','',str_replace('-','',str_replace(' ','',$um5[$trm5-1][1]))).'00 +0800'."\" stop=\"".str_replace(':','',str_replace('-','',str_replace(' ','',$um51[0][1]))).'00 +0800'."\" channel=\"".$cid5[$idm5-1][1]."\">\n<title lang=\"zh\">".str_replace('<','&lt;',str_replace('&','&amp;',str_replace('>',' &gt;',trim($un5[$trm5-1][1]))))."</title>\n<desc lang=\"zh\">".str_replace('<','&lt;',str_replace('&','&amp;',str_replace('>',' &gt;',trim($uk5[$trm5-1][1]))))." </desc>\n</programme>\n";                                                                                                                                        








$trm51=count($um51);
for ($k51 = 1; $k51 <= $trm51-1 ; $k51++) {  
     
                                        
 $chn.="<programme start=\"".str_replace(':','',str_replace('-','',str_replace(' ','',$um51[$k51-1][1]))).'00 +0800'."\" stop=\"".str_replace(':','',str_replace('-','',str_replace(' ','',$um51[$k51][1]))).'00 +0800'."\" channel=\"".$cid5[$idm5-1][1]."\">\n<title lang=\"zh\">".str_replace('<','&lt;',str_replace('&','&amp;',str_replace('>',' &gt;',trim($un51[$k51-1][1]))))."</title>\n<desc lang=\"zh\">".str_replace('<','&lt;',str_replace('&','&amp;',str_replace('>',' &gt;',trim($uk51[$k51-1][1]))))."</desc>\n</programme>\n";
                                                                                           }                                                                                                                                                                                                                       

 
   $chn.="<programme start=\"".str_replace(':','',str_replace('-','',str_replace(' ','',$um51[$trm51-1][1]))).'00 +0800'."\" stop=\"".$dt21.'060000 +0800'."\" channel=\"".$cid5[$idm5-1][1]."\">\n<title lang=\"zh\">".str_replace('<','&lt;',str_replace('&','&amp;',str_replace('>',' &gt;',trim($un51[$trm51-1][1]))))."</title>\n<desc lang=\"zh\">".str_replace('<','&lt;',str_replace('&','&amp;',str_replace('>',' &gt;',trim($uk51[$trm51-1][1]))))." </desc>\n</programme>\n";                                                                                                     

}

  $chn.="<channel id=\"中天亞洲台\"><display-name lang=\"zh\">中天亞洲台</display-name></channel>\n";
$url79='https://asia-east2-ctitv-237901.cloudfunctions.net/ctitv-API-program-list?chid=a2&start='.$dt1.'&end='.$dt1.'&_=';
$ch79= curl_init ();
curl_setopt ( $ch79, CURLOPT_URL, $url79 );
curl_setopt ( $ch79, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch79,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch79,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch79,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:131.0) Gecko/20100101 Firefox/131.0');
//curl_setopt($ch79,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re79 = curl_exec($ch79);
   // $re79=str_replace('&','&amp;',$re79);
   curl_close($ch79);

//print $re79;

//$data79=json_decode($re79)->title;

$ryut79=count(json_decode($re79));
//print $ryut;

for ($k79 =0; $k79 < $ryut79; $k79++){



$title79=json_decode($re79)[$k79]->title;
$start79=json_decode($re79)[$k79]->start;
$end79=json_decode($re79)[$k79]->end;
   $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',$start79))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','', $end79))).' +0800'."\" channel=\"中天亞洲台\">\n<title lang=\"zh\">".$title79."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";



}

$url791='https://asia-east2-ctitv-237901.cloudfunctions.net/ctitv-API-program-list?chid=a2&start='.$dt1.'&end='.$dt2.'&_=';

$ch791= curl_init ();
curl_setopt ( $ch791, CURLOPT_URL, $url791 );
curl_setopt ( $ch791, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch791,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch791,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch791,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:131.0) Gecko/20100101 Firefox/131.0');
//curl_setopt($ch791,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re791 = curl_exec($ch791);
   // $re79=str_replace('&','&amp;',$re79);
   curl_close($ch791);
$ryut791=count(json_decode($re791));


for ($k791 =0; $k791 < $ryut791; $k791++){



$title791=json_decode($re791)[$k791]->title;
$start791=json_decode($re791)[$k791]->start;
$end791=json_decode($re791)[$k791]->end;
   $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',$start791))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','', $end791))).' +0800'."\" channel=\"中天亞洲台\">\n<title lang=\"zh\">".$title791."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";



}

//8inow
$id80=100579;//起始节目编号
$cid8=array(
array('096','viu6'),

array('099','ViuTV'),  
array('102','Viu 頻道'),  
 array('105','now 劇集'),
 array('106','video express rentnow'),
 array('108','nowjeli'),
 array('111','HBO Hits香港'),
 array('112','HBO Family香港'),
 array('113','CINEMAX香港'),
 array('114','HBO Signature香港'),
 array('115','HBO香港'),
 array('116','MOVIE MOVIE'),
 array('133','爆谷台'),
 array('138','Now爆谷星影台'),
 array('150','Animax香港'),
 array('155','tvN香港'),
 array('156','KBS World香港'),
 array('162','東森亞洲'),
  array('168','moov'),
 array('208','Discovery Asia香港'),
 array('209','Discovery Channel香港'),
 array('210','動物星球頻道香港'),
 array('211','Discovery 科學頻道香港'),
 array('212','DMAX香港'),
 array('213','TLC旅遊生活頻道香港'),
 array('217','Love Nature香港'),
 array('220','BBC Earth香港'),
 array('221','戶外頻道香港'),
 array('222','罪案 + 偵緝香港'),
 array('223','HISTORY香港'),
 array('316','CNN 國際新聞網絡香港'),
array('319','CNBC香港'),
array('320','BBC News香港'),
array('321','Bloomberg Television香港'),
array('322','亞洲新聞台香港'),
array('3231','Sky News香港'),
array('324','DW (English)香港'),
array('325','半島電視台英語頻道香港'),
array('326','euronews香港'),
array('327','France 24香港'),
array('328','NHK WORLD-JAPA香港'),
array('329','RT香港'),
array('330','中國環球電視網香港'),
array('331','now直播 '),
array('332','now新聞'),
array('333','now財經'),
array('336','now報價'),
array('338','第一財經'),
array('366','鳳凰資訊'),
array('367','鳳凰香港台'),
array('400','智叻樂園'),
array('548','鳳凰中文'),
array('368','香港衛視'),
array('371','東森亞洲新聞'),
array('440','DreamWorks 頻道香港'),
array('443','Cartoon Network香港'),
array('444','Nickelodeon香港'),
array('447','CBeebies香港'),
array('448','Moonbug香港'),
array('449','Nick Jr.香港'),
array('460','Da Vinc香港'),
array('502','BBC Lifestyle香港'),
array('512','AXN香港'),
array('517','ROCK Entertainment香港'),
array('525','Lifetime香港'),
array('526','Food Network香港'),
array('527','亞洲美食台香港'),
array('528','旅遊頻道香港'),
array('529','居家樂活頻道香港'),
array('535','Netflix香港'),
//array('538','中天亞洲台'),
array('540','深圳衛視香港'),
array('541','CCTV-1香港'),
array('542','CCTV-4香港'),
array('543','大灣區衛視香港'),
array('545','中央電視台新聞頻道香港'),
array('548','鳳凰衛視中文台'),
array('552','OneTV 綜合頻道'),
array('553','三沙衛視香港'),
array('555','浙江衛視香港'),
array('561','ABC Australia香港'),
array('600','now體育'),
array('611','now體育4k'),
array('612','now體育4k'),
array('613','now體育4k'),
array('620','Now Sports 英超TV'),
array('621','Now Sports 英超TV1'),
array('622','Now Sports 英超 TV2'),
array('623','Now Sports 英超 TV3'),
array('624','Now Sports 英超 TV4'),
array('625','Now Sports 英超 TV5'),
array('626','Now Sports 英超 TV6'),
array('627','Now Sports 英超 TV7'),
array('630','Now Sports Premier'),
array('631','Now Sports 1'),
array('632','Now Sports 2'),
array('633','Now Sports 3'),
array('634','Now Sports 4'),
array('635','Now Sports 5'),
array('636','Now Sports 6'),
array('637','Now Sports 7'),
array('638','beIN SPORTS 1'),
array('639','beIN SPORTS 2'),
array('640','MUTV'),
array('641','Now Sports 641'),
array('642','NBA'),
array('643','beIN SPORTS 3'),
array('644','beIN SPORTS 4'),
array('645','beIN SPORTS 5'),
array('646','beIN SPORTS 6'),
array('650','beIN SPORTS RUGBY'),
array('651','Now Sports 651'),
array('668','Now Sports 668'),
array('670','SPOTV'),
array('671','SPOTV2'),
array('674','Astro Cricket'),
array('679','Premier Sports'),
array('680','Now Sports plus'),
array('681','Now Sports 681'),
array('683','Now 高爾夫2'),
array('684','Now 高爾夫3'),
array('688','Lucky 688'),
array('711','NHK World Premium'),
array('713','TV5MONDE Style'),
array('714','TV5MONDE ASIE'),
array('715','France 24 (French)'),
array('720','GMA Pinoy TV'),
array('721','GMA Life T'),
array('725','TFC'),
array('771','Sony TV (India)'),
array('772','Sony MAX'),
array('774','Sony SAB'),
array('779','MTV India'),
array('780','COLORS'),
array('781','Zee Cinema International'),
array('782','Zee TV'),
array('785','Zee News'),
array('793','Star Gold'),
array('794','STAR PLUS'),
array('797','Star Bharat'),
array('900','成人節目資訊'),
array('901','冰火頻道'),
array('903','成人極品台'),

);


$nid8=sizeof($cid8);

for ($idm8 = 1; $idm8 <= $nid8; $idm8++){
 $idd8=$id80+$idm8;
   $chn.="<channel id=\"".$cid8[$idm8-1][1]."\"><display-name lang=\"zh\">".$cid8[$idm8-1][1]."</display-name></channel>\n";
                                         }

for ($idm8 = 1; $idm8 <= $nid8; $idm8++){
$cookie8='Cookie: __eoi=ID=dfc31ab44d7042c7:T=1720171157:RT=1720416942:S=AA-AfjZu3rh8SzbgMl-5j4p1JBCI; NOWSESSIONID=; NOW_SESSION=b0c4fc0487e5b349296f55d969de215610470549-NOWSESSIONID=&mupType=NORMAL&nowDollarBalance=0&isBinded=false&isMobileId=false&mobile=&isOTTMode=N&firstMupUser=false&is4K=false&isLogin=false&isMobileGuest=false&fsaType=&mupShow=login&lang=zh; LANG=zh';
$url8='https://nowplayer.now.com/tvguide/channeldetail/'.$cid8[$idm8-1][0].'/1?isfromchannel=false';
 
 $idd8=$id80+$idm8;

    $ch8 = curl_init();
    curl_setopt($ch8, CURLOPT_URL, $url8);
    curl_setopt($ch8, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch8, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch8, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch8, CURLOPT_TIMEOUT, 30); 
$hea8=array(
'Host: nowplayer.now.com',
'Connection: keep-alive',
'Cache-Control: max-age=0Cache-Control: max-age=0',
'Upgrade-Insecure-Requests: 1',
'DNT: 1',

//'Referer: https://nowplayer.now.com/tvguide?filterType=all',
'Referer: '.$url8,
);
curl_setopt ( $ch8, CURLOPT_HEADER, $hea8 );
curl_setopt($ch8,CURLOPT_USERAGENT,'Mozilla/5.0');
curl_setopt($ch8, CURLOPT_COOKIE, $cookie8);
curl_setopt($ch8,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re8 = curl_exec($ch8);
    curl_close($ch8);

 $re8 = preg_replace('/\s(?=)/', '',$re8);
 $re8=str_replace('&','&amp;',$re8);
$re8=str_replace('<ul>','',$re8);
$re8=str_replace('<spanclass="live-btn">播放中</span>','',$re8);
//$re8 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re8);// 適合php7



preg_match('/id\="day1"(.*)id\="day2"/i',$re8,$uk8);
//print $uk8[1];

preg_match_all('|<divclass="time">(.*?)</div>|i',$uk8[1],$um8,PREG_SET_ORDER);//播放時間
preg_match_all('|<divclass="prograam-name">(.*?)</div>|i',$uk8[1],$un8,PREG_SET_ORDER);//播放節目
$trm8=count($um8);


preg_match('/id\="day2"(.*)id\="day3"/i',$re8,$uk81);
//print $uk81[1];

preg_match_all('|<divclass="time">(.*?)</div>|i',$uk81[1],$um81,PREG_SET_ORDER);//播放時間
preg_match_all('|<divclass="prograam-name">(.*?)</div>|i',$uk81[1],$un81,PREG_SET_ORDER);//播放節目
$trm81=count($um81);

//$um8[][1]='12:00PM';

//$um81[][1]='12:00PM';
//輸入節目開始上午
//print_r($um8);
//print_r($um81);

for ($k8 =1; $k8 <= $trm8-2; $k8++) {
     
   
      $chn.="<programme start=\"".$dt1.date("Hi", strtotime($um8[$k8-1][1])).'00 +0800'."\" stop=\"".$dt1.date("Hi", strtotime($um8[$k8][1])).'00 +0800'."\" channel=\"".$cid8[$idm8-1][1]."\">\n<title lang=\"zh\">".str_replace('<spanclass="live-btn">播放中</span>','',$un8[$k8-1][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
       
                                                                                                                                       }
                                                              

$chn.="<programme start=\"".$dt1.date("Hi", strtotime($um8[$trm8-1][1])).'00 +0800'."\" stop=\"".$dt2.date("Hi", strtotime( $um81[0][1])).'00 +0800'."\" channel=\"".$cid8[$idm8-1][1]."\">\n<title lang=\"zh\">".str_replace('<spanclass="live-btn">播放中</span>','',$un8[$trm8-1][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";     

//$chn.="<programme start=\"".$dt1.str_replace(':','',DateTime::createFromFormat('h:i A', $um8[$trm8][1])->format('H:i')).'00 +0800'."\" stop=\"".$dt2.str_replace(':','',DateTime::createFromFormat('h:i A', $um81[0][1])->format('H:i')).'00 +0800'."\" channel=\"".$cid8[$idm8-1][1]."\">\n<title lang=\"zh\">".str_replace('<spanclass="live-btn">播放中</span>','',$un8[$trm8-1][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";     


for ($k81 =1; $k81 <= $trm81-1; $k81++) {
        $chn.="<programme start=\"".$dt2.date("Hi", strtotime($um81[$k81-1][1])).'00 +0800'."\" stop=\"".$dt2.date("Hi", strtotime($um81[$k81][1])).'00 +0800'."\" channel=\"".$cid8[$idm8-1][1]."\">\n<title lang=\"zh\">".str_replace('<spanclass="live-btn">播放中</span>','',$un81[$k81-1][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
       
                                                                                                                                       }
                                                              

$chn.="<programme start=\"".$dt2.date("Hi", strtotime($um81[$trm81-1][1])).'00 +0800'."\" stop=\"".$dt2.'240000 +0800'."\" channel=\"".$cid8[$idm8-1][1]."\">\n<title lang=\"zh\">".str_replace('<spanclass="live-btn">播放中</span>','',$un81[$trm81-1][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";     
     /*
      $chn.="<programme start=\"".$dt2.str_replace(':','',DateTime::createFromFormat('h:i A', $um81[$k81-1][1])->format('H:i')).'00 +0800'."\" stop=\"".$dt2.str_replace(':','',DateTime::createFromFormat('h:i A', $um81[$k81][1])->format('H:i')).'00 +0800'."\" channel=\"".$cid8[$idm8-1][1]."\">\n<title lang=\"zh\">".str_replace('<spanclass="live-btn">播放中</span>','',$un81[$k81-1][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
       
                                                                                                                                       }
                                                                   
//$chn.="<programme start=\"".$dt2.str_replace(':','',DateTime::createFromFormat('h:i A', $um81[$trm81-1][1])->format('H:i')).'00 +0800'."\" stop=\"".$dt2.'240000 +0800'."\" channel=\"".$cid8[$idm8-1][1]."\">\n<title lang=\"zh\">".str_replace('<spanclass="live-btn">播放中</span>','',$un81[$trm81-1][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";     
     
 */
                            
}
//新加坡mewatch
$id100=600000;//起始节目编号
$cid100=array(
 // array('186574',' Russia Today'),
  array('97098','Channel 5'),
 array('97104','Channel 8'),
 array('97084','Channel Suria'),
 array('97096','Channel Vasantham'),
 array('97072','CNA'),
 array('97129','Channel U'),
  // array('20695','EGG'),
array('97073','meWATCH LIVE 1'),
array('97078','meWATCH LIVE 2'),
array('98202','meWATCH LIVE 5'),


 //array('186574','oktolidays'),
array('158965','NOW 80s'),
array('158964','NOW 70s'),
array('158963','NOW ROCK'),
array('158962','trace urban'),
array('242036','GEM'),

array('98200','SPL01'),
array('98201','SPL02'),
array('15896','SPOTV Stadia'),

);

$nid100=sizeof($cid100);


for ($idm100= 1; $idm100 <= $nid100; $idm100++){
 $idd100=$id100+$idm100;
   $chn.="<channel id=\"".$cid100[$idm100-1][1]."\"><display-name lang=\"zh\">".$cid100[$idm100-1][1]."</display-name></channel>\n";
}



for ($idm100= 1; $idm100 <= $nid100; $idm100++){

$url98='https://cdn.mewatch.sg/api/schedules?channels='.$cid100[$idm100-1][0].'&date='.$dt10.'&duration=24&ff=idp%2Cldp%2Crpt%2Ccd&hour=16&intersect=true&lang=en&segments=all';
//https://cdn.mewatch.sg/api/schedules?channels=158965&date=2024-07-12&duration=24&ff=idp%2Cldp%2Crpt%2Ccd&hour=16&intersect=true&lang=en&segments=all

 $idd100=$id100+$idm100;
$ch98=curl_init();
curl_setopt($ch98,CURLOPT_URL,$url98);
curl_setopt($ch98,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch98,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch98,CURLOPT_RETURNTRANSFER,1);
$re98=curl_exec($ch98);
curl_close($ch98);
$re98 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re98);// 適合php7
$re98=str_replace('&','&amp;',$re98);
 
 $data98=json_decode($re98)[0]->schedules;
$tuu98=count($data98);

for ( $i98=0 ; $i98<=$tuu98-1 ; $i98++ ) {
$startDate98=json_decode($re98)[0]->schedules[$i98]->startDate;
$endDate98=json_decode($re98)[0]->schedules[$i98]->endDate;
$title98=json_decode($re98)[0]->schedules[$i98]->item->title;

$secondaryLanguageTitle98=json_decode($re98)[0]->schedules[$i98]->item->secondaryLanguageTitle;
$description99=json_decode($re98)[0]->schedules[$i98]->item->description;
$seasonNumber98=json_decode($re98)[0]->schedules[$i98]->classification->seasonNumber;
$episodeNumber98=json_decode($re98)[0]->schedules[$i98]->classification->episodeNumber;
$startDate98=str_replace('Z','',$startDate98);
$startDate98=str_replace('T','',$startDate98);
$startDate98=str_replace('-','',$startDate98);
$startDate98=str_replace(':','',$startDate98);
$endDate98=str_replace('T','',$endDate98);
$endDate98=str_replace('Z','',$endDate98);
$endDate98=str_replace(':','',$endDate98);
$endDate98=str_replace('-','',$endDate98);
$chn.="<programme start=\"".$startDate98.' +0000'."\" stop=\"".$endDate98.' +0000'."\" channel=\"".$cid100[$idm100-1][1]."\">\n<title lang=\"zh\">".$secondaryLanguageTitle98.$title98.$seasonNumber98.$episodeNumber98."</title>\n<desc lang=\"zh\">".$description98."</desc>\n</programme>\n";

}

$url99='https://cdn.mewatch.sg/api/schedules?channels='.$cid100[$idm100-1][0].'&date='.$dt11.'&duration=24&ff=idp%2Cldp%2Crpt%2Ccd&hour=16&intersect=true&lang=en&segments=all';
 $idd100=$id100+$idm100;
$ch99=curl_init();
curl_setopt($ch99,CURLOPT_URL,$url99);
curl_setopt($ch99,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch99,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch99,CURLOPT_RETURNTRANSFER,1);
$re99=curl_exec($ch99);
curl_close($ch99);
$re99 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re99);// 適合php7
//print $re ;
//$re99 = preg_replace('/\s(?=)/', '',$re);
 $re99=str_replace('&','&amp;',$re99);
 $data99=json_decode($re99)[0]->schedules;
$tuu99=count($data99);

for ( $i99=0 ; $i99<=$tuu99-1 ; $i99++ ) {
$startDate99=json_decode($re99)[0]->schedules[$i99]->startDate;
$endDate99=json_decode($re99)[0]->schedules[$i99]->endDate;
$title99=json_decode($re99)[0]->schedules[$i99]->item->title;
$secondaryLanguageTitle99=json_decode($re99)[0]->schedules[$i99]->item->secondaryLanguageTitle;
$description99=json_decode($re99)[0]->schedules[$i99]->item->description;
$seasonNumber99=json_decode($re99)[0]->schedules[$i99]->classification->seasonNumber;
$episodeNumber99=json_decode($re99)[0]->schedules[$i99]->classification->episodeNumber;
$startDate99=str_replace('Z','',$startDate99);
$startDate99=str_replace('T','',$startDate99);
$startDate99=str_replace('-','',$startDate99);
$startDate99=str_replace(':','',$startDate99);
$endDate99=str_replace('T','',$endDate99);
$endDate99=str_replace('Z','',$endDate99);
$endDate99=str_replace(':','',$endDate99);
$endDate99=str_replace('-','',$endDate99);
$chn.="<programme start=\"".$startDate99.' +0000'."\" stop=\"".$endDate99.' +0000'."\" channel=\"".$cid100[$idm100-1][1]."\">\n<title lang=\"zh\">".$secondaryLanguageTitle99.$title99.$seasonNumber99.$episodeNumber99."</title>\n<desc lang=\"zh\">".$description99."</desc>\n</programme>\n";

}


$url100='https://cdn.mewatch.sg/api/schedules?channels='.$cid100[$idm100-1][0].'&date='.$dt12.'&duration=24&ff=idp%2Cldp%2Crpt%2Ccd&hour=16&intersect=true&lang=en&segments=all';
 $idd100=$id100+$idm100;
$ch100=curl_init();
curl_setopt($ch100,CURLOPT_URL,$url100);
curl_setopt($ch100,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch100,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch100,CURLOPT_RETURNTRANSFER,1);
$re100=curl_exec($ch100);
curl_close($ch100);
$re100 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re100);// 適合php7
//print $re ;
//$re99 = preg_replace('/\s(?=)/', '',$re);
 $re100=str_replace('&','&amp;',$re100);
 $data100=json_decode($re100)[0]->schedules;
$tuu100=count($data100);

for ( $i100=0 ; $i100<=$tuu100-1 ; $i100++ ) {
$startDate100=json_decode($re100)[0]->schedules[$i100]->startDate;
$endDate100=json_decode($re100)[0]->schedules[$i100]->endDate;
$title100=json_decode($re100)[0]->schedules[$i100]->item->title;

$secondaryLanguageTitle100=json_decode($re100)[0]->schedules[$i100]->item->secondaryLanguageTitle;
$description100=json_decode($re100)[0]->schedules[$i100]->item->description;
$seasonNumber100=json_decode($re100)[0]->schedules[$i00]->classification->seasonNumber;
$episodeNumber100=json_decode($re100)[0]->schedules[$i100]->classification->episodeNumber;
$startDate100=str_replace('Z','',$startDate100);
$startDate100=str_replace('T','',$startDate100);
$startDate100=str_replace('-','',$startDate100);
$startDate100=str_replace(':','',$startDate100);
$endDate100=str_replace('T','',$endDate100);
$endDate100=str_replace('Z','',$endDate100);
$endDate100=str_replace(':','',$endDate100);
$endDate100=str_replace('-','',$endDate100);
$chn.="<programme start=\"".$startDate100.' +0000'."\" stop=\"".$endDate100.' +0000'."\" channel=\"".$cid100[$idm100-1][1]."\">\n<title lang=\"zh\">".$secondaryLanguageTitle100.$seasonNumber100.$title100.$episodeNumber100."</title>\n<desc lang=\"zh\">".$description100."</desc>\n</programme>\n";

}

}


$id280=100579;//起始节目编号
$cid28=array(
 array('2072','Hami韓劇台(免費)'),
 array('2073','Hami韓劇台'), 
array('1516','三立新聞'), 

 array('2087','Hami日漫台'),  
 array('1830','Hami羽球台'), 
 array('1831','Hami羽球台(免費)'),  
array('2083','Hami卡通台'),  
array('2017','Hami MLB 台'),  
array('1868','空中英語教室'),  

array('1546','amc電影台'),
array('2039','AsianFoodNetwork亞洲美食頻道'),
array('1510','BBCEarth'),
array('1513','BBCLifestyle'),
array('1527','BBCNEWS'),
array('2015','BloombergTV'),
array('1889','CARTOONITO'),
array('1710','CATCHPLAY電影台'),
array('1711','CBeebies'),
array('1752','CNEX紀實頻道'),
array('2003','CNN國際新聞台'),
array('2004','CNN頭條新聞台(HLN)'),
array('2002','CN卡通頻道'),
array('1924','DiscoveryAsia'),
array('1514','DiscoveryDMAX'),
array('1511','DiscoveryEVE'),
array('1512','Discovery科學台(Science)'),
array('1836','DREAMWORKS'),
array('1771','EUROSPORT'),
array('1850','GinxTV電競頻道'),
array('1971','GlobalTrekker'),
array('2017','HamiMLB台'),
array('2083','Hami卡通台(免費)'),
array('2087','Hami日漫台(免費)'),
array('1830','Hami羽球台'),
array('1831','Hami羽球台(免費)'),
array('2073','Hami韓劇台'),
array('2072','Hami韓劇台(免費)'),
array('1901','HGTV居家樂活頻道'),
array('1859','History'),
array('2056','INULTRA'),
array('1970','Lifetime'),
array('1914','LiveABC互動英語頻道'),
array('1867','LOVENATURE'),
array('1757','MezzoLive'),
array('1865','momo親子台'),
array('1974','NHK新聞資訊台'),
array('1939','NickJr.'),
array('1876','PETCLUBTV'),
array('2005','ROCKAction'),
array('1860','ROCKEntertainment'),
array('1861','ROCKExtreme'),
array('1766','TechStorm'),
array('1759','TRACESportStars'),
array('1758','TraceUrban'),
array('1756','TravelChannel'),
array('1561','TVBS'),
array('1560','TVBS新聞台'),
array('1559','TVBS歡樂台'),
array('1789','TVBS精采台'),
array('1884','tvN'),
array('1888','WarnerTV'),
array('1690','三立iNEWS'),
array('1516','三立新聞台'),
array('1562','中天新聞台'),
array('1508','中央氣象署影音頻道'),
array('1568','中視主頻'),
array('1567','中視新聞台'),
array('1566','中視經典台'),
array('1541','中視菁采台'),
array('2070','亞洲旅遊台'),
array('1793','人間衛視'),
array('1528','八大精彩台'),
array('1549','八大綜藝台'),
array('1555','公視戲劇'),
array('1973','博斯無限二台'),
array('1882','博斯無限台'),
array('1903','博斯網球台'),
array('1845','博斯運動一台'),
array('1846','博斯高球一台'),
array('1972','博斯魅力網'),
array('1539','古典音樂台'),
array('1519','台視主頻'),
array('1565','台視新聞台'),
array('1790','台視綜合台'),
array('1791','台視財經台'),
array('1736','國會頻道1'),
array('1735','國會頻道2'),
array('1538','大愛電視'),
array('2069','寰宇新聞'),
array('1851','尼克兒童頻道'),
array('1689','影迷數位電影台'),
array('1520','愛放ifun動漫台'),
array('1906','愛放ifun動漫台(免費)'),
array('1518','愛爾達娛樂台'),
array('1517','愛爾達影劇台'),
array('2079','愛爾達日韓台'),
array('1573','愛爾達綜合台'),
array('1744','愛爾達體育1台'),
array('1743','愛爾達體育2台'),
array('1745','愛爾達體育3台'),
array('2055','愛爾達體育4台'),
array('1853','愛爾達體育MAX1台'),
array('1854','愛爾達體育MAX2台'),
array('1855','愛爾達體育MAX3台'),
array('1856','愛爾達體育MAX4台'),
array('1564','東森新聞台'),
array('1563','東森財經台'),
array('1509','東森購物頻道'),
array('1579','民視台灣台'),
array('1551','民視影劇台'),
array('1533','民視新聞台'),
array('1534','民視無線台'),
array('1535','民視第一台'),
array('1550','民視綜藝台'),
array('1553','空中英語教室'),
array('1868','空中英語教室(免費)'),
array('2019','罪案偵緝頻道'),
array('1825','華藝MBC綜合台'),
array('1558','華藝MBC綜合台(免費)'),
array('1526','華視主頻'),
array('1569','華視新聞資訊台'),
array('2081','華視新聞資訊台(免費)'),
array('1548','豬哥亮歌廳秀'),
array('1547','達文西頻道'),
array('1635','采昌影劇台'),
array('1915','金光布袋戲'),
array('2006','鏡電視新聞台'),
array('1938','電影原聲台CMusic'),
array('1652','靖天日本台'),
array('1649','非凡商業台'),
array('1650','非凡新聞台'),
array('1904','龍華偶像'),
array('1542','龍華影劇'),
array('1839','龍華戲劇'),
array('1840','龍華電影'),

);

$nid28=sizeof($cid28);

for ($idm28 = 1; $idm28 <= $nid28; $idm28++){
 $idd28=$id280+$idm28;
   $chn.="<channel id=\"".$cid28[$idm28-1][1]."\"><display-name lang=\"zh\">".$cid28[$idm28-1][1]."</display-name></channel>\n";
                                         }
for ($idm28 = 1; $idm28 <= $nid28; $idm28++){
$url28='https://hamivideo.hinet.net/channel/epg.do';
 $idd28=$id280+$idm28;

$data28="contentPk=OTT_LIVE_000000".$cid28[$idm28-1][0]."&date=$dt11";

    $ch28 = curl_init();
    curl_setopt($ch28, CURLOPT_URL, $url28);
    curl_setopt($ch28, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch28, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch28, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch28, CURLOPT_TIMEOUT, 30); // CURLOPT_TIMEOUT_MS
curl_setopt ( $ch28, CURLOPT_POST, 1 );
curl_setopt ( $ch28, CURLOPT_POSTFIELDS, $data28 );
curl_setopt($ch28,CURLOPT_USERAGENT,'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36');
//curl_setopt($ch28, CURLOPT_COOKIE, $cookie28);
curl_setopt($ch28,CURLOPT_ENCODING,'Vary: Accept-Encoding');
$re28 = curl_exec($ch28);
curl_close($ch28);
//print $re28;
//}

preg_match_all('/programName":"(.*?)","startTime/i',$re28,$um28,PREG_SET_ORDER);//播放節目
preg_match_all('/startTime":"(.*?)","endTime/i',$re28,$us28,PREG_SET_ORDER);//播放時間
preg_match_all('/endTime":"(.*?)","tsId/i',$re28,$ue28,PREG_SET_ORDER);//播放時間
$trm28=sizeof($um28);
//輸入節目開始上午
for ($k28 = 0; $k28 <= $trm28-1; $k28++) {
     $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",
$us28[$k28][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$ue28[$k28][1])))).' +0800'."\" channel=\"".$cid28[$idm28-1][1]."\">\n<title lang=\"zh\">". $um28[$k28][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
       
                                                                                                                                       }



$data281="contentPk=OTT_LIVE_000000".$cid28[$idm28-1][0]."&date=$dt12";
// $idd281=$id280+$idm281;
    $ch281 = curl_init();
    curl_setopt($ch281, CURLOPT_URL, $url28);
    curl_setopt($ch281, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch281, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch281, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch281, CURLOPT_TIMEOUT, 30); // CURLOPT_TIMEOUT_MS
curl_setopt ( $ch281, CURLOPT_POST, 1 );
curl_setopt ( $ch281, CURLOPT_POSTFIELDS, $data281 );
curl_setopt($ch281,CURLOPT_USERAGENT,'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36');

curl_setopt($ch281,CURLOPT_ENCODING,'Vary: Accept-Encoding');
$re281 = curl_exec($ch281);
curl_close($ch281);
//print $re28;
//}

preg_match_all('/programName":"(.*?)","startTime/i',$re281,$um281,PREG_SET_ORDER);//播放節目
preg_match_all('/startTime":"(.*?)","endTime/i',$re281,$us281,PREG_SET_ORDER);//播放時間
preg_match_all('/endTime":"(.*?)","tsId/i',$re281,$ue281,PREG_SET_ORDER);//播放時間
$trm281=sizeof($um281);
//輸入節目開始上午
for ($k281 = 0; $k281 <= $trm281-1; $k281++) {
     $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",
$us281[$k281][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',date("Y-m-d H:i:s",$ue281[$k281][1])))).' +0800'."\" channel=\"".$cid28[$idm28-1][1]."\">\n<title lang=\"zh\">". $um281[$k281][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
       
                                                                                                                                       }
                                                                                      }
/*
//央视频
$id79=799999;//起始节目编号
$cid79=array(
   
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
array('600002521','江苏卫视'),
array('600002483','东方卫视'),
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
array('600002484','福建卫视'),
array('600002490','贵州卫视'),
array('600002503','江西卫视'),
array('600002505','辽宁卫视'),
array('600002532','安徽卫视'),
array('600002493','河北卫视'),
array('600002513','山东卫视'),
array('600152137','天津卫视'),
array('600152138','新疆卫视'),

array('600171827','中国教育电视台-1'),
array('600170344','兵团卫视'),



);

$nid79=sizeof($cid79);
for ($idm79 = 1; $idm79 <= $nid79; $idm79++){
 $idd79=$id79+$idm79;
   $chn.="<channel id=\"".$cid79[$idm79-1][1]."\"><display-name lang=\"zh\">".$cid79[$idm79-1][1]."</display-name></channel>\n";
         
}

for ($idm79 = 1; $idm79 <= $nid79; $idm79++){

        // https://capi.yangshipin.cn/api/yspepg/program/600001818/20241025
//$url79='https://h5access.yangshipin.cn/web/tv_program?targetId=1&vappid=59306155&vsecret=b42702bf7309a179d102f3d51b1add2fda0bc7ada64cb801&raw=1&type=by_day&pid='.$cid79[$idm79-1][0].'&day='.$dt11;


$url79='https://capi.yangshipin.cn/api/yspepg/program/'.$cid79[$idm79-1][0].'/'.$dt1;


$idd79=$id79+$idm79;
$ch79= curl_init ();
curl_setopt ( $ch79, CURLOPT_URL, $url79 );
curl_setopt ( $ch79, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch79,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch79,CURLOPT_SSL_VERIFYHOST,false);

$hea79=[

'Host: capi.yangshipin.cn',
'Connection: keep-alive',

'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0',


'Origin: https://www.yangshipin.cn',

'Referer: https://www.yangshipin.cn/',
'Accept-Encoding: gzip',
//'Accept-Language: en-US,en;q=0.9,zh-CN;q=0.8,zh;q=0.7,en-GB;q=0.6',


];
curl_setopt( $ch79, CURLOPT_HTTPHEADER, $hea79);

curl_setopt($ch79,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:131.0) Gecko/20100101 Firefox/131.0');
//curl_setopt($ch79,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re79 = curl_exec($ch79);
   // $re79=str_replace('&','&amp;',$re79);
   curl_close($ch79);
//$re79 =preg_replace('/[^\p{L}\p{N}\s\-:]+/u', '', $re79);
//$re79=utf8_
//print $re79;
$pattern = '/\x08(\d{8})\x12(.*?)\x18.*?\x05(\d{2}:\d{3})\x05(\d{2}:\d{3})/';

// 使用 preg_match_all 提取数据
preg_match_all($pattern,$re79, $matches, PREG_SET_ORDER);

// 输出提取的信息
foreach ($matches as $match) {
    $id = $match[1];         // ID
    $title = $match[2];      // 标题
    $startTime = $match[3];  // 开始时间
    $endTime = $match[4];    // 结束时间

$chn .= "<programme start=\"" .$dt1.str_replace(' ', '', str_replace(':', '', substr($startTime, 0, strlen($startTime) - 1))) . '00 +0800" stop="' .$dt1.str_replace(' ', '', str_replace(':', '', substr($endTime, 0, strlen($endTime) - 1))) . '00 +0800" channel="' . $cid79[$idm79-1][1] . "\">\n<title lang=\"zh\">" . substr($title, 1) . "</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
    //echo "标题: $title, 开始时间: $startTime, 结束时间: $endTime\n";
}
$url791='https://capi.yangshipin.cn/api/yspepg/program/'.$cid79[$idm79-1][0].'/'.$dt2;
$idd79=$id79+$idm79;
$ch791= curl_init ();
curl_setopt ( $ch791, CURLOPT_URL, $url791 );
curl_setopt ( $ch791, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch791,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch791,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt( $ch791, CURLOPT_HTTPHEADER, $hea79);
curl_setopt($ch791,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:131.0) Gecko/20100101 Firefox/131.0');
//curl_setopt($ch791,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re791 = curl_exec($ch791);
   // $re79=str_replace('&','&amp;',$re79);
   curl_close($ch791);


// 使用 preg_match_all 提取数据
preg_match_all($pattern,$re791, $matches1, PREG_SET_ORDER);


// 输出提取的信息
foreach ($matches1 as $match1) {
    $id1 = $match1[1];         // ID
    $title1 = $match1[2];      // 标题
    $startTime1 = $match1[3];  // 开始时间
    $endTime1 = $match1[4];    // 结束时间

$chn .= "<programme start=\"" .$dt2.str_replace(' ', '', str_replace(':', '', substr($startTime1, 0, strlen($startTime1) - 1))) . '00 +0800" stop="' .$dt2.str_replace(' ', '', str_replace(':', '', substr($endTime1, 0, strlen($endTime1) - 1))) . '00 +0800" channel="' . $cid79[$idm79-1][1] . "\">\n<title lang=\"zh\">" . substr($title1, 1) . "</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
    //echo "标题: $title, 开始时间: $startTime, 结束时间: $endTime\n";
}

}

//看看新闻官网直播
$chn.="<channel id=\"看看新聞直播\"><display-name lang=\"zh\">看看新聞直播</display-name></channel>\n";

$urlk13="https://live.kankanews.com/";
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
*/
//电视猫

$id290=90000;
$cid29=array(
/*
array( 'SHIJIAZHUANG' ,'SHIJIAZHUANG1' ,'石家庄新闻','-','/'),

array( 'SHIJIAZHUANG' ,'SHIJIAZHUANG2' ,'石家庄城市','-','/'),

array( 'SHIJIAZHUANG' ,'SHIJIAZHUANG3' ,'石家庄娱乐','-','/'),
array( 'SHIJIAZHUANG' ,'SHIJIAZHUANG4' ,'石家庄都市','-','/'),
*/
array( 'digital' ,'SITV-YULE' ,'魅力足球','/','_'),
array( 'CCTV' ,'CCTVEUROPE' ,'CCTV-4欧洲频道','-','/'),
array( 'CCTV' ,'CCTVAMERICAS' ,'CCTV-4美洲频道','-','/'),
array( 'digital' ,'SITV-SPORTS' ,'劲爆体育','/','_'),




);

$nid29=sizeof($cid29);
 for ($id29 = 1; $id29 <= $nid29; $id29++){
    $idd29=$id29+$id290;
    $chn.="<channel id=\"".$cid29[$id29-1][2]."\"><display-name lang=\"zh\">".$cid29[$id29-1][2]."</display-name></channel>\n";
 }

for ($id29 = 1; $id29<= $nid29; $id29++){

$idd29=$id29+$id290;

$url29='https://www.tvmao.com/program'.$cid29[$id29-1][4].$cid29[$id29-1][0].$cid29[$id29-1][3].$cid29[$id29-1][1].'-w'.$w1.'.html';
$ch29 = curl_init();
curl_setopt($ch29, CURLOPT_URL, $url29);
 curl_setopt($ch29, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch29, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch29, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch29,CURLOPT_ENCODING,'Vary: Accept-Encoding');
$re29=curl_exec($ch29);
curl_close($ch29);
$re29 = preg_replace('/\s(?=)/', '',$re29);
//$re29 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re29);// 適合php7
$re29 =str_replace('<divclass="cur_player"><span>正在播出','',$re29);
preg_match('/周日(.*)查看更多<\/a>/i',$re29,$uk29);
$uk29[1]=str_replace('</a>','',$uk29[1]);
preg_match_all('|<spanclass="p_show">(.*?)</span>|i',$uk29[1],$un29,PREG_SET_ORDER);//播放節目內容
preg_match_all('|<spanclass="am">(.*?)</span>|i',$uk29[1],$um29,PREG_SET_ORDER);//播放節目时间
for ($k29 = 0; $k29 <=count($um29)-2; $k29++){
$chn.="<programme start=\"".$dt1.str_replace(':','',$um29[$k29][1]).'00 +0800'."\" stop=\"".$dt1.str_replace(':','',$um29[$k29+1][1]).'00 +0800'."\" channel=\"".$cid29[$id29-1][2]."\">\n<title lang=\"zh\">". preg_replace('/<[^>]+>/', '',$un29[$k29][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                                                               }


$url292='https://www.tvmao.com/servlet/accessToken?p=channelEpg';
$ch292 = curl_init();
curl_setopt($ch292, CURLOPT_URL, $url292);
 curl_setopt($ch292, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch292, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch292, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch292, CURLOPT_HTTPHEADER, array(
'Host: www.tvmao.com',
'Connection: keep-alive',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36 Edg/129.0.0.0',
'Referer: '.$url29,
//'Cookie: xsuid=e7508145-b5a5-4b2a-a1e2-8092fd347670; xsuid_time=2024-10-11; tvm_channel_province=BTV@台湾; FAD=1',
));
$re292=curl_exec($ch292);
curl_close($ch292);
$token29=json_decode($re292)[1];
//print $token29;

$postfield291='tc='.$cid29[$id29-1][0].'&cc='.$cid29[$id29-1][1].'&w='.$w1.'&token='.$token29;
$url291='https://www.tvmao.com/servlet/channelEpg';
$ch291 = curl_init();
curl_setopt($ch291, CURLOPT_URL, $url291);
 curl_setopt($ch291, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch291, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch291, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch291, CURLOPT_HTTPHEADER, array(
'Host: www.tvmao.com',
'Connection: keep-alive',
//'Content-Length: 251',
'X-Requested-With: XMLHttpRequest',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36 Edg/129.0.0.0',
'Content-Type: application/x-www-form-urlencoded',
'Origin: https://www.tvmao.com',
'Referer: '.$url29,
));
curl_setopt ( $ch291, CURLOPT_POST, 1 );
curl_setopt ( $ch291, CURLOPT_POSTFIELDS, $postfield291);
//curl_setopt($ch291, CURLOPT_COOKIE, $cookie291);
curl_setopt($ch291,CURLOPT_ENCODING,'Vary: Accept-Encoding');
$re291=curl_exec($ch291);
curl_close($ch291);
$re291 = preg_replace('/\s(?=)/', '',$re291);
//$re291 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re291);// 適合php7
$re291=stripslashes($re291);
//print $re291;


$re291=str_replace('</a>','',$re291);
$re291 =str_replace('<divclass="cur_player"><span>正在播出','',$re291);
preg_match_all('|<spanclass="pm">(.*?)</span>|i',$re291,$um291,PREG_SET_ORDER);//播放时间
preg_match_all('|<spanclass=\"p_show\">(.*?)</span>|i',$re291,$un291,PREG_SET_ORDER);//播放節目節目內容
//print_r($un291);
//print_r($um291);
$chn.="<programme start=\"".$dt1.str_replace(':','',$um29[count($um29)-1][1]).'00 +0800'."\" stop=\"".$dt1.str_replace(':','',$um291[0][1]).'00 +0800'."\" channel=\"".$cid29[$id29-1][2]."\">\n<title lang=\"zh\">". preg_replace('/<[^>]+>/', '',$un29[count($um29)-1][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";


//$chn.="<programme start=\"".$dt1.'120000 +0800'."\" stop=\"".$dt1.str_replace(':','',$um291[0][1]).'00 +0800'."\" channel=\"".$cid29[$id29-1][2]."\">\n<title lang=\"zh\">". preg_replace('/<[^>]+>/', '',$un291[0][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
for ($k291 = 0; $k291 <=count($um291)-2; $k291++){
$chn.="<programme start=\"".$dt1.str_replace(':','',$um291[$k291][1]).'00 +0800'."\" stop=\"".$dt1.str_replace(':','',$um291[$k291+1][1]).'00 +0800'."\" channel=\"".$cid29[$id29-1][2]."\">\n<title lang=\"zh\">". preg_replace('/<[^>]+>/', '',$un291[$k291][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                                                               }
$chn.="<programme start=\"".$dt1.str_replace(':','',$um291[count($um291)-1][1]).'00 +0800'."\" stop=\"".$dt1.'240000 +0800'."\" channel=\"".$cid29[$id29-1][2]."\">\n<title lang=\"zh\">". preg_replace('/<[^>]+>/', '',$un291[count($um291)-1][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";


$url2911='https://www.tvmao.com/program'.$cid29[$id29-1][4].$cid29[$id29-1][0].$cid29[$id29-1][3].$cid29[$id29-1][1].'-w'.$w2.'.html';
//print $url29;

$ch2911 = curl_init();
curl_setopt($ch2911, CURLOPT_URL, $url2911);
 curl_setopt($ch2911, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch2911, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch2911, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch2911,CURLOPT_ENCODING,'Vary: Accept-Encoding');
$re2911=curl_exec($ch2911);
curl_close($ch2911);
$re2911 = preg_replace('/\s(?=)/', '',$re2911);
$re2911 =str_replace('<divclass="cur_player"><span>正在播出','',$re2911);
//$re2911 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re2911);// 適合php7
preg_match('/周日(.*)查看更多<\/a>/i',$re2911,$uk2911);
$uk2911[1]=str_replace('</a>','',$uk2911[1]);

preg_match_all('|<spanclass="p_show">(.*?)</span>|i',$uk2911[1],$un2911,PREG_SET_ORDER);//播放節目內容
preg_match_all('|<spanclass="am">(.*?)</span>|i',$uk2911[1],$um2911,PREG_SET_ORDER);//播放節目时间
//print_r($un29);
//print_r($um29);
for ($k2911 = 1; $k2911 <=count($um2911)-2; $k2911++){
$chn.="<programme start=\"".$dt2.str_replace(':','',$um2911[$k2911-1][1]).'00 +0800'."\" stop=\"".$dt2.str_replace(':','',$um2911[$k2911][1]).'00 +0800'."\" channel=\"".$cid29[$id29-1][2]."\">\n<title lang=\"zh\">". preg_replace('/<[^>]+>/', '',$un2911[$k2911-1][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                                                               }

$url29112='https://www.tvmao.com/servlet/accessToken?p=channelEpg';
$ch29112 = curl_init();
curl_setopt($ch29112, CURLOPT_URL, $url29112);
 curl_setopt($ch29112, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch29112, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch29112, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch29112, CURLOPT_HTTPHEADER, array(
'Host: www.tvmao.com',
'Connection: keep-alive',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36 Edg/129.0.0.0',
'Referer: '.$url2911,
//'Cookie: xsuid=e7508145-b5a5-4b2a-a1e2-8092fd347670; xsuid_time=2024-10-11; tvm_channel_province=BTV@台湾; FAD=1',
));
$re29112=curl_exec($ch29112);
curl_close($ch29112);
$token2911=json_decode($re29112)[1];
$postfield29111='tc='.$cid29[$id29-1][0].'&cc='.$cid29[$id29-1][1].'&w='.$w2.'&token='.$token2911;

$url29111='https://www.tvmao.com/servlet/channelEpg';
$ch29111 = curl_init();
curl_setopt($ch29111, CURLOPT_URL, $url29111);
 curl_setopt($ch29111, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch29111, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch29111, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch29111, CURLOPT_HTTPHEADER, array(
'Host: www.tvmao.com',
'Connection: keep-alive',
//'Content-Length: 251',
'X-Requested-With: XMLHttpRequest',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36 Edg/129.0.0.0',
//'Content-Type: application/x-www-form-urlencoded',
'Origin: https://www.tvmao.com',
'Referer: '.$url2911,
));
curl_setopt ( $ch29111, CURLOPT_POST, 1 );
curl_setopt ( $ch29111, CURLOPT_POSTFIELDS, $postfield29111);
//curl_setopt($ch29111, CURLOPT_COOKIE, $cookie29111);
curl_setopt($ch29111,CURLOPT_ENCODING,'Vary: Accept-Encoding');
$re29111=curl_exec($ch29111);
curl_close($ch29111);
$re29111 = preg_replace('/\s(?=)/', '',$re29111);
//$re29111 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re29111);// 適合php7
$re29111=stripslashes($re29111);
//print $re291;

$re29111 =str_replace('<divclass="cur_player"><span>正在播出','',$re29111);
$re29111=str_replace('</a>','',$re29111);
preg_match_all('|<spanclass="pm">(.*?)</span>|i',$re29111,$um29111,PREG_SET_ORDER);//播放时间
preg_match_all('|<spanclass=\"p_show\">(.*?)</span>|i',$re29111,$un29111,PREG_SET_ORDER);//播放節目節目內容


$chn.="<programme start=\"".$dt2.str_replace(':','',$um2911[count($um2911)-1][1]).'00 +0800'."\" stop=\"".$dt2.str_replace(':','',$um29111[0][1]).'00 +0800'."\" channel=\"".$cid29[$id29-1][2]."\">\n<title lang=\"zh\">". preg_replace('/<[^>]+>/', '',$un2911[count($um2911)-2][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";


for ($k29111 = 0; $k29111 <=count($um29111)-2; $k29111++){
$chn.="<programme start=\"".$dt2.str_replace(':','',$um29111[$k29111][1]).'00 +0800'."\" stop=\"".$dt2.str_replace(':','',$um29111[$k29111+1][1]).'00 +0800'."\" channel=\"".$cid29[$id29-1][2]."\">\n<title lang=\"zh\">". preg_replace('/<[^>]+>/', '',$un29111[$k29111][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                                                               }
$chn.="<programme start=\"".$dt2.str_replace(':','',$um29111[count($um29111)-1][1]).'00 +0800'."\" stop=\"".$dt2.'240000 +0800'."\" channel=\"".$cid29[$id29-1][2]."\">\n<title lang=\"zh\">". preg_replace('/<[^>]+>/', '',$un29111[count($um29111)-1][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";

}     




$idn10=100642;//起始节目编号
$cid10=array(
array('新视觉','新视觉'),
array('劲爆体育','劲爆体育'),
array('海峡卫视','海峡卫视'),

);
$nid10=sizeof($cid10);
for ($idm10 = 1; $idm10 <= $nid10; $idm10++){
 $idd10=$idn10+$idm10;
   $chn.="<channel id=\"".$cid10[$idm10-1][1]."\"><display-name lang=\"zh\">".$cid10[$idm10-1][1]."</display-name></channel>\n";
}


for ($idm10 = 1; $idm10 <= $nid10; $idm10++){

 $idd10=$idn10+$idm10;
     $uu10=$dt1-20241010;
$url10="https://sp1.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?query=".$cid10[$idm10-1][1]."&co=data[tabid=1]&resource_id=12520";
//print $url10;
//https://sp1.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?query=劲爆体育&co=data[tabid=1]&resource_id=12520&t=1730798019316&ie=utf8&oe=gbk&cb=op_aladdin_callback&format=json&tn=baidu&cb=jQuery1102003895703937199224_1730797921462&_=1730797921465
//https://sp1.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?query=%E5%8A%B2%E7%88%86%E4%BD%93%E8%82%B2&co=data%5Btabid%3D2%5D&resource_id=12520&t=1730797955748&ie=utf8&oe=gbk&cb=op_aladdin_callback&format=json&tn=baidu&cb=jQuery1102003895703937199224_1730797921462&_=1730797921464
       //https://sp1.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?query=%E9%AD%85%E5%8A%9B%E8%B6%B3%E7%90%83&co=data%5Btabid%3D2%5D&resource_id=12520&t=1728794577406&ie=utf8&oe=gbk&cb=op_aladdin_callback&format=json&tn=baidu&cb=jQuery110205800927578049866_1728794577072&_=1728794577073
      //https://sp1.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?query=%E5%8A%B2%E7%88%86%E4%BD%93%E8%82%B2&co=data%5Btabid%3D2%5D&resource_id=12520&t=1730767673396&ie=utf8&oe=gbk&cb=op_aladdin_callback&format=json&tn=baidu&cb=jQuery110208641844689647804_1730767601031&_=1730767601033
    $ch10 = curl_init();
    curl_setopt($ch10, CURLOPT_URL, $url10);
    curl_setopt($ch10, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch10, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch10, CURLOPT_SSL_VERIFYHOST, FALSE);
   curl_setopt($ch10,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re10 = curl_exec($ch10);
    curl_close($ch10);
$re10 = preg_replace('/\s(?=)/', '',$re10);
$re10 =str_replace('/', '', $re10); 

$re10=mb_convert_encoding($re10,"UTF-8" ,"gb2312" ); 
preg_match_all('/,"title":"(.*?)","tvname/i', $re10,$um10,PREG_SET_ORDER);//播放節目
preg_match_all('/"times":"(.*?)","title/i', $re10,$un10,PREG_SET_ORDER);//播放時間
$trm10=sizeof($um10);
 for ($k10 = 1; $k10 <= $trm10-1; $k10++) {
   $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace(' ','',$un10[$k10-1][1]))).'00 +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',$un10[$k10][1]))).'00 +0800'."\" channel=\"".$cid10[$idm10-1][1]."\">\n<title lang=\"zh\">".$um10[$k10-1][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}



$url101="https://sp1.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?query=".$cid10[$idm10-1][1]."&co=data[tabid=2]&resource_id=12520";

//$url101="https://sp1.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?query=".$cid10[$idm10-1][1]."&co=data[tabid=($dt2-20241011)]&resource_id=12520";


    $ch101 = curl_init();
    curl_setopt($ch101, CURLOPT_URL, $url101);
    curl_setopt($ch101, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch101, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch101, CURLOPT_SSL_VERIFYHOST, FALSE);
   curl_setopt($ch101,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re101 = curl_exec($ch101);
    curl_close($ch101);
$re101 = preg_replace('/\s(?=)/', '',$re101);
$re101 =str_replace('/', '', $re101); 
$re101=mb_convert_encoding($re101,"UTF-8" ,"gb2312" ); 
//$re101 =iconv('GB2312', 'UTF-8', $re101); 
//print $re101;

preg_match_all('/,"title":"(.*?)","tvname/i', $re101,$um101,PREG_SET_ORDER);//播放節目
preg_match_all('/"times":"(.*?)","title/i', $re101,$un101,PREG_SET_ORDER);//播放時間
$trm101=sizeof($um101);

  $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',$un10[$trm10-1][1]))).'00 +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace(' ','',$un101[0][1]))).'00 +0800'."\" channel=\"".$cid10[$idm10-1][1]."\">\n<title lang=\"zh\">". $um10[$trm10-1][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";



 for ($k101 = 1; $k101 <= $trm101-1; $k101++) {
   $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace(' ','',$un101[$k101-1][1]))).'00 +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',$un101[$k101][1]))).'00 +0800'."\" channel=\"".$cid10[$idm10-1][1]."\">\n<title lang=\"zh\">".$um101[$k101-1][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}
  $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',$un101[$trm101-1][1]))).'00 +0800'."\" stop=\"".$dt2.'240000 +0800'."\" channel=\"".$cid10[$idm10-1][1]."\">\n<title lang=\"zh\">". $um101[$trm101-1][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";

}




$idn11=100652;//起始节目编号
$cid11=array(
array('chcna','CMC 北美频道'),
array('cmchk','CMC 香港频道'),
array('chchome','CHC 家庭影院'),
array('dypdepg','CCTV6 电影频道'),
array('xlepg','1905App 热血·影院'),
array('apptvepg','1905App 环球经典'),
);
$nid11=sizeof($cid11);


for ($idm11 = 1; $idm11 <= $nid11; $idm11++){


 $idd11=$idn11+$idm11;
   $chn.="<channel id=\"".$cid11[$idm11-1][1]."\"><display-name lang=\"zh\">".$cid11[$idm11-1][1]."</display-name></channel>\n";
}


for ($idm11 = 1; $idm11 <= $nid11; $idm11++){

 $idd11=$idn11+$idm11;

$url11="https://www.1905.com/cctv6/program/".$cid11[$idm11-1][0]."/list/";
//print $url10;

    $ch11 = curl_init();
    curl_setopt($ch11, CURLOPT_URL, $url11);
    curl_setopt($ch11, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch11, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch11, CURLOPT_SSL_VERIFYHOST, FALSE);
$headers11=[
'Host: www.1905.com',
'Connection: keep-alive',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36 Edg/129.0.0.0',

'Referer: '.$url11,
'Accept-Encoding: gzip, deflate, br, zstd',
'Accept-Language: en-US,en;q=0.9,zh-CN;q=0.8,zh;q=0.7,en-GB;q=0.6',

];
curl_setopt($ch11, CURLOPT_HTTPHEADER, $headers11);
    curl_setopt($ch11,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re11 = curl_exec($ch11);
    curl_close($ch11);
$re11 = preg_replace('/\s(?=)/', '',$re11);

$re11=str_replace('<em>(00:00-12:00)</em>','',$re11);
$re11=str_replace('<em>(12:00-24:00)</em>','',$re11);

preg_match('|<p>节目单(.*?)<!--footer-->|i',$re11,$rk11);
//print $re10;

//print $rk10[1];



preg_match_all('/<lidata-id="(.*?)"data-caturl/i',$rk11[1],$un11,PREG_SET_ORDER);//播放時間
preg_match_all('|<em>(.*?)</em>|i',$rk11[1],$ul11,PREG_SET_ORDER);//播放節目



$trm11=sizeof($ul11);
 

for ($k11 = 1; $k11 <= $trm11-1; $k11++) {
   $chn.="<programme start=\"".date("YmdHis",$un11[$k11-1][1]).' +0800'."\" stop=\"".date("YmdHis", $un11[$k11][1]).' +0800'."\" channel=\"" . $cid11[$idm11-1][1] . "\">\n<title lang=\"zh\">". $ul11[$k11-1][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";

}

$chn.="<programme start=\"".date("YmdHis",$un11[$trm11-1][1]).' +0800'."\" stop=\"".$dt1.'235900 +0800'."\" channel=\"" . $cid11[$idm11-1][1] . "\">\n<title lang=\"zh\">". $ul11[$k11-1][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";


}

 //22河南电视台
//$time22=time();
$id22=101035;//起始节目编号
$cid22=array(
array('145','河南卫视'),
array('149','河南新闻'),
array('141','河南都市'),
array('146','河南民生'),
array('147','河南法制'),
array('151','河南公共'),
array('152','河南乡村'),
array('148','河南电视剧'),
array('154','河南梨园戏曲'),
array('155','河南文物宝库'),
array('156','河南武术'),
array('157','河南晴彩中原'),
array('163','河南移动戏曲'),
array('183','河南象世界'),
array('150','河南欢腾购物'),
array('194','国学时代界'),






);
$nid22=sizeof($cid22);
for ($idm22 = 1; $idm22 <= $nid22; $idm22++){
 $idd22=$id22+$idm22;
   $chn.="<channel id=\"".$cid22[$idm22-1][1]."\"><display-name lang=\"zh\">".$cid22[$idm22-1][1]."</display-name></channel>\n";       
}

for ($idm22 = 1; $idm22 <= $nid22; $idm22++){
$ts = time();
$sign = hash('sha256', '6ca114a836ac7d73'.$ts);

   $url22='https://pubmod.hntv.tv/program/getAuth/vod/originStream/program/'.$cid22[$idm22-1][0].'/'.$ts;
 $idd22=$id22+$idm22;
    $ch22= curl_init();
    curl_setopt($ch22, CURLOPT_URL,$url22);
    curl_setopt($ch22, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch22, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch22, CURLOPT_SSL_VERIFYHOST, FALSE);
$headers22=[
'Host: pubmod.hntv.tv',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36 Edg/129.0.0.0',
'sign: '.$sign,
'timestamp: '.$ts,
'Origin: https://static.hntv.tv',

'Connection: keep-alive',
'Referer: https://static.hntv.tv/',
];

//curl_setopt($ch, CURLOPT_HTTPHEADER, array('timestamp: '.$ts,'sign: '.$sign));
//curl_setopt($ch, CURLOPT_USERAGENT, 'okhttp/3.12.0');
curl_setopt($ch22, CURLOPT_HTTPHEADER, $headers22);


curl_setopt($ch22,CURLOPT_ENCODING,'Vary: Accept-Encoding');
$re22 = curl_exec($ch22);
curl_close($ch22);

//print $re21;

$programs22=json_decode($re22)->programs;
$tum22=count($programs22);


for ( $i22=1 ; $i22<=$tum22 ; $i22++ ) {
$title22=json_decode($re22)->programs[$i22-1]->title;//节目名称
$beginTime22=json_decode($re22)->programs[$i22-1]->beginTime;//节目开始时间
$endTime22=json_decode($re22)->programs[$i22-1]->endTime;//节目结束时间



$chn.="<programme start=\"".str_replace(' ','',str_replace('-','',str_replace(':','',date('Y-m-d H:i:s',$beginTime22)))).' +0800'."\" stop=\"".str_replace(' ','',str_replace('-','',str_replace(':','',date('Y-m-d H:i:s', $endTime22)))).' +0800'."\" channel=\"".$cid22[$idm22-1][1]."\">\n<title lang=\"zh\">". $title22."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";


}
}

 //19浙江電視台
$id19=100999;//起始节目编号
$cid19=array(
array('101','浙江卫视'),
array('102','浙江钱江都市'),
array('103','浙江经济'),
array('104','浙江科教'),
array('106','浙江民生'),
array('107','浙江新闻'),
array('108','浙江少儿'),
array('110','浙江国际'),
array('111','浙江好易购'),
array('112','浙江数码时代'),

);
$nid19=sizeof($cid19);


for ($idm19 = 1; $idm19 <= $nid19; $idm19++){
 $idd19=$id19+$idm19;
   $chn.="<channel id=\"".$cid19[$idm19-1][1]."\"><display-name lang=\"zh\">".$cid19[$idm19-1][1]."</display-name></channel>\n";
       
}

for ($idm19 = 1; $idm19 <= $nid19; $idm19++){
 $idd19=$id19+$idm19;
$url19='https://p.cztv.com/api/paas/program/'.$cid19[$idm19-1][0].'/'.$dt1;
    $ch19 = curl_init();
    curl_setopt($ch19, CURLOPT_URL, $url19);
    curl_setopt($ch19, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch19, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch19, CURLOPT_SSL_VERIFYHOST, FALSE);

$headers19=[

'Host: p.cztv.com',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:91.0) Gecko/20100101 Firefox/91.0',

'Origin: http://www.cztv.com',
'Connection: keep-alive',
'Referer: http://www.cztv.com/',

];
curl_setopt($ch19, CURLOPT_HTTPHEADER, $headers19);

    curl_setopt($ch19,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re19 = curl_exec($ch19);
    curl_close($ch19);
 $re19 = str_replace('《','',$re19);
 $re19 = str_replace('》','',$re19);

//print $re19;
$list19=json_decode($re19)->content->list[0]->list;
$ryut19=count($list19);

for ( $i19=0 ; $i19<=$ryut19-1 ; $i19++ ) {
//$date = date('d-m-Y H:i:s', $play_time);
$program_title=json_decode($re19)->content ->list[0] ->list[$i19]->program_title;
$play_time=json_decode($re19)->content ->list[0] ->list[$i19]->play_time;
$duration=json_decode($re19)->content ->list[0] ->list[$i19]->duration;


//$date = str_replace(' ','',str_replace('-','',str_replace(':','',date('Y-m-d H:i:s', 1565600000))));
                                                                                                             
//print $program_title;
//print $play_time;

$chn.="<programme start=\"".str_replace(' ','',str_replace('-','',str_replace(':','',date('Y-m-d H:i:s', ($play_time/1000))))).' +0800'."\" stop=\"".str_replace(' ','',str_replace('-','',str_replace(':','',date('Y-m-d H:i:s', (($play_time+$duration)/1000))))) .' +0800'."\" channel=\"".$cid19[$idm19-1][1]."\">\n<title lang=\"zh\">". $program_title."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";




}
}


//广东电视台
$id20=101009;//起始节目编号
$cid20=array(
array('1','广东卫视'),
array('2','广东珠江'),
array('6','广东新闻'),
array('4','广东民生'),
array('14','广东大湾区卫视'),
array('8','广东大湾区卫视海外版'),
array('3','广东体育'),
array('13','广东经济科教'),
array('17','广东影视'),
array('16','广东综艺'),
array('5','广东珠江境外'),
array('18','广东少儿'),
array('7','广东嘉禾卡通'),
array('31','广东现代教育'),
array('32','广东移动'),
array('33','广东岭南戏曲'),

);
$nid20=sizeof($cid20);
for ($idm20 = 1; $idm20 <= $nid20; $idm20++){
 $idd20=$id20+$idm20;
   $chn.="<channel id=\"".$cid20[$idm20-1][1]."\"><display-name lang=\"zh\">".$cid20[$idm20-1][1]."</display-name></channel>\n";       
}

for ($idm20 = 1; $idm20 <= $nid20; $idm20++){

$url20='http://epg.gdtv.cn/f/'.$cid20[$idm20-1][0].'/'.$dt11.'.xml';
 $idd20=$id20+$idm20;

    $ch20= curl_init();
    curl_setopt($ch20, CURLOPT_URL,$url20);
    curl_setopt($ch20, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch20, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch20, CURLOPT_SSL_VERIFYHOST, FALSE);

curl_setopt($ch20,CURLOPT_ENCODING,'Vary: Accept-Encoding');
$re20 = curl_exec($ch20);
curl_close($ch20);






preg_match_all('|<content time1="(.*?)" time2=|i',$re20,$us20,PREG_SET_ORDER);//播放開始時間
preg_match_all('|time2="(.*?)">|i',$re20,$ue20,PREG_SET_ORDER);//播放結束時間
preg_match_all('|CDATA\[(.*?)\]\]>|i',$re20,$un20,PREG_SET_ORDER);//播放時間

//print_r($us20);
//print_r($ue20);
//print_r($un20);
$ryut20=count($un20);
for ( $i20=0 ; $i20<=$ryut20-1 ; $i20++ ) {

$chn.="<programme start=\"".str_replace(' ','',str_replace('-','',str_replace(':','',date('Y-m-d H:i:s', $us20[$i20][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace('-','',str_replace(':','',date('Y-m-d H:i:s', $ue20[$i20][1])))).' +0800'."\" channel=\"".$cid20[$idm20-1][1]."\">\n<title lang=\"zh\">". $un20[$i20][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";






}


$url201='http://epg.gdtv.cn/f/'.$cid20[$idm20-1][0].'/'.$dt12.'.xml';

 $idd20=$id20+$idm20;
    $ch201= curl_init();
    curl_setopt($ch201, CURLOPT_URL,$url201);
    curl_setopt($ch201, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch201, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch201, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch201,CURLOPT_ENCODING,'Vary: Accept-Encoding');
$re201 = curl_exec($ch201);
curl_close($ch201);

//print $re20;





preg_match_all('|<content time1="(.*?)" time2=|i',$re201,$us201,PREG_SET_ORDER);//播放開始時間
preg_match_all('|time2="(.*?)">|i',$re201,$ue201,PREG_SET_ORDER);//播放結束時間
preg_match_all('|CDATA\[(.*?)\]\]>|i',$re201,$un201,PREG_SET_ORDER);//播放時間


$ryut201=count($un201);
for ( $i201=0 ; $i201<=$ryut201-1 ; $i201++ ) {

$chn.="<programme start=\"".str_replace(' ','',str_replace('-','',str_replace(':','',date('Y-m-d H:i:s', $us201[$i201][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace('-','',str_replace(':','',date('Y-m-d H:i:s', $ue201[$i201][1])))).' +0800'."\" channel=\"".$cid20[$idm20-1][1]."\">\n<title lang=\"zh\">". $un201[$i201][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";


}



}

//陝西
$id23=101051;
$cid23=array(
array('star','陝西卫视'),
array('1','陝西新闻'),
array('2','陝西都市青春'),
array('3','陝西生活'),
array('4','陝西影视'),
array('5','陝西公共'),
array('6','陝西乐家购物'),
array('7','陝西体育'),
array('nl','陝西农林'),

    );
 

$nid23=sizeof($cid23);
for ($idm23 = 1; $idm23 <= $nid23; $idm23++){
 $idd23=$id23+$idm23;
   $chn.="<channel id=\"".$cid23[$idm23-1][1]."\"><display-name lang=\"zh\">".$cid23[$idm23-1][1]."</display-name></channel>\n";
}
for ($idm23 = 1; $idm23 <= $nid23; $idm23++){

$url23="http://m.snrtv.com/index.php?m=playlist_tv&channel=".$cid23[$idm23-1][0];

 $idd23=$id23+$idm23;
    $ch23 = curl_init();
    curl_setopt($ch23, CURLOPT_URL, $url23);
    curl_setopt($ch23, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch23, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch23, CURLOPT_SSL_VERIFYHOST, FALSE);
$headers23=[
'Host: m.snrtv.com',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:91.0) Gecko/20100101 Firefox/91.0',
'Connection: keep-alive',
'Referer: http://live.snrtv.com/',
//Cookie: Hm_lvt_22ac8379032eba86b4501cf27e79465c=1629937088; Hm_lpvt_22ac8379032eba86b4501cf27e79465c=1629937088

];
curl_setopt($ch23, CURLOPT_HTTPHEADER, $headers23);
//curl_setopt($ch23, CURLOPT_TIMEOUT, 30); // CURLOPT_TIMEOUT_MS
    curl_setopt($ch23,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re23 = curl_exec($ch23);
  //$re31=str_replace('&','&amp;',$re31);
    curl_close($ch23);
   // $re31=compress_html($re31);
//print $re31;

$re23 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re23);// 適合php7
//print  $re23;


preg_match_all('|"start":"(.*?)"|i',$re23,$us23,PREG_SET_ORDER);//獲取時間
preg_match_all('|"end":"(.*?)"|i',$re23,$ue23,PREG_SET_ORDER);//獲取時間
preg_match_all('|"name":"(.*?)"|i',$re23,$uk23,PREG_SET_ORDER);//獲取節目
//print_r($ue23);
$trm23=count($uk23);
   for ($k23 = 0; $k23 < $trm23-1; $k23++) {
if(str_replace(':','',$us23[$k23][1])<str_replace(':','',$us23[$k23+1][1])){
        $chn.="<programme start=\"".$dt1.str_replace(':','',$us23[$k23][1]).'00 +0800'."\" stop=\"".$dt1.str_replace(':','',$ue23[$k23][1]).'00 +0800'."\" channel=\"".$cid23[$idm23-1][1]."\">\n<title lang=\"zh\">".preg_replace('/\s(?=)/','',str_replace('</h4>','',$uk23[$k23][1]))."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                                     



     }

if(str_replace(':','',$us23[$k23][1])>str_replace(':','',$us23[$k23+1][1])){
        $chn.="<programme start=\"".$dt1.str_replace(':','',$us23[$k23][1]).'00 +0800'."\" stop=\"".($dt1+1).str_replace(':','',$ue23[$k23][1]).'00 +0800'."\" channel=\"".$cid23[$idm23-1][1]."\">\n<title lang=\"zh\">".preg_replace('/\s(?=)/','',str_replace('</h4>','',$uk23[$k23][1]))."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                                          }
}
}
//25安徽卫视
$id25=101060;//起始节目编号
$cid25=array(
       array('11','安徽卫视'),
    array('12','安徽经济'),
 array('16','安徽公共'),
 array('14','安徽影视'),
 array('13','安徽农业科教'),
 array('17','安徽综艺体育'),
 array('19','安徽人物'),
 array('18','安徽国际'),
);

$nid25=sizeof($cid25);
for ($idm25 = 1; $idm25 <= $nid25; $idm25++){
 $idd25=$id25+$idm25;
   $chn.="<channel id=\"".$cid25[$idm25-1][1]."\"><display-name lang=\"zh\">".$cid25[$idm25-1][1]."</display-name></channel>\n";
         
}

for ($idm25 = 1; $idm25 <= $nid25; $idm25++){

          
$url25='https://tvzb-hw.ahtv.cn/tvradio/Tvprogram/tvProgramList?index=1&page_size=99999&tv_id='.$cid25[$idm25-1][0];
$idd25=$id25+$idm25;
$ch25 = curl_init ();
curl_setopt ( $ch25, CURLOPT_URL, $url25 );
//curl_setopt ( $ch25, CURLOPT_HEADER, $hea );
curl_setopt($ch25,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch25,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt ( $ch25, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch25,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re25 = curl_exec($ch25);
    $re25=str_replace('&','&amp;',$re25);
   curl_close($ch25);

//print $re1;

preg_match_all('|"tv_program_name":"(.*?)","start_time|i',$re25,$um25,PREG_SET_ORDER);//播放節目

preg_match_all('|"start_time":(.*?),"replay_url|i',$re25,$un25,PREG_SET_ORDER);//播放時間

//print_r($um1);
//print_r($un1);


$trm25=count($um25);
  for ($k25 = 0; $k25 <=$trm25-2 ; $k25++) {  

 $chn.="<programme start=\"".date('YmdHis', $un25[$k25][1]).' +0800'."\" stop=\"".date('YmdHis', $un25[$k25+1][1]).' +0800'."\" channel=\"".$cid25[$idm25-1][1]."\">\n<title lang=\"zh\">".str_replace('<','&lt;',str_replace('&','&amp;',str_replace('>',' &gt;',$um25[$k25][1])))."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
 

               
}
}
/*
$idm30=100647;
$cid30=array(
array('4','福建综合'),

array('5','东南卫视'),
array('6','福建公共'),
array('7','福建电视剧'),

array('13','福建新闻'),
array('8','福建旅游'),
array('9','福建经济'),
array('10','福建体育'),
array('2','福建少儿'),
array('3','海峡卫视'),

);


$nid30=sizeof($cid30);

for ($id30 = 1; $id30 <= $nid30; $id30++){

    $idd30=$id30+$idm30;
    $chn.="<channel id=\"".$cid30[$id30-1][1]."\"><display-name lang=\"zh\">".$cid30[$id30-1][1]."</display-name></channel>\n";
 }


for ($id30 = 1; $id30 <= $nid30; $id30++){
 $t=time();
$tr=md5("877a9ba7a98f75b90a9d49f53f15a858&YmJjMjllMjJkODc2OGViZTUwYzRjYjAyYzBhZDg3YmU=&1.0.0&".$t);
$url30='https://live.fjtv.net/m2o/program_switch.php?channel_id='.$cid30[$id30-1][0].'&shownums=7&_='.$t;
   $idd30=$id30+$idm30;



$header30=array(
"Host: live.fjtv.net",
"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:84.0) Gecko/20100101 Firefox/84.0",
"X-API-TIMESTAMP: ".$t,
"X-API-KEY: 877a9ba7a98f75b90a9d49f53f15a858",
"X-AUTH-TYPE: md5",
"X-API-VERSION: 1.0.0",
"X-API-SIGNATURE: ".$tr,
"Referer: http://live.fjtv.net/",
);
      $ch30 = curl_init();
    curl_setopt($ch30, CURLOPT_URL, $url30);
curl_setopt($ch30, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch30, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch30, CURLOPT_RETURNTRANSFER, TRUE);
    //curl_setopt($ch30, CURLOPT_COOKIEJAR, $cookie_jar);
curl_setopt($ch30, CURLOPT_TIMEOUT, 60); // CURLOPT_TIMEOUT_MS
    curl_setopt($ch30,CURLOPT_HTTPHEADER,$header30);
    $re30 = curl_exec($ch30);
   // $re=stripslashes($re);
    curl_close($ch30);

   preg_match_all('|<span class="time">(.*?)</span>|',$re30,$time30);
preg_match_all('|</span>(.*?)</li>|',$re30,$title30);
//print_r($time30);
//print_r($title30);

$trm30=count($time30[1]);
//print $trm30;


  for ($k30 =1; $k30 <=$trm30-2 ; $k30++) {  
        $chn.="<programme start=\"".$dt1.str_replace(':','',$time30[1][$k30-1]).' +0800'."\" stop=\"".$dt1.str_replace(':','',$time30[1][$k30]).' +0800'."\" channel=\"".$cid30[$id30-1][1]."\">\n<title lang=\"zh\">".$title30[1][$k30+6]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                                                            
 

    }

$chn.="<programme start=\"".$dt1.str_replace(':','',$time30[1][$trm30-1]).' +0800'."\" stop=\"".$dt1.'235900 +0800'."\" channel=\"".$cid30[$id30-1][1]."\">\n<title lang=\"zh\">".$title30[1][$trm30+6]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}

*/
//廈門電視
$id33=100669;
$cid33=array(

array('84','厦门卫视'),

array('16','厦门1'),

array('17','厦门2'),

//array('52','厦门移动'),


    );

$nid33=sizeof($cid33);
for ($idm33 = 1; $idm33 <= $nid33; $idm33++){
 $idd33=$id33+$idm33;
   $chn.="<channel id=\"".$cid33[$idm33-1][1]."\"><display-name lang=\"zh\">".$cid33[$idm33-1][1]."</display-name></channel>\n";
}
for ($idm33 = 1; $idm33 <= $nid33; $idm33++){
//$url23="http://m.snrtv.com/index.php?m=playlist_tv&channel=".$cid23[$idm23-1][0];
$url33="https://mapi1.kxm.xmtv.cn/api/v1/tvshow_share.php?channel_id=".$cid33[$idm33-1][0]."&zone=";
 $idd33=$id33+$idm33;
    $ch33 = curl_init();
    curl_setopt($ch33, CURLOPT_URL, $url33);
    curl_setopt($ch33, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch33, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch33, CURLOPT_SSL_VERIFYHOST, FALSE);
$headers33=[
'Host: mapi1.kxm.xmtv.cn',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:96.0) Gecko/20100101 Firefox/96.0',
'Origin: https://share1.kxm.xmtv.cn',
//Cookie: Hm_lvt_22ac8379032eba86b4501cf27e79465c=1629937088; Hm_lpvt_22ac8379032eba86b4501cf27e79465c=1629937088
];
curl_setopt($ch33, CURLOPT_HTTPHEADER, $headers33);
    curl_setopt($ch33,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re33 = curl_exec($ch33);
    curl_close($ch33);
   // $re31=compress_html($re31);



$re33 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re33);// 適合php7



preg_match_all('|"start_time":(.*?),"date|i',$re33,$us33,PREG_SET_ORDER);//獲取時間
preg_match_all('|"end_time":(.*?),"m3u8|i',$re33,$ue33,PREG_SET_ORDER);//獲取時間
preg_match_all('|"theme":"(.*?)"|i',$re33,$uk33,PREG_SET_ORDER);//獲取節目

$trm33=count($uk33);

 for ($k33 = 0; $k33 < $trm33-1; $k33++) {
// $chn.="<programme start=\"".$dt1.str_replace(':','',date("H:i", $us33[$k33][1])).' +0800'."\" stop=\"".$dt1.str_replace(':','',date("H:i", $ue33[$k33][1])).' +0800'."\" channel=\"".$cid33[$idm33-1][1]."\">\n<title lang=\"zh\">".$uk33[$k33][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                                                            
   $chn.="<programme start=\"".str_replace(' ','',str_replace('-','',str_replace(':','', date('Y-m-d H:i:s', $us33[$k33][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace('-','',str_replace(':','', date('Y-m-d H:i:s', $ue33[$k33][1])))).' +0800'."\" channel=\"".$cid33[$idm33-1][1]."\">\n<title lang=\"zh\">".$uk33[$k33][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";                               




}
              

   }

$idm31=100658;
$cid31=array(
array('462','河北卫视'),

array('114','河北经济'),
array('118','河北农民'),
array('62','河北都市'),
array('334','河北影视剧'),
array('70','河北少儿科教'),
array('338','河北公共'),

);


$nid31=sizeof($cid31);

for ($id31 = 1; $id31 <= $nid31; $id31++){

    $idd31=$id31+$idm31;
    $chn.="<channel id=\"".$cid31[$id31-1][1]."\"><display-name lang=\"zh\">".$cid31[$id31-1][1]."</display-name></channel>\n";
 }

for ($id31 = 1; $id31 <= $nid31; $id31++){
   $idd31=$id31+$idm31;

$ch311 = curl_init();
curl_setopt_array($ch311, array(
CURLOPT_URL => 'https://api.cmc.hebtv.com/spidercrms/api/live/liveShowSet/findNoPage',
CURLOPT_CUSTOMREQUEST => 'OPTIONS',
CURLOPT_RETURNTRANSFER => true,
CURLOPT_HEADER => true,
CURLOPT_NOBODY => true,
CURLOPT_VERBOSE => true,
CURLOPT_SSL_VERIFYPEER=>false,
CURLOPT_SSL_VERIFYHOST =>false,
));

$r = curl_exec($ch311);

//echo PHP_EOL.'Response Headers:'.PHP_EOL;

//print_r($r);

curl_close($ch311);



$url31 = 'https://api.cmc.hebtv.com/spidercrms/api/live/liveShowSet/findNoPage';

$data1 = array(
    "sourceId" => $cid31[$id31 - 1][0],
    "tenantId" => "0d91d6cfb98f5b206ac1e752757fc5a9",
    "day" => "$dt11",
    "dayEnd" => "$dt11",
);

$encryptString = json_encode($data1, true);

$ch31 = curl_init();
curl_setopt($ch31, CURLOPT_URL, $url31);
curl_setopt($ch31, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch31, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch31, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch31, CURLOPT_POST, 1);
curl_setopt($ch31, CURLOPT_POSTFIELDS, $encryptString); // 使用 JSON 编码后的字符串

curl_setopt($ch31, CURLOPT_HTTPHEADER, array(
    'Connection: keep-alive',
    'Content-Length: ' . strlen($encryptString),
    'tenantId: 0d91d6cfb98f5b206ac1e752757fc5a9',
    'DNT: 1',
    'Content-Type: application/json',
    'Origin: https://www.hebtv.com',
    'Referer: https://www.hebtv.com/',
));

$re31 = curl_exec($ch31);
curl_close($ch31);

// 处理返回的 JSON

    $re31 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re31);
 // print $re31;


preg_match_all('/"startDateTime":"(.*?)",/i',$re31,$us31,PREG_SET_ORDER);//播放開始時間
preg_match_all('/"endDateTime":"(.*?)",/',$re31,$ue31,PREG_SET_ORDER);//播放結束時間
preg_match_all('/name":"(.*?)",/',$re31,$un31,PREG_SET_ORDER);//播放時間
//print_r($us31);
//print_r($ue31);
//print_r($un31);

$trm31=count($us31);
for ($k31 = 0; $k31 <= $trm31-1 ; $k31++) {  


   $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',
$us31[$k31][1]))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',$ue31[$k31][1]))).' +0800'."\" channel=\"".$cid31[$id31-1][1]."\">\n<title lang=\"zh\">". $un31[$k31][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}



$url31 = 'https://api.cmc.hebtv.com/spidercrms/api/live/liveShowSet/findNoPage';

$data11 = array(
    "sourceId" => $cid31[$id31 - 1][0],
    "tenantId" => "0d91d6cfb98f5b206ac1e752757fc5a9",
    "day" => "$dt11",
    "dayEnd" => "$dt12",
);

$encryptString1 = json_encode($data11, true);

$ch311 = curl_init();
curl_setopt($ch311, CURLOPT_URL, $url31);
curl_setopt($ch311, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch311, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch311, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch311, CURLOPT_POST, 1);
curl_setopt($ch311, CURLOPT_POSTFIELDS, $encryptString1); // 使用 JSON 编码后的字符串

curl_setopt($ch311, CURLOPT_HTTPHEADER, array(
    'Connection: keep-alive',
    'Content-Length: ' . strlen($encryptString),
    'tenantId: 0d91d6cfb98f5b206ac1e752757fc5a9',
    'DNT: 1',
    'Content-Type: application/json',
    'Origin: https://www.hebtv.com',
    'Referer: https://www.hebtv.com/',
));

$re311 = curl_exec($ch311);
curl_close($ch311);

// 处理返回的 JSON

    $re311 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re311);
 // print $re31;


preg_match_all('/"startDateTime":"(.*?)",/i',$re311,$us311,PREG_SET_ORDER);//播放開始時間
preg_match_all('/"endDateTime":"(.*?)",/',$re311,$ue311,PREG_SET_ORDER);//播放結束時間
preg_match_all('/name":"(.*?)",/',$re311,$un311,PREG_SET_ORDER);//播放時間
//print_r($us31);
//print_r($ue31);
//print_r($un31);

$trm311=count($us311);
for ($k311 = 0; $k311 <= $trm311-1 ; $k311++) {  


   $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',
$us311[$k311][1]))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',$ue311[$k311][1]))).' +0800'."\" channel=\"".$cid31[$id31-1][1]."\">\n<title lang=\"zh\">". $un311[$k311][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}





}


/*
//河北电视台

$idm31=100658;
$cid31=array(
array('462','河北卫视'),

array('114','河北经济'),
array('118','河北农民'),
array('62','河北都市'),
array('334','河北影视剧'),
array('70','河北少儿科教'),
array('338','河北公共'),

);


$nid31=sizeof($cid31);

for ($id31 = 1; $id31 <= $nid31; $id31++){

    $idd31=$id31+$idm31;
    $chn.="<channel id=\"".$cid31[$id31-1][1]."\"><display-name lang=\"zh\">".$cid31[$id31-1][1]."</display-name></channel>\n";
 }

for ($id31 = 1; $id31 <= $nid31; $id31++){
   $idd31=$id31+$idm31;

$ch311 = curl_init();
curl_setopt_array($ch311, array(
CURLOPT_URL => 'https://api.cmc.hebtv.com/appapi/api/content/get-live-info',
CURLOPT_CUSTOMREQUEST => 'OPTIONS',
CURLOPT_RETURNTRANSFER => true,
CURLOPT_HEADER => true,
CURLOPT_NOBODY => true,
CURLOPT_VERBOSE => true,
CURLOPT_SSL_VERIFYPEER=>false,
CURLOPT_SSL_VERIFYHOST =>false,
));

$r = curl_exec($ch311);

//echo PHP_EOL.'Response Headers:'.PHP_EOL;

//print_r($r);

curl_close($ch311);



$url31='https://api.cmc.hebtv.com/appapi/api/content/get-live-info';


$data1 = array(
 "sourceId" =>$cid31[$id31-1][0],
"tenantId" => "0d91d6cfb98f5b206ac1e752757fc5a9",
"tenantid" => "0d91d6cfb98f5b206ac1e752757fc5a9",
"api_version" => "3.7.0",
"client" => "android",
"cms_app_id" => "19",
"app_id" => 2,
"app_version" => "1.0.39",
"no_cache" => "yes",

);


$encryptString = json_encode($data1,true);
//echo $encryptString;
$url31='https://api.cmc.hebtv.com/appapi/api/content/get-live-info';
    $ch31 = curl_init();
    curl_setopt($ch31, CURLOPT_URL, $url31);
  curl_setopt($ch31, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch31, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch31, CURLOPT_SSL_VERIFYHOST, false);
//curl_setopt($ch31, CURLOPT_CUSTOMREQUEST, 'OPTIONS');
//curl_setopt($ch31, CURLOPT_CUSTOMREQUEST, NULL);
    curl_setopt ( $ch31, CURLOPT_POST, 1 );
    curl_setopt ( $ch31, CURLOPT_POSTFIELDS, $data1 );
      //curl_setopt($ch31, CURLOPT_COOKIEJAR, $cookie_jar)
//curl_setopt($ch31, CURLOPT_TIMEOUT, 28); // CURLOPT_TIMEOUT_MS
   $re31 = curl_exec($ch31);



    curl_close($ch31);
$re31 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re31);// 適合php7
//print  $re31;

$uuu=json_decode($re31);
$tuuu31=$uuu->result->data->$dt11;//http://www.bejson.com/jsonviewernew/进行结果转化
$tyu31=count($tuuu31);
//print_r($tuuu31);





for ( $i31=0 ; $i31<=$tyu31-1 ; $i31++ ) {
$endDateTime31=$tuuu31[$i31]->endDateTime;
$name31=$tuuu31[$i31]->name;
$startDateTime31=$tuuu31[$i31]->startDateTime;
//$endDateTime31=json_decode($re31)->result->data->$dt11[$i31]->endDateTime;
//$name31=json_decode($re31)->result->data->$dt11[$i31]->name;


$startDateTime31=str_replace(' ','',$startDateTime31);

$startDateTime31=str_replace('-','',$startDateTime31);
$startDateTime31=str_replace(':','',$startDateTime31);

$endDateTime31=str_replace(' ','',$endDateTime31);
$endDateTime31=str_replace(':','',$endDateTime31);
$endDateTime31=str_replace('-','',$endDateTime31);

$chn.="<programme start=\"".$startDateTime31.' +0800'."\" stop=\"".$endDateTime31.' +0800'."\" channel=\"".$cid31[$id31-1][1]."\">\n<title lang=\"zh\">".$name31."</title>\n<desc lang=\"zh\"></desc>\n</programme>\n";

}


$tuuu32=$uuu->result->data->$dt12;//http://www.bejson.com/jsonviewernew/进行结果转化

$tyu32=count($tuuu32);

for ( $i32=0 ; $i32<=$tyu32-1 ; $i32++ ) {
$startDateTime32=$tuuu32[$i32]->startDateTime;
$endDateTime32=$tuuu32[$i32]->endDateTime;
$name32=$tuuu32[$i32]->name;


$startDateTime32=str_replace(' ','',$startDateTime32);

$startDateTime32=str_replace('-','',$startDateTime32);
$startDateTime32=str_replace(':','',$startDateTime32);

$endDateTime32=str_replace(' ','',$endDateTime32);
$endDateTime32=str_replace(':','',$endDateTime32);
$endDateTime32=str_replace('-','',$endDateTime32);

$chn.="<programme start=\"".$startDateTime32.' +0800'."\" stop=\"".$endDateTime32.' +0800'."\" channel=\"".$cid31[$id31-1][1]."\">\n<title lang=\"zh\">".$name32."</title>\n<desc lang=\"zh\"></desc>\n</programme>\n";

}




}


//海南電視台
$time=time();
    
$id35=100672;
$cid35=array(
array('7','三沙卫视'),
array('3','海南卫视'),
array('4','海南经济'),
array('5','海南新闻'),
array('6','海南公共'),
 array('8','海南文旅'),
 array('9','海南少儿'),
//id=7三沙卫视,19海南卫视高清,3卫视标清,4经济频道,5新闻频道,6公共频道,8海南文旅,,9海南少儿,11新闻广播
);

$nid35=sizeof($cid35);
for ($idm35 = 1; $idm35 <= $nid35; $idm35++){
 $idd35=$id35+$idm35;
   $chn.="<channel id=\"".$cid35[$idm35-1][1]."\"><display-name lang=\"zh\">".$cid35[$idm35-1][1]."</display-name></channel>\n";
         
}

for ($idm35 = 1; $idm35 <= $nid35; $idm35++){

 $idd35=$id35+$idm35;

//$url28="http://module.iqilu.com/media/apis/main/getprograms?channelID=".$cid28[$idm28-1][0]."&date=".$dt11;
$url35="http://www.hnntv.cn/m2o/program_switch.php?channel_id=".$cid35[$idm35-1][0]."&shownums=7&_=".$time;

$ch35=curl_init();
curl_setopt($ch35,CURLOPT_URL,$url35);;
curl_setopt($ch35,CURLOPT_RETURNTRANSFER,1);
 //curl_setopt($ch25, CURLOPT_SSL_VERIFYPEER, FALSE);
//curl_setopt($ch25, CURLOPT_SSL_VERIFYHOST, FALSE);
//curl_setopt($ch35,CURLOPT_ENCODING,'Vary: Accept-Encoding');
$re35=curl_exec($ch35);
curl_close($ch35);  
//print $re35;

$re35= preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re35);// 適合php7

preg_match_all('|</span>(.*?)</li>|i',$re35,$tile35,PREG_SET_ORDER);//播放節目
preg_match_all('|<span class="time">(.*?)</span>|i',$re35,$st35,PREG_SET_ORDER);//播放節目時間


$tuuy35=count($st35);
//print_r($tile35);
//print_r($st35);


for ($k35 = 0; $k35<= $tuuy35-2; $k35++) { 
   
                 
 $chn.="<programme start=\"".$dt1.str_replace(' ','',str_replace('-','',str_replace(':','',$st35[$k35][1]))).'00 +0800'."\" stop=\"".$dt1.str_replace(' ','',str_replace('-','',str_replace(':','',$st35[$k35+1][1]))).'00 +0800'."\" channel=\"".$cid35[$idm35-1][1]."\">\n<title lang=\"zh\">".$tile35[$k35+7][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
}




 $chn.="<programme start=\"".$dt1.str_replace(' ','',str_replace('-','',str_replace(':','',$st35[$tuuy35-1][1]))).'00 +0800'."\" stop=\"".$dt1.'240000 +0800'."\" channel=\"".$cid35[$idm35-1][1]."\">\n<title lang=\"zh\">".$tile35[$tuuy35+6][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";

}
*/
//山東電視台
$id28=100680;
$cid28=array(
array('24','山东卫视'),
array('31','山东新闻'),
array('25','山东齐鲁'),
array('26','山东体育'),
array('29','山东生活'),
array('28','山东综艺'),
array('30','山东农科'),
array('27','山东文旅'),
  array('32','山东少儿'),

);

$nid28=sizeof($cid28);
for ($idm28 = 1; $idm28 <= $nid28; $idm28++){
 $idd28=$id28+$idm28;
   $chn.="<channel id=\"".$cid28[$idm28-1][1]."\"><display-name lang=\"zh\">".$cid28[$idm28-1][1]."</display-name></channel>\n";
         
}

for ($idm28 = 1; $idm28 <= $nid28; $idm28++){

 $idd28=$id28+$idm28;

$url28="http://module.iqilu.com/media/apis/main/getprograms?channelID=".$cid28[$idm28-1][0]."&date=".$dt11;


$ch28=curl_init();
curl_setopt($ch28,CURLOPT_URL,$url28);;
curl_setopt($ch28,CURLOPT_RETURNTRANSFER,1);
 //curl_setopt($ch25, CURLOPT_SSL_VERIFYPEER, FALSE);
//curl_setopt($ch25, CURLOPT_SSL_VERIFYHOST, FALSE);
//curl_setopt($ch25,CURLOPT_ENCODING,'Vary: Accept-Encoding');
$re28=curl_exec($ch28);
curl_close($ch28);  
//print $re28;

$re28= preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re28);// 適合php7

preg_match_all('|{"name":"(.*?)",|i',$re28,$tile28,PREG_SET_ORDER);//播放節目
preg_match_all('/"begintime":(.*?),"endtime/i',$re28,$st28,PREG_SET_ORDER);//播放節目開始
preg_match_all('|"endtime":(.*?)},|i',$re28,$et28,PREG_SET_ORDER);//播放節目結束


$tuuy28=count($et28);
//print_r($tile28);
//print_r($st28);
//print_r($et28);



for ($k28 = 0; $k28<= $tuuy28-1; $k28++) { 
    $chn.="<programme start=\"".str_replace(' ','',str_replace('-','',str_replace(':','',date('Y-m-d H:i:s', $st28[$k28][1])))).' +0800'."\" stop=\"".str_replace(' ','',str_replace('-','',str_replace(':','',date('Y-m-d H:i:s', $et28[$k28][1])))).' +0800'."\" channel=\"".$cid28[$idm28-1][1]."\">\n<title lang=\"zh\">".str_replace('<','&lt;',str_replace('&','&amp;',str_replace('>',' &gt;',$tile28[$k28][1])))."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                 

}
}

/*
深圳电视台

$id34=100662;
$cid34=array(


array("AxeFRth","深圳卫视"),

 array("ZwxzUXr","深圳都市"),
 array("4azbkoY","深圳电视剧"),
 array("2q76Sw2","深圳公共"),
 array("3vlcoxP","深圳财经"),
 array("1q4iPng","深圳娱乐"),
 array("1SIQj6s","深圳少儿"),
 array("wDF6KJ3","深圳移动电视"),
 array("BJ5u5k2","深圳购物"),
 array("xO1xQFv","深圳DV生活"),
 array("sztvgjpd","深圳"),
);
$nid34=sizeof($cid34);
for ($idm34 = 1; $idm34<= $nid34; $idm34++){
 $idd34=$id34+$idm34;
   $chn.="<channel id=\"".$cid34[$idm34-1][1]."\"><display-name lang=\"zh\">".$cid34[$idm34-1][1]."</display-name></channel>\n";
    
}

for ($idm34 = 1; $idm34 <= $nid34; $idm34++){
 $idd34=$id34+$idm34;
$url34="http://cls2.cutv.com/api/getEpgs?channelId=".$cid34[$idm34-1][0]."&daytime=".$time111;
$ch34=curl_init();
curl_setopt($ch34,CURLOPT_URL,$url34);;
curl_setopt($ch34,CURLOPT_RETURNTRANSFER,1);
 //curl_setopt($ch25, CURLOPT_SSL_VERIFYPEER, FALSE);
//curl_setopt($ch25, CURLOPT_SSL_VERIFYHOST, FALSE);
//curl_setopt($ch25,CURLOPT_ENCODING,'Vary: Accept-Encoding');
$re34=curl_exec($ch34);
curl_close($ch34);  
$re34= preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re34);// 適合php7
$list=json_decode($re34)->list;
$yyy=count($list);
$daytime=json_decode($re34)->list[$yyy-1]->daytime; 
$programme=json_decode($re34)->list[$yyy-1]->programme; 
$tuuy34= count($programme);
for ($k34 = 0; $k34<= $tuuy34-1; $k34++) { 
$s=$programme[$k34]->s; 
$e=$programme[$k34+1]->s; 
$t=$programme[$k34]->t; 
   $chn.="<programme start=\"".str_replace(' ','',str_replace('-','',str_replace(':','',date('Y-m-d H:i:s', ($s+$daytime)/1000)))).' +0800'."\" stop=\"".str_replace(' ','',str_replace('-','',str_replace(':','',date('Y-m-d H:i:s',  ($e+$daytime)/1000)))).' +0800'."\" channel=\"".$cid34[$idm34-1][1]."\">\n<title lang=\"zh\">".str_replace('<','&lt;',str_replace('&','&amp;',str_replace('>',' &gt;',$t)))."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                 

}



}
*/





 $chn.="</tv>\n";
//print  $chn;

file_put_contents($fp, $chn);

?>
