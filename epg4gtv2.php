<?php
header( 'Content-Type: text/plain; charset=UTF-8');
//header( 'Content-Type: html/text; charset=utf-8');
ini_set("max_execution_time", "900000000");
//htaccess php_value max_execution_time 0;
ini_set('date.timezone','Asia/Shanghai');
$fp="epg4gtv2.xml";//压缩版本的扩展名后加.gz
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



$chn="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<!DOCTYPE tv SYSTEM \"http://api.torrent-tv.ru/xmltv.dtd\">\n<tv generator-info-name=\"秋哥綜合\" generator-info-url=\"https://www.tdm.com.mo/c_tv/?ch=Satellite\">\n";

//1澳門電視台官網 10001-100005 正確
$id1=100000;//起始节目编号
$cid1=array(
    array('1','澳視澳門'),
 array('2','澳門葡萄牙'),
    array('5','澳門資訊'),
    array('6','澳門體育'),
    array('7','澳門綜藝'),
   array('8','澳門衛星'),
);

$nid1=sizeof($cid1);
for ($idm1 = 1; $idm1 <= $nid1; $idm1++){
 $idd1=$id1+$idm1;
   $chn.="<channel id=\"".$cid1[$idm1-1][1]."\"><display-name lang=\"zh\">".$cid1[$idm1-1][1]."</display-name></channel>\n";
         
}

for ($idm1 = 1; $idm1 <= $nid1; $idm1++){

          
$url1='https://www.tdm.com.mo/api/v1.0/program-list/'.$dt11.'?type=tv&channelId='.$cid1[$idm1-1][0].'&date='.$dt11;
$idd1=$id1+$idm1;
$ch1 = curl_init ();
curl_setopt ( $ch1, CURLOPT_URL, $url1 );
//curl_setopt ( $ch1, CURLOPT_HEADER, $hea );
curl_setopt($ch1,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch1,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt ( $ch1, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch1,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re1 = curl_exec($ch1);
    $re1=str_replace('&','&amp;',$re1);
   curl_close($ch1);

//print $re1;

preg_match_all('|"title":"(.*?)","isLive|i',$re1,$um1,PREG_SET_ORDER);//播放節目
preg_match_all('|"date":"(.*?)","title|i',$re1,$un1,PREG_SET_ORDER);//播放時間
$trm1=count($um1);
  for ($k1 = 1; $k1 <=$trm1-1 ; $k1++) {  
   $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',$un1[$k1-1][1]))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',$un1[$k1][1]))).' +0800'."\" channel=\"".$cid1[$idm1-1][1]."\">\n<title lang=\"zh\">".$um1[$k1-1][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                                          
                                            

    }             


$url11='https://www.tdm.com.mo/api/v1.0/program-list/'.$dt12.'?type=tv&channelId='.$cid1[$idm1-1][0].'&date='.$dt12;
$idd1=$id1+$idm1;
$ch11 = curl_init ();
curl_setopt ( $ch11, CURLOPT_URL, $url11 );
//curl_setopt ( $ch1, CURLOPT_HEADER, $hea );
curl_setopt($ch11,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch11,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt ( $ch11, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch11,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re11 = curl_exec($ch11);
     $re11=str_replace('&','&amp;',$re11);
   curl_close($ch11);

//print $re1;

preg_match_all('|"title":"(.*?)","isLive|i',$re11,$um11,PREG_SET_ORDER);//播放節目
preg_match_all('|"date":"(.*?)","title|i',$re11,$un11,PREG_SET_ORDER);//播放時間
$trm11=count($um11);
  for ($k11 = 1; $k11 <=$trm11-1 ; $k11++) {  
   $chn.="<programme start=\"".str_replace(' ','',str_replace(':','',str_replace('-','',$un11[$k11-1][1]))).' +0800'."\" stop=\"".str_replace(' ','',str_replace(':','',str_replace('-','',$un11[$k11][1]))).' +0800'."\" channel=\"".$cid1[$idm1-1][1]."\">\n<title lang=\"zh\">".$um11[$k11-1][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                                                                                                           }                                                                                                                                           
                                                                          


  }


//3中華電信mod  正常中

$id3 = 100009;
$cid3 = array(
array('005', '民視'),
array('006','人間衛視'),
array('007','台視'),
array('008','大愛電視'),
array('009','中視'),
array('011','華視'),
array('012','公視'),
array('141','公視台語台'),
array('002','好消息衛星電視台'),
array('003','原住民族電視台'),
array('004','客家電視'),
array('010','華視教育體育文化台'),
array('142','小公視'),
array('095','大愛二台'),
array('018','CARTOONITO HD'),
array('019','ELTV生活英語台'),
array('020','龍華動畫HD'),
array('021','尼克兒童頻道HD'),
array('022','靖洋卡通台Nice Bingo'),
array('023','靖天卡通台'),
array('114','CBeebies'),
array('115','DaVinCi Learning達文西頻道'),
array('116','Nick Jr.'),
array('121','DREAMWORKS'),
array('118','國會頻道1'),
array('119','國會頻道2'),
array('293','Fun探索娛樂台 4K'),
array('108','Medici-arts'),
array('109','MTV Live'),
array('111','MTV綜合電視台'),
array('112','Mezzo Live HD'),
array('113','TRACE Urban'),
array('184','古典音樂台CLASSICA HD'),
array('230','CMusic'),
array('158','愛爾達體育1台'),
array('157','愛爾達體育2台'),
array('156','愛爾達體育3台'),
array('282','愛爾達體育4台'),
array('164','博斯高球一台'),
array('163','博斯高球二台'),
array('160','博斯網球'),
array('159','博斯魅力'),
array('161','博斯無限台'),
array('170','博斯無限二台'),
array('172','TRACE Sport Stars HD'),
array('202','DAZN3'),
array('162','博斯運動一台'),
array('203','DAZN2'),
array('168','智林體育台'),
array('214','EUROSPORT'),
array('167','博斯運動二台'),
array('286','麥哲倫頻道 HD'),
array('233','LOVE NATURE 4K'),
array('015','BBC Earth'),
array('099','Discovery Asia'),
array('100','Discovery科學頻道'),
array('101','DMAX'),
array('102','EVE'),
array('280','Global Trekker HD'),
array('104','歷史頻道'),
array('105','罪案偵緝頻道'),
array('120','BBC Lifestyle Channel'),
array('283','INULTRA'),
array('235','PET CLUB TV HD'),
array('126','LUXE TV'),
array('139','Lifetime'),
array('083','KLT-靖天國際台'),
array('211','幸福空間居家台'),
array('217','滾動力 rollor'),
array('234','HGTV 居家樂活頻道'),
array('292','車迷TV HD'),

array('037','美食星球頻道'),
array('086','Asian Food Network 亞洲美食頻道'),
array('130','Food Network 美食台頻道'),
array('034','亞洲旅遊台MOD'),
array('080','EYE TV旅遊台'),
array('089','Travel Channel'),
array('096','時尚頻道'),
array('204','TV5MONDE STYLE HD 生活時尚'),
array('029','東森購物1台'),
array('024','愛爾達綜合台'),
array('025','三立綜合台'),
array('026','靖天資訊台'),
array('027','靖天綜合台'),
array('028','靖天育樂台'),
array('030','Smart知識台'),
array('031','台視綜合台'),
array('032','亞洲綜合台'),
array('033','中視經典台'),
array('035','寰宇HD綜合台'),
array('038','中視菁采台'),
array('039','TVBS精采台'),
array('048','壹電視綜合台'),
array('134','緯來精采台'),
array('087','天天電視台'),
array('088','ETtoday綜合台'),
array('132','民視第一台'),
array('133','民視台灣台'),
array('131','Nice TV 靖天歡樂台'),
array('137','新唐人亞太台'),
array('231','八大優頻道'),
array('291','momo綜合台HD'),
array('275','信吉藝文台'),
array('218','冠軍電視台'),
array('274','華藝台灣台'),
array('258','信吉文藝台'),
array('276','大立電視台'),
array('281','富力電視台'),
array('290','新天地民俗台 HD'),
array('209','momo2台'),
array('079','東森購物3台'),
array('040','三立戲劇台'),
array('041','台灣戲劇台'),
array('042','龍華戲劇'),
array('043','龍華偶像'),
array('060','龍華影劇'),
array('044','愛爾達影劇台'),
array('045','靖天戲劇台'),
array('046','靖洋戲劇台'),
array('047','EYE TV戲劇台'),
array('036','東森購物2台'),
array('084','靖天日本台'),
array('091','曼迪日本台'),
array('093','Animax'),
array('092','tvN'),
array('128','韓國娛樂台KMTV'),
array('225','華藝中文台'),
array('223','ROCK Extreme'),
array('229','ROCK Entertainment'),

array('24','美好2台'),
array('210','momo1台'),
array('206','東森購物4台'),
array('049','中視新聞台'),
array('050','寰宇新聞台'),
array('051','寰宇新聞台灣台'),
array('052','台視新聞台'),
array('053','三立財經新聞台'),
array('054','華視新聞資訊台'),
array('055','壹電視新聞台'),
array('208','民視新聞台'),
array('264','鏡電視新聞台'),
array('056','寰宇財經台'),
array('082','台視財經台'),
array('057','美好1台'),
array('207','東森購物5台'),
array('143','CNN International'),
array('144','BBC World News'),
array('145','Bloomberg TV'),
array('146','Channel NewsAsia頻道'),
array('147','CNBC Asia Channel'),
array('148','Euronews'),
array('149','德國之聲電視台'),
array('150','FRANCE24(English)'),
array('272','TaiwanPlus'),
array('216','NHK新聞資訊台'),
array('212','半島英語新聞台Al Jazeera English'),
array('123','視納華仁紀實台'),
array('124','影迷數位紀實台'),
array('058','美亞電影台'),
array('059','龍華電影'),
array('064','靖天映畫'),
array('065','靖天電影台'),
array('062','龍華洋片'),
array('061','Warner TV'),
array('284','ROCK Action HD'),
array('068','amc最愛電影'),
array('215','HITS'),
array('075','龍華經典'),
array('076','華藝影劇台'),
array('077','CatchPlay電影台'),
array('078','CinemaWorld'),
array('153','壹電視電影台'),
array('135','采昌影劇台'),
array('154','影迷數位電影台'),
array('213','My Cinema Europe HD 我的歐洲電影'),
array('177','唯心電視台'),
array('173','佛衛電視慈悲台'),
array('174','華藏衛視'),
array('176','正德電視台'),
array('178','TV5MONDE'),
array('179','Arirang TV'),
array('183','FRANCE24'),


);

$nid3 = sizeof($cid3);
for ($idm3 = 1; $idm3 <= $nid3; $idm3++) {
    $idd3 = $id3 + $idm3;
    $chn .= "<channel id=\"" . $cid3[$idm3 - 1][1] . "\"><display-name lang=\"zh\">" . $cid3[$idm3 - 1][1] . "</display-name></channel>\n";
}

for ($idm3 = 1; $idm3 <= $nid3; $idm3++) {
    $idd3 = $id3 + $idm3;
    $url3 = 'https://mod.cht.com.tw/channel/epg.do';
  $headers3 = [
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36 Edg/123.0.0.0",
        "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
        "Referer: https://mod.cht.com.tw/channel/MOD_LIVE_0000000" . $cid3[$idm3 - 1][0] . ".do",
    ];

    $post3 = "contentPk=MOD_LIVE_0000000" . $cid3[$idm3 - 1][0] . "&date=" . $dt11;
    $ch3 = curl_init();
    curl_setopt($ch3, CURLOPT_URL, $url3);
    curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch3, CURLOPT_SSL_VERIFYHOST, FALSE);
  
    curl_setopt($ch3, CURLOPT_HTTPHEADER, $headers3);
    curl_setopt($ch3, CURLOPT_POST, 1);
    curl_setopt($ch3, CURLOPT_POSTFIELDS, $post3);
    curl_setopt($ch3, CURLOPT_CONNECTTIMEOUT, 1000);
     //数据传输的最大允许时间 
    curl_setopt($ch3, CURLOPT_TIMEOUT, 1000);

    $re3 = curl_exec($ch3);
    $re3 = str_replace('&', '&amp;', $re3);
    curl_close($ch3);

    preg_match_all('/programName":"(.*?)",/i', $re3, $um3, PREG_SET_ORDER);
    preg_match_all('/startTime":"(.*?)",/i', $re3, $un3, PREG_SET_ORDER);
    preg_match_all('/endTime":"(.*?)",/i', $re3, $uk3, PREG_SET_ORDER);
//print_r($un31);
 $trm3= count($um3);
for ($k3 = 1; $k3 <= $trm3-1; $k3++) {
   $chn.="<programme start=\"".date("YmdHis",$un3[$k3-1][1]).' +0800'."\" stop=\"".date("YmdHis", $uk3[$k3-1][1]).' +0800'."\" channel=\"" . $cid3[$idm3 - 1][1] . "\">\n<title lang=\"zh\">". $um3[$k3-1][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";

}


$post31 = "contentPk=MOD_LIVE_0000000" . $cid3[$idm3 - 1][0] . "&date=" . $dt12;
    $ch31 = curl_init();
    curl_setopt($ch31, CURLOPT_URL, $url3);
    curl_setopt($ch31, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch31, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch31, CURLOPT_SSL_VERIFYHOST, FALSE);
      curl_setopt($ch31, CURLOPT_HTTPHEADER, $headers3);
    curl_setopt($ch31, CURLOPT_POST, 1);
    curl_setopt($ch31, CURLOPT_POSTFIELDS, $post31);
    curl_setopt($ch31, CURLOPT_CONNECTTIMEOUT, 1000);
    //数据传输的最大允许时间 
    curl_setopt($ch31, CURLOPT_TIMEOUT, 1000);
    $re31 = curl_exec($ch31);
    $re31 = str_replace('&', '&amp;', $re31);
    curl_close($ch31);
    preg_match_all('/programName":"(.*?)",/i', $re31, $um31, PREG_SET_ORDER);
    preg_match_all('/startTime":"(.*?)",/i', $re31, $un31, PREG_SET_ORDER);
    preg_match_all('/endTime":"(.*?)",/i', $re31, $uk31, PREG_SET_ORDER);

 $trm31= count($um31);
//print  $trm31;

for ($k31 = 1; $k31 <= $trm31-1; $k31++) {
 
  $chn.="<programme start=\"".date("YmdHis",$un31[$k31-1][1]).' +0800'."\" stop=\"".date("YmdHis", $uk31[$k31-1][1]).' +0800'."\" channel=\"" . $cid3[$idm3 - 1][1] . "\">\n<title lang=\"zh\">". $um31[$k31-1][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";

}

}

//4 bb寬頻更新2024年1月1日

$id4=100200;
$cid4=array(
array('2','bb快報'),
array('3','公用頻道'),
array('4','高雄都會台'),
array('5','CNN International'),
array('6','民視無線台'),
array('7','人間衛視'),
array('8','台灣電視台'),
array('9','大愛電視台'),
array('10','中視數位台'),
array('11','霹靂電視台'),
array('12','中華電視台'),
array('13','公共電視台'),
array('14','公視台語台'),
array('15','好消息衛星電視台'),
array('16','原住民族電視台'),
array('17','客家電視台'),
array('18','BBC EARTH'),
array('19','Discovery'),
array('20','TLC 旅遊生活頻道'),
array('21','動物星球頻道'),
array('22','Nick Jr.(小尼克)'),
array('23','Cartoon Network'),
array('24','MOMO親子台'),
array('25','東森幼幼台'),
array('26','緯來綜合台'),
array('27','八大第一台'),
array('28','八大綜合台'),
array('29','三立台灣台'),
array('30','三立都會台'),
array('31','韓國娛樂KMTV'),
array('32','東森綜合台'),
array('33','超視'),
array('34','東森購物2台'),
array('35','momo2台'),
array('36','中天綜合台'),
array('37','東風衛視台'),
array('38','MUCH TV'),
array('39','中天娛樂台'),
array('40','東森戲劇台'),
array('41','八大戲劇台'),
array('42','TVBS歡樂台'),
array('43','緯來戲劇台'),
array('44','高點電視台'),
array('45','東森購物3台'),
array('46','東森購物1台'),
array('47','momo 1台'),
array('48','三立財經新聞台'),
array('49','壹電視新聞台'),
array('50','era news 年代新聞'),
array('51','東森新聞台'),
array('52','華視新聞資訊台'),
array('53','民視新聞台'),
array('54','三立新聞台'),
array('55','TVBS 新聞台'),
array('56','TVBS'),
array('57','東森財經新聞台'),
array('58','非凡新聞台'),
array('59','ViVa 1台'),
array('60','東森購物5台'),
array('61','CATCH PLAY電影台'),
array('62','東森電影台'),
array('63','緯來電影台'),
array('64','LS TIME電影台'),
array('65','HBO'),
array('66','東森洋片台'),
array('67','AXN'),
array('68','好萊塢電影台'),
array('69','AMC電影'),
array('70','CINEMAX有線'),
array('71','緯來育樂台'),
array('72','緯來體育台'),
array('73','ELEVEN SPORTS 1'),
array('74','ELEVEN SPORTS 2'),
array('75','MOMO綜合台'),
array('76','緯來日本台'),
array('77','國興衛視'),
array('78','BBC LIFESTYLE'),
array('79','MTV綜合電視台'),
array('80','靖天購物一台'),
array('84','ANIMAX'),
array('82','信吉電視台'),
array('85','寰宇新聞台'),
array('86','鏡電視新聞台'),
array('87','冠軍電視台'),
array('88','JET TV'),
array('89','非凡商業台'),
array('90','中台灣生活網頻道'),
array('91','八大娛樂台'),
array('92','運通財經綜合台'),
array('93','全球財經網頻道'),
array('94','誠心電視台'),
array('95','未有频道'),
array('96','Z頻道'),
array('97','台灣綜合台'),
array('98','海豚綜合台'),
array('99','威達超舜生活台'),
array('100','台灣藝術台'),
array('101','華藏衛星電視台'),
array('102','十方法界電視台'),
array('103','世界衛星電視台'),
array('104','佛衛電視慈悲台'),
array('105','信大電視台'),
array('106','NHK WORLD PREMIUM'),
array('107','全大電視台'),
array('108','美麗人生購物台'),
array('109','正德電視台'),
array('110','天良綜合台'),
array('111','番薯衛星電視台'),
array('112','富立電視台'),
array('113','華藝台灣台'),
array('114','冠軍夢想台'),
array('115','新天地民俗台'),
array('116','三聖電視台'),
array('117','紅豆2台'),
array('118','天美麗電視台'),
array('119','大立電視台'),
array('120','雙子衛視'),
array('121','小公視'),
array('122','華視教育體育文化台'),
array('123','國會頻道1台'),
array('124','國會頻道2台'),
array('125','幸福空間居家台'),
array('126','亞洲旅遊台有線'),
array('129','智林體育台'),
array('130','大愛二台'),
array('131','好消息二台'),
array('136','高點育樂台'),
array('137','靖天綜合台'),
array('138','靖天資訊台'),
array('139','靖天日本台'),
array('140','唯心電視台'),
array('145','GLOBLTREKKER探索世界'),
array('146','ROCK ACTION 搖滾電影'),
array('147','trace sport star運動明星'),
array('148','美食星球'),
array('149','環宇財經'),
array('150','寰宇新聞台灣台'),
array('151','民視第一台'),
array('152','民視台灣台'),
array('153','中視菁采台'),
array('154','中視新聞台'),
array('155','台視新聞台'),
array('156','台視財經台'),
array('157','台視綜合台'),
array('160','Bloomberg Television'),
array('161','BBC World News'),
array('162','TV5MONDE'),
array('163','Channel News Asia'),
array('164','TaiwanPlus'),
array('168','LOVE NATURE'),
array('200','CI罪案偵查頻道'),
array('201','lifetime娛樂頻道'),
array('202','Asian Food Network 亞洲美食頻道'),
array('203','梅迪奇藝術頻道'),
array('204','Discovery Asia'),
array('205','Discovery科學頻道'),
array('206','DMAX'),
array('207','EVE'),
array('209','博斯運動一台'),
array('211','HITS'),
array('212','History 歷史頻道'),
array('213','PET CLUB TV寵物頻道'),
array('214','滾動力 rollor'),
array('215','cinemalworld世界電影頻道'),
array('216','My Cinema Europe HD 我的歐洲電影'),
array('217','HBO HD'),
array('218','HBO Signature 原創鉅獻'),
array('219','HBO Hits 強檔鉅獻'),
array('220','HBO Family'),
array('221','tvN'),
array('222','華藝MBC綜合台'),
array('223','靖天映畫'),
array('224','靖天電影'),
array('225','ROCK ENTERTAINMENT'),
array('226','ROCK EXTREME'),
array('227','CMusic'),
array('229','Warner TV'),
array('230','MTV Live音樂頻道'),
array('234','靖洋卡通'),
array('235','Nickelodeon Asia尼克兒童頻道'),
array('236','CARTOONITO'),
array('237','Cbeebies'),
array('239','Eurosport'),
array('242','博斯運動二台'),
array('243','博斯高球一台'),
array('244','博斯高球二台'),
array('245','博斯魅力網'),
array('246','博斯網球台'),
array('247','博斯無限台'),
array('248','博斯無限二台'),
array('301','彩虹頻道'),
array('302','松視4台'),
array('303','Pandora潘朵啦高畫質玩美台'),
array('304','Pandora潘朵啦高畫質粉紅台'),
array('305','松視1台'),
array('306','松視2台'),
array('307','松視3台'),
array('308','彩虹E台'),
array('309','彩虹Movie台'),
array('310','K頻道'),
array('311','HOT頻道'),
array('312','HAPPY頻道'),
array('313','玩家頻道'),
array('314','驚豔成人電影台'),
array('315','香蕉台'),
array('316','樂活頻道'),

    );
$nid4=sizeof($cid4);

for ($idm4 = 1; $idm4 <= $nid4; $idm4++){

 $idd4=$id4+$idm4;
   $chn.="<channel id=\"".$cid4[$idm4-1][1]."\"><display-name lang=\"zh\">".$cid4[$idm4-1][1]."</display-name></channel>\n";
}
for ($idm4 = 1; $idm4 <= $nid4; $idm4++){

$idd4=$id4+$idm4;

    $url4='https://www.homeplus.net.tw/cable/Product_introduce/digital_tv/get_channel_content';
  
$idd4=$id4+$idm4;
$post4 = "so=210&channelid=" . $cid4[$idm4 - 1][0] ;
   $ch4 = curl_init ();
curl_setopt ( $ch4, CURLOPT_URL, $url4  );
curl_setopt ( $ch4, CURLOPT_HEADER, 0 );
//curl_setopt ( $ch4, CURLOPT_HEADER, $hea );
curl_setopt($ch4,CURLOPT_USERAGENT,'Mozilla/5.0 (Linux; Android 8.1.0; JKM-AL00b) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Mobile Safari/537.36):');
curl_setopt($ch4,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch4,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt ( $ch4, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt ( $ch4, CURLOPT_POST, 1 );
curl_setopt ( $ch4, CURLOPT_POSTFIELDS, $post4);
//curl_setopt($ch4,CURLOPT_USERAGENT, " user-agent:Mozilla/5.0 (Windows NT 6.1; rv:62.0) Gecko/20100101 Firefox/62.0");//浏览器头信息
$re4 = curl_exec ( $ch4 );
//$re4=str_replace('00:00','24:00',$re4);
curl_close ( $ch4 );
$tuu4=json_decode($re4)->date_program->$dt11[0];
    $tuu41 = json_decode($re4)->date_program->$dt11[1];
$trm4=count($tuu4);
 for ($k4 = 1; $k4 <= $trm4-1; $k4++) { 
$name4=json_decode($re4)->date_program->$dt11[0][$k4-1]->name;
$description4=json_decode($re4)->date_program->$dt11[0][$k4-1]->description;
$beginTime4=json_decode($re4)->date_program->$dt11[0][$k4-1]->beginTime;
$endTime4=json_decode($re4)->date_program->$dt11[0][$k4-1]->endTime;
if( (str_replace(':','',$beginTime4))<(str_replace(':','',$endTime4))){
$chn.="<programme start=\"".$dt1.str_replace(':','',str_replace('-','',str_replace(' ','',str_replace('.0','',$beginTime4))))."00 +0800\" stop=\"".$dt1.str_replace(':','',str_replace('-','',str_replace(' ','',str_replace('.0','',$endTime4))))."00 +0800\"  channel=\"".$cid4[$idm4-1][1]."\">\n<title lang=\"zh\">". $name4."</title>\n<desc lang=\"zh\"> ". $description4."</desc>\n</programme>\n";

}

else{
$chn.="<programme start=\"".$dt22.str_replace(':','',str_replace('-','',str_replace(' ','',str_replace('.0','',$beginTime4))))."00 +0800\" stop=\"".$dt1.str_replace(':','',str_replace('-','',str_replace(' ','',str_replace('.0','',$endTime4))))."00 +0800\"  channel=\"".$cid4[$idm4-1][1]."\">\n<title lang=\"zh\">". $name4."</title>\n<desc lang=\"zh\"> ". $description4."</desc>\n</programme>\n";
}

}

    $tuu41 = json_decode($re4)->date_program->$dt11[1];
    foreach ($tuu41 as $item) {
        $name41 = $item->name;
        $description41 = $item->description;
        $beginTime41 = $item->beginTime;
        $endTime41 = $item->endTime;
        $chn .= "<programme start=\"" . $dt1.str_replace(':', '', str_replace('-', '', str_replace(' ', '', str_replace('.0', '', $beginTime41)))) . "00 +0800\" stop=\"" .$dt1. str_replace(':', '', str_replace('-', '', str_replace(' ', '', str_replace('.0', '', $endTime41)))) . "00 +0800\"  channel=\"".$cid4[$idm4-1][1]."\">\n<title lang=\"zh\">" . $name41 . "</title>\n<desc lang=\"zh\"> " . $description41 . "</desc>\n</programme>\n";
    }

$tuu411=json_decode($re4)->date_program->$dt12[0];
    $tuu412 = json_decode($re4)->date_program->$dt12[1];
$trm411=count($tuu411);
 for ($k411 = 1; $k411 < $trm411-1; $k411++) { 
$name411=json_decode($re4)->date_program->$dt12[0][$k411-1]->name;
$description411=json_decode($re4)->date_program->$dt12[0][$k411-1]->description;
$beginTime411=json_decode($re4)->date_program->$dt12[0][$k411-1]->beginTime;
$endTime411=json_decode($re4)->date_program->$dt12[0][$k411-1]->endTime;
if( (str_replace(':','',$beginTime411))<(str_replace(':','',$endTime411)))
{
$chn.="<programme start=\"".$dt2.str_replace(':','',str_replace('-','',str_replace(' ','',str_replace('.0','',$beginTime411))))."00 +0800\" stop=\"".$dt2.str_replace(':','',str_replace('-','',str_replace(' ','',str_replace('.0','',$endTime411))))."00 +0800\"  channel=\"".$cid4[$idm4-1][1]."\">\n<title lang=\"zh\">". $name411."</title>\n<desc lang=\"zh\"> ". $description411."</desc>\n</programme>\n";
}

else{
$chn.="<programme start=\"".$dt1.str_replace(':','',str_replace('-','',str_replace(' ','',str_replace('.0','',$beginTime411))))."00 +0800\" stop=\"".$dt2.str_replace(':','',str_replace('-','',str_replace(' ','',str_replace('.0','',$endTime411))))."00 +0800\"  channel=\"".$cid4[$idm4-1][1]."\">\n<title lang=\"zh\">". $name411."</title>\n<desc lang=\"zh\"> ". $description411."</desc>\n</programme>\n";
}
}

    $tuu412 = json_decode($re4)->date_program->$dt12[1];
    foreach ($tuu412 as $item) {
        $name412 = $item->name;
        $description412 = $item->description;
        $beginTime412 = $item->beginTime;
        $endTime412 = $item->endTime;
        $chn .= "<programme start=\"" . $dt2.str_replace(':', '', str_replace('-', '', str_replace(' ', '', str_replace('.0', '', $beginTime412)))) . "00 +0800\" stop=\"" .$dt2. str_replace(':', '', str_replace('-', '', str_replace(' ', '', str_replace('.0', '', $endTime412)))) . "00 +0800\"  channel=\"".$cid4[$idm4-1][1]."\">\n<title lang=\"zh\">" . $name412 . "</title>\n<desc lang=\"zh\"> " . $description41 . "</desc>\n</programme>\n";
    }
}


//7 4gtv
$url7='https://api2.4gtv.tv/Channel/GetChannelBySetId/1/pc/L';//主要目的抓取channel id。用$fs4GTV_ID
$ch7=curl_init();
curl_setopt($ch7,CURLOPT_URL,$url7);
curl_setopt($ch7,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch7,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch7,CURLOPT_RETURNTRANSFER,1);
/*
$headers7 = array（
'Host:d1jithvltpp1l1.cloudfront.net',
'User-Agent: okhttp/3.12.1',
'CLIENT-IP:1.160.0.29',
'X-FORWARDED-FOR:1.160.0.29', 
);
curl_setopt($ch7, CURLOPT_HTTPHEADER, $headers7);
*/
$re7=curl_exec($ch7);
$re7=str_replace('&','&amp;',$re7);
curl_close($ch7);
 $data7=json_decode($re7)->Data;
$tuu7=count($data7);
for ( $i7=0 ; $i7<=$tuu7-1 ; $i7++ ) {
$fsNAME=json_decode($re7)->Data[$i7]->fsNAME;
$fs4GTV_ID=json_decode($re7)->Data[$i7]->fs4GTV_ID;
$fnID=json_decode($re7)->Data[$i7]->fnID;
  $chn.="<channel id=\"".$fsNAME."\"><display-name lang=\"zh\">".$fsNAME."</display-name></channel>\n";//用於xml的channel輸出
                                  }
for ( $i7=0 ; $i7<=$tuu7-1 ; $i7++ ) {
$fsNAME=json_decode($re7)->Data[$i7]->fsNAME;
$fs4GTV_ID=json_decode($re7)->Data[$i7]->fs4GTV_ID;
$urk71='https://www.4gtv.tv/proglist/'.$fs4GTV_ID.'.txt';//主要是抓取節目預告內容
$ch71=curl_init();
curl_setopt($ch71,CURLOPT_URL,$urk71);
curl_setopt($ch71,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch71,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch71,CURLOPT_RETURNTRANSFER,1);
//curl_setopt($ch71, CURLOPT_HTTPHEADER, $headers7);	
//curl_setopt($ch71, CURLOPT_TIMEOUT, 30); // CURLOPT_TIMEOUT_MS
//curl_setopt($ch71,CURLOPT_ENCODING,'Vary: Accept-Encoding');
$rek71=curl_exec($ch71);
$rek71=str_replace('&','&amp;',$rek71);
curl_close($ch71);
$ryut7=count(json_decode($rek71, true));
for ($k7 =0; $k7 <= $ryut7-1; $k7++){
$sdate[$k7]=str_replace('-','',json_decode($rek71, true)[$k7]["sdate"]);//獲取節目開始日期
$edate[$k7]=str_replace('-','',json_decode($rek71, true)[$k7]["edate"]);//獲取節目結束日期
$stime[$k7]=str_replace(':','',json_decode($rek71, true)[$k7]["stime"]).' +0800';//獲取節目開始時間
$etime[$k7]=str_replace(':','',json_decode($rek71, true)[$k7]["etime"]).' +0800';//獲取節目結束時間
$chn.="<programme start=\"".$sdate[$k7].$stime[$k7]."\" stop=\"".$edate[$k7].$etime[$k7]."\" channel=\"".$fsNAME."\">\n<title lang=\"zh\">".json_decode($rek71, true)[$k7]["title"]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                                    }
                               }



   $chn.="<channel id=\"咪咕体育\"><display-name lang=\"zh\">咪咕体育</display-name></channel>\n";
                                      

$url8='https://vms-sc.miguvideo.com/vms-match/v6/staticcache/basic/match-list/normal-match-list/0/all/default/1/miguvideo';
 //$url8='https://v0-sc.miguvideo.com/vms-match/v6/match/balltypedata/1/miguvideo';


    $ch8 = curl_init();
    curl_setopt($ch8, CURLOPT_URL, $url8);
    curl_setopt($ch8, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch8, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch8, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch8, CURLOPT_TIMEOUT, 30); 
curl_setopt($ch8,CURLOPT_USERAGENT,'Mozilla/5.0');
curl_setopt($ch8,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re8 = curl_exec($ch8);
    curl_close($ch8);
//print $re8;

//$re98 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $re98);// 適合php7
//$re98=str_replace('&','&amp;',$re98);

 $data98=json_decode($re8)->body->matchList->$dt1;
//print_r( $data98);
$tuu98=count($data98);
//print $tuu98;

for ( $i98=0 ; $i98<=$tuu98-1 ; $i98++ ) {
$title=$data98[$i98]->title;  //题目
$startTime=$data98[$i98]->startTime;  //开始时间
$endTime=$data98[$i98]->endTime;  //题目
$pkInfoTitle=$data98[$i98]->pkInfoTitle;  //题目
//$matchField1=$data98[$i98]->matchField;  //比赛场地
$chn.="<programme start=\"".date("YmdHis",$startTime/1000).'00 +0800'."\" stop=\"".date("YmdHis",$endTime/1000).'00 +0800'."\"  channel=\"咪咕体育\">\n<title lang=\"zh\">".$title.$pkInfoTitle."</title>\n<desc lang=\"zh\">/</desc>\n</programme>\n";
}

 $data981=json_decode($re8)->body->matchList->$dt2;
$tuu981=count($data981);

for ( $i981=0 ; $i981<=$tuu981-1 ; $i981++ ) {
$title1=$data981[$i981]->title;  //题目
$startTime1=$data981[$i981]->startTime;  //开始时间
$endTime1=$data981[$i981]->endTime;  //题目
$pkInfoTitle1=$data981[$i981]->pkInfoTitle;  //题目
//$matchField1=$data981[$i981]->matchField;  //比赛场地
$chn.="<programme start=\"".date("YmdHis",$startTime1/1000).'00 +0800'."\" stop=\"".date("YmdHis",$endTime1/1000).'00 +0800'."\"  channel=\"咪咕体育\">\n<title lang=\"zh\">".$title1.$pkInfoTitle1."</title>\n<desc lang=\"zh\">/</desc>\n</programme>\n";
}
//17奥林匹克官网直播
   $chn.="<channel id=\"奥林匹克官网直播\"><display-name lang=\"zh\">奥林匹克官网直播</display-name></channel>\n";
$time17=microtime(true)*10000;
 $url17='https://olympics.com/zh/api/v1/live/video/'.$dt11.'/octv/epglist?channelid=OCTV&_='.$time17;

    $ch17 = curl_init();
    curl_setopt($ch17, CURLOPT_URL, $url17);
    curl_setopt($ch17, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch17, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch17, CURLOPT_SSL_VERIFYHOST, FALSE);
     curl_setopt($ch17,CURLOPT_ENCODING,'Vary: Accept-Encoding');
    $re17 = curl_exec($ch17);
    curl_close($ch17);
   $re17=compress_html($re17);
//print $re17;
preg_match_all('|"startTime":"(.*?)"|i',$re17,$us17,PREG_SET_ORDER);//播放开始時間
preg_match_all('|"endTime":"(.*?)"|i',$re17,$ue17,PREG_SET_ORDER);//播放结束時間
preg_match_all('|"title":"(.*?)",|i',$re17,$um17,PREG_SET_ORDER);//播放節目
$trm17=count($us17);

//print_r( $us17);
//print_r( $ue17);
//print_r( $um17);

for ($k17 = 1; $k17 <= $trm17-1 ; $k17++) {  
       $chn.="<programme start=\"".str_replace('T','',str_replace('-','',str_replace(':','',$us17[$k17-1][1]))).' +0000'."\" stop=\"".str_replace('T','',str_replace('-','',str_replace(':','',$us17[$k17][1]))).' +0000'."\" channel=\"奥林匹克官网直播\">\n<title lang=\"zh\">".str_replace('|','',str_replace( '-','',$um17))[($k17-1)*2][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                                                                                           }   

  $chn.="<programme start=\"".str_replace('T','',str_replace('-','',str_replace(':','',$us17[ $trm17-1][1]))).' +0000'."\" stop=\"".str_replace('T','',str_replace('-','',str_replace(':','',$ue17[ $trm17-1][1]))).' +0000'."\" channel=\"奥林匹克官网直播\">\n<title lang=\"zh\">".str_replace('|','',str_replace( '-','',$um17))[($trm17-1)*2][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";


//27凱痣有線

$id270=100200;//起始节目编号
$cid27=array(


  array('1','節目表HD'),
array('3','公用頻道'),
array('4','公用頻道'),
array('5','CNN International'),
    array('606','民視無線台'),
    array('7','人間衛視'),
    array('8','台灣電視台'),
    array('1009','大愛電視台'),
   array('1010','中視數位台'),
 array('11','霹靂電視台'),
 array('12','中華電視台'),
 array('1013','公共電視台'),
 array('14','公視台語台'),
 array('15','好消息衛星電視台'),
 array('16','原住民族電視台'),
 array('17','客家電視台'),
 array('18','BBC Earth HD'),
 array('19','Discovery'),
 array('20','Discovery 旅遊生活頻道'),
 array('21','動物星球頻道'),
array('22','Cartoon Network'),
array('23','尼克兒童頻道HD'),
array('24','MOMO親子台'),
array('25','東森幼幼台'),
array('26','緯來綜合台'),
array('27','八大第一台'),
array('1028','八大綜合台'),
array('29','三立台灣台'),
array('30','三立都會台'),
array('631','衛視中文台'),
array('1032','東森綜合台'),
array('1033','超視'),
array('34','東森購物2台'),
array('35','momo2台'),
array('1036','中天綜合台'),
array('37','東風衛視台'),
array('38','MUCH TV'),
array('39','中天娛樂台'),
array('1040','東森戲劇台'),
array('1041','八大戲劇台'),
array('42','TVBS歡樂台'),
array('43','緯來戲劇台'),
array('44','高點電視台'),
array('46','JET 綜合台HD'),
array('47','東森購物3台'),
array('48','東森購物1台'),
array('49','momo 1台'),
array('106','三立iNEWS HD'),
array('1049','壹電視新聞台'),
array('50','era news 年代新聞'),
array('51','東森新聞台'),
array('296','華視新聞資訊台HD'),
array('53','民視新聞台'),
array('54','三立新聞台'),
array('55','TVBS 新聞台'),
array('1056','TVBS'),
array('1057','東森財經新聞台'),
array('1058','非凡新聞台'),
array('59','ViVa 1台'),
array('60','東森購物5台'),
array('230','CATCHPLAY電影台HD'),
array('62','東森電影台'),
array('63','緯來電影台'),
array('64','LS TIME電影台'),
array('65','HBO'),
array('66','東森洋片台'),
array('67','AXN'),
array('68','好萊塢電影台'),
array('120','amc電影台HD'),
array('70','CINEMAX'),
array('71','緯來育樂台'),
array('72','緯來體育台'),
array('1098','DAZN 1'),
array('210','DAZN 2'),
array('200','MOMO綜合台'),
array('1076','博斯運動一台'),
array('75','緯來日本台'),
array('77','國興衛視'),
array('304','BBC Lifestyle HD'),
array('2084','靖天購物一台'),
array('95','Animax(HD)'),
array('108','信吉電視台'),
array('46','JET 綜合台HD'),
array('2080','中台灣生活網頻道HD'),
array('307','寰宇新聞台'),
array('1086','鏡電視新聞台HD'),
array('85','冠軍電視台'),
array('76','Z頻道HD'),
array('1128','ETtoday綜合台HD'),
array('88','非凡商業台'),
array('86','八大娛樂台'),
array('91','運通財經綜合台'),
array('94','全球財經網頻道'),
array('211','誠心電視台'),
array('1084','華藝台灣台'),
array('96','台灣綜合台'),
array('833','海豚綜合台'),
array('112','威達超舜生活台'),
array('87','台灣藝術台'),
array('117','樂視台'),
array('101','華藏衛星電視台'),
array('103','十方法界電視台'),
array('82','世界衛星電視台'),
array('105','佛衛電視慈悲台'),
array('98','信大電視台'),
array('840','全大電視台'),
array('158','美麗人生購物台HD'),
array('1109','正德電視台'),
array('99','天良綜合台'),
array('100','番薯衛星電視台'),
array('838','富立電視台'),
array('97','華視教育體育文化台'),
array('113','冠軍夢想台'),
array('115','新天地民俗台'),
array('116','三聖電視台'),
array('1117','紅豆2台HD'),
array('1107','天美麗電視台HD'),
array('120','雙子衛視'),
array('1121','高雄都會台HD'),
array('107','NHK HD'),
array('1123','國會頻道1台'),
array('124','國會頻道2台'),
array('125','幸福空間居家台'),
array('126','高點育樂台'),
array('1127','滾動力 rollor HD'),
array('79','MTV HD'),
array('129','智林體育台'),
array('130','唯心電視台'),
array('145','Global Trekker HD'),
array('146','ROCK Action HD'),
array('147','TRACE Sport Stars HD'),
array('148','美食星球頻道HD'),
array('149','寰宇財經台HD'),
array('301','民視第一台'),
array('1300','民視台灣台'),
array('153','中視菁采台'),
array('1154','中視新聞台'),
array('302','台視新聞台'),
array('308','台視財經台'),
array('157','台視綜合台'),
array('216','小公視'),
array('2202','PET CLUB TV寵物頻道HD'),
array('1303','CBeebies HD'),
array('237','CARTOONITO HD'),
array('238','達文西頻道'),
array('234','Discovery Asia'),
array('1207','TechStorm HD'),
array('2208','ROCK EXTREME HD'),
array('209','歷史頻道HD'),
array('2211','Discovery科學頻道'),
array('194','tvN '),
array('212','DREAMWORKS'),
array('1213','Lifetime HD'),
array('213','緯來精采台HD'),
array('1215','靖洋卡通台Nice Bingo HD'),
array('1216','Nick Jr.'),
array('1219','Warner TV'),
array('2218','EVE'),
array('2219','Cmusic'),
array('220','Food Network美食台頻道'),
array('221','Travel Channel'),
array('1085','寰宇新聞台灣台'),
array('1305','亞洲旅遊台'),
array('1224','CI 罪案偵緝頻道HD'),
array('206','亞洲美食頻道'),
array('239','HGTV居家樂活頻道'),
array('1234','MTV Live HD'),
array('256','TV5MONDE'),
array('236','古典音樂台'),
array('1230','LOVE NATURE'),
array('1231','DMAX HD'),
array('1232','梅迪奇藝術頻道HD'),
array('1233','靖天映畫台HD'),
array('1234','時尚頻道HD'),
array('122','HBO HD頻道'),
array('163','HBO強檔鉅獻HD)'),
array('162','HBO原創鉅獻HD'),
array('164','HBO溫馨家庭HD'),
array('1239','CinemaWorld HD'),
array('1240','HITS HD'),
array('1241','ROCK Entertainment HD'),
array('1225','歐洲體育台'),
array('224','博斯高球台HD'),
array('245','博斯高球二台'),
array('240','博斯無限二台'),
array('241','博斯魅力網'),
array('242','博斯網球台'),
array('243','博斯運動二台'),
array('244','博斯無限台'),
array('111','大愛2台'),
array('330','好消息2台'),
array('309','TaiwanPlus HD'),
array('254','Bloomberg Television'),
array('311','CNBC Asia'),
array('252','BBC World News'),
array('306','Arirang TV'),
array('255','德國之聲電視台'),
array('228','TV5法式生活頻道'),
array('290','彩虹頻道'),
array('291','彩虹E台'),
array('292','彩虹Movie台'),
array('293','K頻道'),
array('904','Pandora潘朵啦高畫質玩美台'),
array('905','Pandora潘朵啦高畫質粉紅台'),
array('906','驚豔成人電影台'),
array('907','香蕉台'),
array('297','松視1台'),
array('298','松視2台'),
array('299','松視3台'),
array('300','松視4台'),
array('913','樂活頻道HD'),

array('912','JStar極限台電影頻道')

//https://www.kbro.com.tw/K01/catv-table-channel_1_0_2.html
    );
$nid27=sizeof($cid27);

for ($idm27 = 1; $idm27 <= $nid27; $idm27++){
 $idd27=$id270+$idm27;
   $chn.="<channel id=\"".$cid27[$idm27-1][1]."\"><display-name lang=\"zh\">".$cid27[$idm27-1][1]."</display-name></channel>\n";
}

for ($idm27 = 1; $idm27 <= $nid27; $idm27++){
 $idd27=$id270+$idm27;
$urk27='https://www.kbro.com.tw/do/getpage_catvtable.php?callback=result&action=get_channelprogram&channelid='.$cid27[$idm27-1][0].'&showtime='.$dt1;
$ch27=curl_init();
curl_setopt($ch27,CURLOPT_URL,$urk27);
curl_setopt($ch27,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch27,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch27,CURLOPT_RETURNTRANSFER,1);
$rek27=curl_exec($ch27);
curl_close($ch27);
$rek27 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $rek27);// 適合php7
preg_match_all('|"programname":"(.*?)","playdate|i',$rek27,$un27,PREG_SET_ORDER);//播放節目內容
preg_match_all('|"starttime":"(.*?)","endtime|i',$rek27,$ul27,PREG_SET_ORDER);//播放節目開始時間
preg_match_all('|"endtime":"(.*?)","eventid|i',$rek27,$uk27,PREG_SET_ORDER);//播放節目結束時間
$ryut27=count($uk27);
for ($k27 = 1; $k27 <= $ryut27; $k27++){
    $chn.="<programme start=\"".str_replace(' ','',str_replace('-','',str_replace(':','',$ul27[$k27-1][1]))).' +0800'."\" stop=\"".str_replace(' ','',str_replace('-','',str_replace(':','',$uk27[$k27-1][1]))).' +0800'."\" channel=\"".$cid27[$idm27-1][1]."\">\n<title lang=\"zh\">".str_replace('<spanclass="live-btn">播放中</span>','',$un27[$k27-1][1])."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";

                                                               }

$urk271='https://www.kbro.com.tw/do/getpage_catvtable.php?callback=result&action=get_channelprogram&channelid='.$cid27[$idm27-1][0].'&showtime='.$dt2;
$ch271=curl_init();
curl_setopt($ch271,CURLOPT_URL,$urk271);
curl_setopt($ch271,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch271,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch271,CURLOPT_RETURNTRANSFER,1);
$rek271=curl_exec($ch271);
curl_close($ch271);
$rek271 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $rek271);// 適合php7
preg_match_all('|"programname":"(.*?)","playdate|i',$rek271,$un271,PREG_SET_ORDER);//播放節目內容
preg_match_all('|"starttime":"(.*?)","endtime|i',$rek271,$ul271,PREG_SET_ORDER);//播放節目開始時間
preg_match_all('|"endtime":"(.*?)","eventid|i',$rek271,$uk271,PREG_SET_ORDER);//播放節目結束時間
$ryut271=count($uk271);
for ($k271 = 1; $k271 <= $ryut271-1; $k271++){

    $chn.="<programme start=\"".str_replace(' ','',str_replace('-','',str_replace(':','',$ul271[$k271-1][1]))).' +0800'."\" stop=\"".str_replace(' ','',str_replace('-','',str_replace(':','',$uk271[$k271-1][1]))).' +0800'."\" channel=\"".$cid27[$idm27-1][1]."\">\n<title lang=\"zh\">".$un271[$k271-1][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";

                                                                       }
                                                                                           }



$id200=600020;//起始节目编号
$cid200=array(
 array('11','KBS 1'),
 array('12','KBS 2'),
 array('14','KBS WORLD'),
 array('81','KBS NEWS'),
 array('N91','KBS DRAMA'),
 array('N92','KBS JOY'),
 array('N94','KBS STORY'),
 array('N93','KBS LIFE'),
 array('N96','KBS KIDS'),
);
$nid200=sizeof($cid200);
for ($idm200= 1; $idm200 <= $nid200; $idm200++){
 $idd200=$id200+$idm200;
   $chn.="<channel id=\"".$cid200[$idm200-1][1]."\"><display-name lang=\"zh\">".$cid200[$idm200-1][1]."</display-name></channel>\n";
}

for ($idm200= 1; $idm200 <= $nid200; $idm200++){
$url200='https://static.api.kbs.co.kr/mediafactory/v1/schedule/weekly?&rtype=jsonp&local_station_code=00&channel_code='.$cid200[$idm200-1][0].'&program_planned_date_from='.$dt1.'&program_planned_date_to='.$dt1.'&callback=weekly_schedule';
 $idd200=$id200+$idm200;
$ch200=curl_init();
curl_setopt($ch200,CURLOPT_URL,$url200);
curl_setopt($ch200,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch200,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch200,CURLOPT_RETURNTRANSFER,1);
$re200=curl_exec($ch200);
curl_close($ch200);
$rk200=stripslashes($re200);

 preg_match('/"program_planned_date":"(.*?)",/',$rk200,$date200);//播放日期
 preg_match_all('/"program_planned_start_time":"(.*?)",/',$rk200,$start_time200,PREG_SET_ORDER);//播放開始時間
 preg_match_all('/"program_planned_end_time":"(.*?)",/',$rk200,$end_time200,PREG_SET_ORDER);//播放結束時間
 preg_match_all('/"program_title":"(.*?)",/',$rk200,$title200,PREG_SET_ORDER);//播放節目名稱
 preg_match_all('/"program_intention":"(.*?)",/',$rk200,$intention200,PREG_SET_ORDER);//播放節目名稱
$tuu200=count($start_time200);
//print_r($start_time200);
//print_r($end_time200);
for ( $i200=1 ; $i200<=$tuu200-1 ; $i200++ ) {
$start_time200[$i200][1]=str_replace('0000','00',$start_time200[$i200][1]);
$end_time200[$i200][1]=str_replace('0000','00',$end_time200[$i200][1]);
$chn.="<programme start=\"".$date200[1].$start_time200[$i200-1][1].' +0900'."\" stop=\"".$date200[1].$end_time200[$i200-1][1].' +0900'."\" channel=\"".$cid200[$idm200-1][1]."\">\n<title lang=\"zh\">".$title200[$i200-1][1]."</title>\n<desc lang=\"zh\"></desc>\n</programme>\n";


}

$url201='https://static.api.kbs.co.kr/mediafactory/v1/schedule/weekly?&rtype=jsonp&local_station_code=00&channel_code='.$cid200[$idm200-1][0].'&program_planned_date_from='.$dt2.'&program_planned_date_to='.$dt2.'&callback=weekly_schedule';
$ch201=curl_init();
curl_setopt($ch201,CURLOPT_URL,$url201);
curl_setopt($ch201,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch201,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch201,CURLOPT_RETURNTRANSFER,1);
$re201=curl_exec($ch201);
curl_close($ch201);
$rk201=stripslashes($re201);
 preg_match('/"program_planned_date":"(.*?)",/',$rk201,$date201);//播放日期
 preg_match_all('/"program_planned_start_time":"(.*?)",/',$rk201,$start_time201,PREG_SET_ORDER);//播放開始時間
 preg_match_all('/"program_planned_end_time":"(.*?)",/',$rk201,$end_time201,PREG_SET_ORDER);//播放結束時間
 preg_match_all('/"program_title":"(.*?)",/',$rk201,$title201,PREG_SET_ORDER);//播放節目名稱
 preg_match_all('/"program_intention":"(.*?)",/',$rk201,$intention201,PREG_SET_ORDER);//播放節目名稱
$tuu201=count($start_time201);
for ( $i201=1 ; $i201<=$tuu201-1 ; $i201++ ) {
$start_time201[$i201][1]=str_replace('0000','00',$start_time201[$i201][1]);
$end_time201[$i201][1]=str_replace('0000','00',$end_time201[$i201][1]);
$chn.="<programme start=\"".$date201[1].$start_time201[$i201-1][1].' +0900'."\" stop=\"".$date201[1].$end_time201[$i201-1][1].' +0900'."\" channel=\"".$cid200[$idm200-1][1]."\">\n<title lang=\"zh\">".$title201[$i201-1][1]."</title>\n<desc lang=\"zh\"></desc>\n</programme>\n";


}
}

$id300=600040;//起始节目编号

//$time=time();

$cid300=array(
 array('SBS','SBS'),
 array('Plus','SBS plus'),
 array('ETV','SBS fune'),
 array('Fil','SBS Fil'),
 array('ESPN','SBS sport'),
 array('Golf','SBS Golf'),
 array('CNBC','SBS Biz'),
 array('MTV','SBS MTV'),
 array('Nick','SBS kizmom'),
);
$nid300=sizeof($cid300);
for ($idm300= 1; $idm300 <= $nid300; $idm300++){
 $idd300=$id300+$idm300;
   $chn.="<channel id=\"".$cid300[$idm300-1][1]."\"><display-name lang=\"zh\">".$cid300[$idm300-1][1]."</display-name></channel>\n";
}
for ($idm300= 1; $idm300 <= $nid300; $idm300++){
$url300='https://static.cloud.sbs.co.kr/schedule/'.$dt4.'/'.$cid300[$idm300-1][0].'.json?_=';
$idd300=$id300+$idm300;
$ch300=curl_init();
curl_setopt($ch300,CURLOPT_URL,$url300);
curl_setopt($ch300,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch300,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch300,CURLOPT_RETURNTRANSFER,1);
$re300=curl_exec($ch300);
curl_close($ch300);
 preg_match_all('/"start_time":"(.*?)",/',$re300,$start_time300,PREG_SET_ORDER);//播放開始時間
 preg_match_all('/"title":"(.*?)",/',$re300,$title300,PREG_SET_ORDER);//播放節目名稱
$tuu300=count($start_time300);
for ( $i300=1 ; $i300<=$tuu300-1 ; $i300++ ) {
$chn.="<programme start=\"".$dt1.str_replace(':','',$start_time300[$i300-1][1]).'00 +0900'."\" stop=\"".$dt1.str_replace(':','',$start_time300[$i300][1]).'00 +0900'."\" channel=\"".$cid300[$idm300-1][1]."\">\n<title lang=\"zh\">".$title300[$i300-1][1]."</title>\n<desc lang=\"zh\"></desc>\n</programme>\n";
}
$url301='https://static.cloud.sbs.co.kr/schedule/'.$dt5.'/'.$cid300[$idm300-1][0].'.json?_=';
$idd300=$id300+$idm300;
$ch301=curl_init();
curl_setopt($ch301,CURLOPT_URL,$url301);
curl_setopt($ch301,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch301,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch301,CURLOPT_RETURNTRANSFER,1);
$re301=curl_exec($ch301);
curl_close($ch301);
 preg_match_all('/"start_time":"(.*?)",/',$re301,$start_time301,PREG_SET_ORDER);//播放開始時間
 preg_match_all('/"title":"(.*?)",/',$re301,$title301,PREG_SET_ORDER);//播放節目名稱
$tuu301=count($start_time301);
for ( $i301=1 ; $i301<=$tuu301-1 ; $i301++ ) {
$chn.="<programme start=\"".$dt2.str_replace(':','',$start_time301[$i301-1][1]).'00 +0900'."\" stop=\"".$dt2.str_replace(':','',$start_time301[$i301][1]).'00 +0900'."\" channel=\"".$cid300[$idm300-1][1]."\">\n<title lang=\"zh\">".$title301[$i301-1][1]."</title>\n<desc lang=\"zh\"></desc>\n</programme>\n";
}

}

//以下為MBC節目預告
$chn.="<channel id=\"MBC\"><display-name lang=\"zh\">MBC</display-name></channel>\n";
$url498='https://control.imbc.com/Schedule/TV?callback=Schedule_TV_'.$dt1.$dt6.'&sDate='.$dt1.'&sType=ALL';
$ch498=curl_init();
curl_setopt($ch498,CURLOPT_URL,$url498);
curl_setopt($ch498,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch498,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch498,CURLOPT_RETURNTRANSFER,1);
$re498=curl_exec($ch498);
curl_close($ch498);
//print $re498;
 preg_match_all('/"StartTime": "(.*?)",/',$re498,$StartTime498,PREG_SET_ORDER);//播放開始時間
preg_match_all('/"EndTime": "(.*?)",/',$re498,$EndTime498,PREG_SET_ORDER);//播放開始時間
 preg_match_all('/"Title": "(.*?)",/',$re498,$Title498,PREG_SET_ORDER);//播放節目名稱
$tuu498=count($StartTime498);
for ( $i498=1 ; $i498<=$tuu498-1 ; $i498++ ) {
$chn.="<programme start=\"".$dt1.$StartTime498[$i498-1][1].'00 +0900'."\" stop=\"".$dt1.$EndTime498[$i498-1][1].'00 +0900'."\" channel=\"MBC\">\n<title lang=\"zh\">".$Title498[$i498-1][1]."</title>\n<desc lang=\"zh\"></desc>\n</programme>\n";
}
$url499='https://control.imbc.com/Schedule/TV?callback=Schedule_TV_'.$dt2.$dt6.'&sDate='.$dt2.'&sType=ALL';
$ch499=curl_init();
curl_setopt($ch499,CURLOPT_URL,$url499);
curl_setopt($ch499,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch499,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch499,CURLOPT_RETURNTRANSFER,1);
$re499=curl_exec($ch499);
curl_close($ch499);
//print $re498;
 preg_match_all('/"StartTime": "(.*?)",/',$re499,$StartTime499,PREG_SET_ORDER);//播放開始時間
preg_match_all('/"EndTime": "(.*?)",/',$re499,$EndTime499,PREG_SET_ORDER);//播放開始時間
 preg_match_all('/"Title": "(.*?)",/',$re499,$Title499,PREG_SET_ORDER);//播放節目名稱
$tuu499=count($StartTime499);
for ( $i499=1 ; $i499<=$tuu499-1 ; $i499++ ) {
$chn.="<programme start=\"".$dt2.$StartTime499[$i499-1][1].'00 +0900'."\" stop=\"".$dt2.$EndTime498[$i499-1][1].'00 +0900'."\" channel=\"MBC\">\n<title lang=\"zh\">".$Title499[$i499-1][1]."</title>\n<desc lang=\"zh\"></desc>\n</programme>\n";
}
$id500=600061;//起始节目编号
//$time=time();
$cid500=array(
// array('TV','ALL','MBC'),
array('MBCPlus','P_everyone','MBC every1'),
array('MBCPlus','P_drama','MBC drama'),
array('MBCPlus','P_music','MBC music'),
array('MBCPlus','P_on','MBC on'),
array('MBCPlus','MBCNET','MBC net'),
array('MBCPlus','P_allthekpop','MBC popt'),
//array('TVPlus','C0028','quad static driving'),
//array('MBCPlus','P_on','1126','MBC on'),
//array('MBCPlus','P_on','1126','MBC on'),
//array('MBCPlus','P_on','1126','MBC on'),

);
$nid500=sizeof($cid500);
for ($idm500= 1; $idm500 <= $nid500; $idm500++){
 $idd500=$id500+$idm500;
   $chn.="<channel id=\"".$cid500[$idm500-1][2]."\"><display-name lang=\"zh\">".$cid500[$idm500-1][2]."</display-name></channel>\n";
}
for ($idm500= 1; $idm500 <= $nid500; $idm500++){

 $idd500=$id500+$idm500;
$url500='https://control.imbc.com/Schedule/'.$cid500[$idm500-1][0].'?callback='.$cid500[$idm500-1][0].$dt1.'_'.$cid500[$idm500-1][1].$dt6.'&sDate='.$dt1.'&sType='.$cid500[$idm500-1][1];
$ch500=curl_init();
curl_setopt($ch500,CURLOPT_URL,$url500);
curl_setopt($ch500,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch500,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch500,CURLOPT_RETURNTRANSFER,1);
$re500=curl_exec($ch500);
curl_close($ch500);
//print $re500;
 preg_match_all('/"StartTime": "(.*?)",/',$re500,$StartTime500,PREG_SET_ORDER);//播放開始時間
preg_match_all('/"EndTime": "(.*?)",/',$re500,$EndTime500,PREG_SET_ORDER);//播放開始時間
 preg_match_all('/ProgramTitle": "(.*?)",/',$re500,$Title500,PREG_SET_ORDER);//播放節目名稱
$tuu500=count($StartTime500);
for ( $i500=1 ; $i500<=$tuu500-1 ; $i500++ ) {
$chn.="<programme start=\"".$dt1.$StartTime500[$i500-1][1].'00 +0900'."\" stop=\"".$dt1.$EndTime500[$i500-1][1].'00 +0900'."\" channel=\"".$cid500[$idm500-1][2]."\">\n<title lang=\"zh\">".$Title500[$i500-1][1]."</title>\n<desc lang=\"zh\"></desc>\n</programme>\n";
}

$url501='https://control.imbc.com/Schedule/'.$cid500[$idm500-1][0].'?callback='.$cid500[$idm500-1][0].$dt2.'_'.$cid500[$idm500-1][1].$dt3.'&sDate='.$dt2.'&sType='.$cid500[$idm500-1][1];
$ch501=curl_init();
curl_setopt($ch501,CURLOPT_URL,$url501);
curl_setopt($ch501,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch501,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch501,CURLOPT_RETURNTRANSFER,1);
$re501=curl_exec($ch501);
curl_close($ch501);
 preg_match_all('/"StartTime": "(.*?)",/',$re501,$StartTime501,PREG_SET_ORDER);//播放開始時間
preg_match_all('/"EndTime": "(.*?)",/',$re501,$EndTime501,PREG_SET_ORDER);//播放開始時間
 preg_match_all('/"ProgramTitle": "(.*?)",/',$re501,$Title501,PREG_SET_ORDER);//播放節目名稱
$tuu501=count($StartTime501);
for ( $i501=1 ; $i501<=$tuu501-1 ; $i501++ ) {
$chn.="<programme start=\"".$dt2.$StartTime501[$i501-1][1].'00 +0900'."\" stop=\"".$dt2.$EndTime501[$i501-1][1].'00 +0900'."\" channel=\"".$cid500[$idm500-1][2]."\">\n<title lang=\"zh\">".$Title501[$i501-1][1]."</title>\n<desc lang=\"zh\"></desc>\n</programme>\n";
}
}

//喀秋莎
   $chn.="<channel id=\"喀秋莎\"><display-name lang=\"zh\">喀秋莎</display-name></channel>\n";
//$cookie700='Cookie: _ga=GA1.2.1907381023.1658108180; _gid=GA1.2.1152379899.1658108180; _ym_uid=1658108180799345290; _ym_d=1658108180; _ym_isad=1; _ym_visorc=w';
$url700='https://www.katyusha.tv/zh-hans/grid';
//$url200='https://www.katyusha.tv/zh-hans/grid?date='.$dt1;
$ch700=curl_init();
curl_setopt($ch700,CURLOPT_URL,$url700);
curl_setopt($ch700,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch700,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch700,CURLOPT_RETURNTRANSFER,1);
$headers700=[
'Host: www.katyusha.tv',
'Connection: keep-alive',
'Upgrade-Insecure-Requests: 1',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36',
'Sec-Fetch-Site: same-origin',
'Sec-Fetch-Mode: navigate',
'Sec-Fetch-User: ?1',
'Sec-Fetch-Dest: document',
'Referer: https://www.katyusha.tv/zh-hans/grid',
];
curl_setopt($ch700,CURLOPT_HTTPHEADER,$headers700);
$re700=curl_exec($ch700);
curl_close($ch700);
$re700 = preg_replace('/\s(?=)/', '',$re700);
$re700=str_replace(':','',$re700);
 preg_match_all('|<divclass="broadcast-media_time">(.*?)</div>|i',$re700,$start_time700,PREG_SET_ORDER);//播放開始時間
 preg_match_all('|<h4class="mt-0mb-2broadcast-media_title">(.*?)</h4>|i',$re700,$title700,PREG_SET_ORDER);//播放節目名稱
 preg_match_all('|<pclass="broadcast-media_text">(.*?)</p>|i',$re700,$intention700,PREG_SET_ORDER);//播放節目介紹
$tuu700=count($start_time700);
for ( $i700=1 ; $i700<=$tuu700 ; $i700++ ) {
  if ($start_time700[$i700-1][1]<600){
        if ($start_time700[$i700][1]>00){
$chn.="<programme start=\"".$dt2.sprintf("%04d", $start_time700[$i700-1][1]).'00 +0800'."\" stop=\"".$dt2.sprintf("%04d", $start_time700[$i700][1]).'00 +0800'."\" channel=\"喀秋莎\">\n<title lang=\"zh\">".$title700[$i700-1][1]."</title>\n<desc lang=\"zh\">".$intention700[$i700-1][1]."</desc>\n</programme>\n";
                                                            }
}
  if ($start_time700[$i700-1][1]>=600){
   if ($start_time700[$i700-1][1]<1000){ 
$chn.="<programme start=\"".$dt1.sprintf("%04d", $start_time700[$i700-1][1]).'00 +0800'."\" stop=\"".$dt1.sprintf("%04d", $start_time700[$i700][1]).'00 +0800'."\" channel=\"喀秋莎\">\n<title lang=\"zh\">".$title700[$i700-1][1]."</title>\n<desc lang=\"zh\">".$intention700[$i700][1]."</desc>\n</programme>\n";
                                                        }
                                                        }
   if ($start_time700[$i700][1]>=1000){ 

 if ($start_time700[$i700-1][1]<1000){
$chn.="<programme start=\"".$dt1.sprintf("%04d", $start_time700[$i700-1][1]).'00 +0800'."\" stop=\"".$dt1.sprintf("%04d", $start_time700[$i700][1]).'00 +0800'."\" channel=\"喀秋莎\">\n<title lang=\"zh\">".$title700[$i700-1][1]."</title>\n<desc lang=\"zh\">".$intention700[$i700-1][1]."</desc>\n</programme>\n";

                                                     } 
if ($start_time700[$i700][1]>=1000){
       if ($start_time700[$i700][1]<=2400){
                              if ($start_time700[$i700][1]<$start_time700[$i700+1][1]){   
                         

$chn.="<programme start=\"".$dt1.sprintf("%04d", $start_time700[$i700][1]).'00 +0800'."\" stop=\"".$dt1.sprintf("%04d", $start_time700[$i700+1][1]).'00 +0800'."\" channel=\"喀秋莎\">\n<title lang=\"zh\">".$title700[$i700][1]."</title>\n<desc lang=\"zh\">".$intention700[$i700][1]."</desc>\n</programme>\n";

                                                                                                                    }


                            if ($start_time700[$i700][1]>$start_time700[$i700+1][1]){  

$chn.="<programme start=\"".$dt1.sprintf("%04d", $start_time700[$i700][1]).'00 +0800'."\" stop=\"".$dt2.sprintf("%04d", $start_time700[$i700+1][1]).'00 +0800'."\" channel=\"喀秋莎\">\n<title lang=\"zh\">".$title700[$i700][1]."</title>\n<desc lang=\"zh\">".$intention700[$i700][1]."</desc>\n</programme>\n";

                                                                                                                  }
                                                }
                                                                        }
}
}
$chn.="<programme start=\"".$dt2.sprintf("%04d", $start_time700[$tuu700-1][1]).'00 +0800'."\" stop=\"".$dt2.'062000 +0800'."\" channel=\"喀秋莎\">\n<title lang=\"zh\">".$title700[$tuu700-1][1]."</title>\n<desc lang=\"zh\">".$intention700[$i700][1]."</desc>\n</programme>\n";

//$cookie701='Cookie: _ga=GA1.2.1907381023.1658108180; _gid=GA1.2.1152379899.1658108180; _ym_uid=1658108180799345290; _ym_d=1658108180; _ym_isad=1; _ym_visorc=w';
//$url701='https://www.katyusha.tv/zh-hans/grid';
$url701='https://www.katyusha.tv/zh-hans/grid?date='.$dt12;
//https://www.katyusha.tv/zh-hans/grid?date=2022-07-19
//https://www.katyusha.tv/zh-hans/grid?date=2022-07-19
$ch701=curl_init();
curl_setopt($ch701,CURLOPT_URL,$url701);
curl_setopt($ch701,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch701,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch701,CURLOPT_RETURNTRANSFER,1);
$headers701=[
'Host: www.katyusha.tv',
'Connection: keep-alive',
'sec-ch-ua: " Not;A Brand";v="99", "Microsoft Edge";v="103", "Chromium";v="103"',
'sec-ch-ua-mobile: ?0',
'sec-ch-ua-platform: "Windows"',
'Upgrade-Insecure-Requests: 1',
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.5060.114 Safari/537.36 Edg/103.0.1264.49',
'Sec-Fetch-Site: same-origin',
'Sec-Fetch-Mode: navigate',
'Sec-Fetch-User: ?1',
'Sec-Fetch-Dest: document',
'Referer: https://www.katyusha.tv/zh-hans/grid',
];

curl_setopt($ch701,CURLOPT_HTTPHEADER,$headers701);
$re701=curl_exec($ch701);
curl_close($ch701);
$re701 = preg_replace('/\s(?=)/', '',$re701);
$re701=str_replace(':','',$re701);
//print $re701;
 preg_match_all('|<divclass="broadcast-media_time">(.*?)</div>|i',$re701,$start_time701,PREG_SET_ORDER);//播放開始時間
 preg_match_all('|<h4class="mt-0mb-2broadcast-media_title">(.*?)</h4>|i',$re701,$title701,PREG_SET_ORDER);//播放節目名稱
 preg_match_all('|<pclass="broadcast-media_text">(.*?)</p>|i',$re701,$intention701,PREG_SET_ORDER);//播放節目介紹
$tuu701=count($start_time701);
for ( $i701=0 ; $i701<=$tuu701-2 ; $i701++ ) {

  if ($start_time701[$i701][1]<600){
        if ($start_time701[$i701+1][1]>00){


$chn.="<programme start=\"".$dt21.sprintf("%04d", $start_time701[$i701][1]).'00 +0800'."\" stop=\"".$dt21.sprintf("%04d", $start_time701[$i701+1][1]).'00 +0800'."\" channel=\"喀秋莎\">\n<title lang=\"zh\">".$title701[$i701][1]."</title>\n<desc lang=\"zh\">".$intention701[$i701][1]."</desc>\n</programme>\n";
                                                            }
}
  if ($start_time701[$i701+1][1]>=600){

   if ($start_time701[$i701+1][1]<1000){ 

$chn.="<programme start=\"".$dt2.sprintf("%04d", $start_time701[$i701][1]).'00 +0800'."\" stop=\"".$dt2.sprintf("%04d", $start_time701[$i701+1][1]).'00 +0800'."\" channel=\"喀秋莎\">\n<title lang=\"zh\">".$title701[$i701][1]."</title>\n<desc lang=\"zh\">".$intention701[$i701][1]."</desc>\n</programme>\n";
                                                        }
                                                        }
   if ($start_time701[$i701+1][1]>=1000){ 

 if ($start_time701[$i701][1]<1000){
$chn.="<programme start=\"".$dt2.sprintf("%04d", $start_time701[$i701][1]).'00 +0800'."\" stop=\"".$dt2.sprintf("%04d", $start_time701[$i701+1][1]).'00 +0800'."\" channel=\"喀秋莎\">\n<title lang=\"zh\">".$title701[$i701][1]."</title>\n<desc lang=\"zh\">".$intention701[$i701][1]."</desc>\n</programme>\n";


                                                     } 
if ($start_time701[$i701][1]>=1000){
       if ($start_time701[$i701][1]<=2400){
                              if ($start_time701[$i701][1]<$start_time701[$i701+1][1]){   
                         

$chn.="<programme start=\"".$dt2.sprintf("%04d", $start_time701[$i701][1]).'00 +0800'."\" stop=\"".$dt2.sprintf("%04d", $start_time701[$i701+1][1]).'00 +0800'."\" channel=\"喀秋莎\">\n<title lang=\"zh\">".$title701[$i701][1]."</title>\n<desc lang=\"zh\">".$intention701[$i701][1]."</desc>\n</programme>\n";

                                                                                                                    }


                            if ($start_time701[$i701][1]>$start_time701[$i701+1][1]){  

$chn.="<programme start=\"".$dt21.sprintf("%04d", $start_time701[$i701][1]).'00 +0800'."\" stop=\"".$dt21.sprintf("%04d", $start_time701[$i701+1][1]).'00 +0800'."\" channel=\"喀秋莎\">\n<title lang=\"zh\">".$title701[$i701][1]."</title>\n<desc lang=\"zh\">".$intention701[$i701][1]."</desc>\n</programme>\n";

                                                                                                                  }
                                                }
                                                                        }
}
}
$chn.="<programme start=\"".$dt21.sprintf("%04d", $start_time701[$tuu701-1][1]).'00 +0800'."\" stop=\"".$dt21.'062000 +0800'."\" channel=\"喀秋莎\">\n<title lang=\"zh\">".$title701[$tuu701-1][1]."</title>\n<desc lang=\"zh\">".$intention701[$i701][1]."</desc>\n</programme>\n";



$id600=600081;//起始节目编号

//$time=time();
$cid600=array(
array('tv','EBS1'),
array('tv2','EBS2'),
array('EBSU','EBS CHILD',),
array('PLUS1','EBS PLUS1'),
array('PLUS2','EBS PLUS2'),
array('EBSE','EBS EDUCATION'),
);
$nid600=sizeof($cid600);
for ($idm600= 1; $idm600 <= $nid600; $idm600++){
 $idd600=$id600+$idm600;
   $chn.="<channel id=\"".$cid600[$idm600-1][1]."\"><display-name lang=\"zh\">".$cid600[$idm600-1][1]."</display-name></channel>\n";
}
for ($idm600= 1; $idm600 <= $nid600; $idm600++){
 $idd600=$id600+$idm600;
   $url600='https://www.ebs.co.kr/schedule?channelCd='.$cid600[$idm600-1][0].'&date='.$dt1.'&onor='.$cid600[$idm600-1][0];
               //https://www.ebs.co.kr/schedule?channelCd=tv2&date=20230718&onor=tv2
$ch600=curl_init();
curl_setopt($ch600,CURLOPT_URL,$url600);
curl_setopt($ch600,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch600,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch600,CURLOPT_RETURNTRANSFER,1);
$headers600=[
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36 Edg/114.0.1823.86',
'Referer: https://www.ebs.co.kr/schedule',
//'Cookie: WHATAP=zq1p9og4vtfru; XTVID=A230717100728270943; PCID=16895596508411605420033; ONAIR_MODE=DEFAULT; SESSION=502b96e9-e81f-4235-b8db-49a23eb3d60e; ONAIR_RATING=1689661221687:257c4cda

];
curl_setopt($ch600, CURLOPT_HTTPHEADER, $headers600);
$re600=curl_exec($ch600);
curl_close($ch600);
$re600 = preg_replace('/\s(?=)/', '',$re600);
//print $re600 ;
 preg_match_all('|<pclass="date">(.*?)</p>|i',$re600,$StartTime600,PREG_SET_ORDER);//播放開始時間
 preg_match_all('|<h4>(.*?)</h4>|i',$re600,$Title600,PREG_SET_ORDER);//播放節目名稱
 preg_match_all('|<pclass="tit"><ahref="#">(.*?)</a></p>|i',$re600,$abtr600,PREG_SET_ORDER);//播放節目介紹
//print_r($StartTime600);
$tuu600=count($StartTime600);
for ( $i600=1 ; $i600<=$tuu600 ; $i600++ ) {
$chn.="<programme start=\"".$dt7.substr(str_replace('-','',str_replace(':','',$StartTime600[$i600-1][1])),0,4).substr(str_replace('-','',str_replace(':','',$StartTime600[$i600-1][1])),-4,4).'00 +0900'."\" stop=\"".$dt7.substr(str_replace('-','',str_replace(':','',$StartTime600[$i600-1][1])),0,4).substr(str_replace('-','',str_replace(':','',$StartTime600[$i600-1][1])),-4,4).'00 +0900'."\" channel=\"".$cid600[$idm600-1][1]."\">\n<title lang=\"zh\">".str_replace('#','',$Title600[$i600-1][1])."</title>\n<desc lang=\"zh\">".$abtr600[$i600-1][1]."</desc>\n</programme>\n";
}

 $url601='https://www.ebs.co.kr/schedule?channelCd='.$cid600[$idm600-1][0].'&onor='.$cid600[$idm600-1][0].'&date='.$dt2;
$ch601=curl_init();
curl_setopt($ch601,CURLOPT_URL,$url601);
curl_setopt($ch601,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch601,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch601,CURLOPT_RETURNTRANSFER,1);
$headers601=[
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36 Edg/114.0.1823.86',
'Referer: https://www.ebs.co.kr/schedule',
//'Cookie: WHATAP=zq1p9og4vtfru; XTVID=A230717100728270943; PCID=16895596508411605420033; ONAIR_MODE=DEFAULT; SESSION=502b96e9-e81f-4235-b8db-49a23eb3d60e; ONAIR_RATING=1689661221687:257c4cda
];
curl_setopt($ch601, CURLOPT_HTTPHEADER, $headers600);

$re601=curl_exec($ch601);
curl_close($ch601);
$re601 = preg_replace('/\s(?=)/', '',$re601);
 preg_match_all('|<pclass="date">(.*?)</p>|i',$re601,$StartTime601,PREG_SET_ORDER);//播放開始時間
 preg_match_all('|<h4>(.*?)</h4>|i',$re601,$Title601,PREG_SET_ORDER);//播放節目名稱
 preg_match_all('|<pclass="tit"><ahref="#">(.*?)</a></p>|i',$re601,$abtr601,PREG_SET_ORDER);//播放節目介紹
$tuu601=count($StartTime601);
for ( $i601=1 ; $i601<=$tuu601 ; $i601++ ) {
$chn.="<programme start=\"".$dt7.substr(str_replace('-','',str_replace(':','',$StartTime601[$i601-1][1])),0,4).substr(str_replace('-','',str_replace(':','',$StartTime601[$i601-1][1])),-4,4).'00 +0700'."\" stop=\"".$dt7.substr(str_replace('-','',str_replace(':','',$StartTime601[$i601-1][1])),0,4).substr(str_replace('-','',str_replace(':','',$StartTime601[$i601-1][1])),-4,4).'00 +0700'."\" channel=\"".$cid600[$idm600-1][1]."\">\n<title lang=\"zh\">".str_replace('#','',$Title600[$i600-1][1])."</title>\n<desc lang=\"zh\">".$abtr601[$i601-1][1]."</desc>\n</programme>\n";
}

}



/*
//2蓮花衛視
$chn.="<channel id=\"蓮花衛視\"><display-name lang=\"zh\">蓮花衛視</display-name></channel>\n";
$data2='d='.strtotime('today').'';
$data222='d='.strtotime('tomorrow').'';

$url2='http://www.macaulotustv.cc/index.php/index/getdetail.html';
$ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL, $url2);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);

curl_setopt ( $ch2, CURLOPT_POST, 1 );
curl_setopt ( $ch2, CURLOPT_POSTFIELDS, $data2 );
//curl_setopt($ch2, CURLOPT_COOKIE, $cookie2);
$rk2 = curl_exec ( $ch2 );
curl_close ( $ch2 );
$re2 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $rk2);// 適合php7
//$re2= preg_replace_callback("#\\\u([0-9a-f]{4})#", 'match_string', $rk2);
$re2=str_replace('\t','',$re2);
$re2=str_replace('"','',$re2);
$re2=str_replace('\/','',$re2);



//print $re2;
preg_match_all('|<em>(.*?)<em>|i',$re2,$um2,PREG_SET_ORDER);//播放時間
preg_match_all('|<span>(.*?)<span>|i',$re2,$un2,PREG_SET_ORDER);//播放節目
$trm2=count($um2);
//print_r($um2);
//print_r($un2);
  for ($k2 =0; $k2<=$trm2-2;$k2++) {

        $chn.="<programme start=\"".$dt1.str_replace(':','',$um2[$k2][1]).'00 +0800'."\" stop=\"".$dt1.str_replace(':','',$um2[$k2+1][1]).'00 +0800'."\" channel=\"蓮花衛視\">\n<title lang=\"zh\">".$un2[$k2][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                                   }


$ch222 = curl_init();
    curl_setopt($ch222, CURLOPT_URL, $url2);
    curl_setopt($ch222, CURLOPT_RETURNTRANSFER, 1);

curl_setopt ( $ch222, CURLOPT_POST, 1 );
curl_setopt ( $ch222, CURLOPT_POSTFIELDS, $data222 );
//curl_setopt($ch222, CURLOPT_COOKIE, $cookie2);
$rk222 = curl_exec ( $ch222 );
curl_close ( $ch222 );
$re222 = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $rk222);// 適合php7

//$re222= preg_replace_callback("#\\\u([0-9a-f]{4})#", 'match_string', $rk222);
$re222=str_replace('\t','',$re222);
$re222=str_replace('"','',$re222);
$re222=str_replace('\/','',$re222);


//print $re2;
preg_match_all('|<em>(.*?)<em>|i',$re222,$um222,PREG_SET_ORDER);//播放時間
preg_match_all('|<span>(.*?)<span>|i',$re222,$un222,PREG_SET_ORDER);//播放節目
$trm222=count($um222);
//print_r($um2);
//print_r($un2);
$chn.="<programme start=\"".$dt1.str_replace(':','',$um2[$trm2-1][1]).'00 +0800'."\" stop=\"".$dt2.str_replace(':','',$um222[0][1]).'00 +0800'."\" channel=\"蓮花衛視\">\n<title lang=\"zh\">".$un2[ $trm2-1][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";


  for ($k222 =0; $k222<=$trm222-2;$k222++) {

        $chn.="<programme start=\"".$dt2.str_replace(':','',$um222[$k222][1]).'00 +0800'."\" stop=\"".$dt2.str_replace(':','',$um222[$k222+1][1]).'00 +0800'."\" channel=\"蓮花衛視\">\n<title lang=\"zh\">".$un222[$k222][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";
                                   }
$chn.="<programme start=\"".$dt2.str_replace(':','',$um222[$trm222-1][1]).'00 +0800'."\" stop=\"".$dt2.'240000 +0800'."\" channel=\"蓮花衛視\">\n<title lang=\"zh\">".$un222[ $trm222-1][1]."</title>\n<desc lang=\"zh\"> </desc>\n</programme>\n";





*/

 $chn.="</tv>\n";
//print  $chn;

file_put_contents($fp, $chn);

 /*
//创建压缩版本
$fn = gzopen ($fp.'.gz', 'w9');
gzwrite($fn, file_get_contents($fp));
gzclose($fn);

*/

?>
