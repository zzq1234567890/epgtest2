<?php

// 设置目标 IP 范围
$start_ip = ip2long("182.170.0.0");
$end_ip = ip2long("182.170.255.255");
$port = 8999;

// 初始化 cURL 多任务句柄
$multiHandle = curl_multi_init();
$curlHandles = [];

// 遍历 IP 范围
for ($ip = $start_ip; $ip <= $end_ip; $ip++) {
    $ipStr = long2ip($ip);

    // 创建 cURL 请求
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://$ipStr:$port");  // 通过端口检查 IP
    curl_setopt($ch, CURLOPT_NOBODY, true);  // 只检查是否可以连接，不下载内容
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);  // 设置超时时间
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // 将每个句柄添加到 cURL 多任务管理器中
    curl_multi_add_handle($multiHandle, $ch);
    $curlHandles[$ipStr] = $ch;
}

// 执行多任务请求
do {
    curl_multi_exec($multiHandle, $active);
    curl_multi_select($multiHandle);
} while ($active);

// 处理请求结果
foreach ($curlHandles as $ipStr => $ch) {
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpCode == 0) {
        echo "无法连接到 $ipStr:$port\n";
    } else {
        echo "成功连接到 $ipStr:$port\n";
    }
    // 移除每个 cURL 句柄
    curl_multi_remove_handle($multiHandle, $ch);
    curl_close($ch);
}

// 关闭 cURL 多任务句柄
curl_multi_close($multiHandle);

?>
