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

// 创建线程池
$runtime = new \parallel\Runtime();

// 定义用于 ping 测试的回调函数
$pingTask = function($ip, $port) {
    $connection = @fsockopen($ip, $port, $errno, $errstr, 1); // 1秒超时
    if (is_resource($connection)) {
        fclose($connection);
        return $ip;
    }
    return null;
};

// 用于收集结果的数组
$results = [];

// 启动并发任务进行 ping 测试
foreach ($ips as $ip) {
    $future = $runtime->run($pingTask, [$ip, $port]);
    $result = $future->value();
    if ($result) {
        $results[] = $result; // 记录连接成功的 IP
    }
}

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
?>

