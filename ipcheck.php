<?php
ini_set("max_execution_time", "333333000000");
ini_set('date.timezone','Asia/Shanghai');

// 生成 IP 地址范围
$startIP = "118.170.0.0";
$endIP = "118.170.255.255";
$port = 2390;
$outputFile = "ip.txt";

// 获取 IP 范围
$ips = getIPRange($startIP, $endIP);

// 初始化cURL多并发请求
$multiCurl = curl_multi_init();
$curlHandles = [];
$results = [];

// 定义用于 ping 测试的回调函数
function pingTask($ip, $port) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://$ip:$port");
    curl_setopt($ch, CURLOPT_NOBODY, true);  // 只测试连接，不下载内容
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);     // 设置超时时间为1秒
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    return $ch;
}

// 为每个 IP 地址创建 cURL 请求
foreach ($ips as $ip) {
    $ch = pingTask($ip, $port);
    curl_multi_add_handle($multiCurl, $ch);
    $curlHandles[$ip] = $ch;
}

// 执行并行请求
do {
    $status = curl_multi_exec($multiCurl, $active);
    if ($active) {
        curl_multi_select($multiCurl);
    }
} while ($active);

// 处理请求结果
foreach ($curlHandles as $ip => $ch) {
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpCode >= 200 && $httpCode < 400) {
        // 端口通畅，记录 IP
        $results[] = $ip;
    }
    curl_multi_remove_handle($multiCurl, $ch);
    curl_close($ch);
}

// 关闭cURL多请求
curl_multi_close($multiCurl);

// 将结果写入 ip.txt 文件
file_put_contents($outputFile, implode("\n", $results) . "\n");

echo "测试完成，结果已写入 '$outputFile' 文件。\n";

// 获取 IP 范围
function getIPRange($startIP, $endIP) {
    $start = ip2long($startIP);
    $end = ip2long($endIP);
    $ips = [];
    for ($i = $start; $i <= $end; $i++) {
        $ips[] = long2ip($i);
    }
    return $ips;
}
