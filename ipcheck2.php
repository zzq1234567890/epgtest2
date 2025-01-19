<?php

// 设置IP地址范围
$start_ip = "118.170.0.0";
$end_ip = "118.170.255.255";
$port = 2390;
$output_file = "ip.txt";

// 将IP地址转换为数字
function ipToLongCustom($ip) {
    return sprintf("%u", ip2long($ip));
}

// 将数字转换为IP地址
function longToIpCustom($long) {
    return long2ip($long);
}

// 多并发cURL请求
function testIpPort($ips, $port, $output_file) {
    // 初始化 cURL multi
    $multi_curl = curl_multi_init();
    $curl_handles = [];
    $results = [];
    
    // 为每个IP地址创建cURL请求
    foreach ($ips as $ip) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://$ip:$port");
        curl_setopt($ch, CURLOPT_NOBODY, true);  // 只测试连接，不下载内容
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);    // 设置超时时间
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // 将cURL句柄加入multi_curl
        curl_multi_add_handle($multi_curl, $ch);
        $curl_handles[$ip] = $ch;
    }
    
    // 执行cURL请求
    do {
        $status = curl_multi_exec($multi_curl, $active);
        if ($active) {
            curl_multi_select($multi_curl);
        }
    } while ($active);
    
    // 处理结果
    foreach ($curl_handles as $ip => $ch) {
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($http_code >= 200 && $http_code < 400) {
            // 端口通畅，记录到文件
            file_put_contents($output_file, "IP: $ip - Port $port is open\n", FILE_APPEND);
        }
        
        // 移除cURL句柄
        curl_multi_remove_handle($multi_curl, $ch);
        curl_close($ch);
    }
    
    // 关闭multi_curl
    curl_multi_close($multi_curl);
}

// 将开始和结束IP地址转换为数字
$start_num = ipToLongCustom($start_ip);
$end_num = ipToLongCustom($end_ip);

// 分割IP范围，创建任务
$ips = [];
for ($ip_num = $start_num; $ip_num <= $end_num; $ip_num++) {
    $ips[] = longToIpCustom($ip_num);
    
    // 如果有100个IP，执行一次并发测试
    if (count($ips) == 100 || $ip_num == $end_num) {
        testIpPort($ips, $port, $output_file);
        $ips = [];  // 清空已处理的IP数组
    }
}

echo "扫描完成，结果已保存到 $output_file 文件中。\n";

?>
