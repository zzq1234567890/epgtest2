<?php
ini_set("max_execution_time", "333333000000");
ini_set('date.timezone','Asia/Shanghai');
$fp="ip.txt";//压缩版本的扩展名后加.gz
// 设置起始和结束 IP
$start_ip = '118.170.0.0';
$end_ip = '118.170.255.255';
$port = 2390; // 要检查的端口
$chn="\n";
// 将起始和结束 IP 转换为整数
function ipToInt($ip) {
    $parts = explode('.', $ip);
    return ($parts[0] * 16777216) + ($parts[1] * 65536) + ($parts[2] * 256) + $parts[3];
}

// 将整数转换为 IP
function intToIp($int) {
    return long2ip($int);
}

// 检查端口是否通
function isPortOpen($ip, $port) {
    $connection = @fsockopen($ip, $port, $errno, $errstr, 1); // 1秒超时
    if (is_resource($connection)) {
        fclose($connection);
        return true;
    } else {
        return false;
    }
}

// 获取起始和结束 IP 的整数表示
$start_int = ipToInt($start_ip);
$end_int = ipToInt($end_ip);

// 遍历 IP 范围并检查端口
for ($i = $start_int; $i <= $end_int; $i++) {
    $ip = intToIp($i);
    if (isPortOpen($ip, $port)) {
        $chn.="$ip\n";        
   
    } else {
        echo "$ip:$port is not reachable\n";
    }
}
file_put_contents($fp, $chn);
?>
