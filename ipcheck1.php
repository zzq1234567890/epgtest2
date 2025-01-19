<?php
ini_set("max_execution_time", "333333000000");
ini_set('date.timezone', 'Asia/Shanghai');
header('Content-Type: text/plain; charset=UTF-8');

// 设置并行任务数量
$max_threads = 100;

// IP范围
$start_ip = "18.170.0.0";
$end_ip = "18.170.255.255";
$port = 2390;

// 生成IP地址范围
function ipRange($start_ip, $end_ip) {
    $start = ip2long($start_ip);
    $end = ip2long($end_ip);
    $ips = [];
    for ($i = $start; $i <= $end; $i++) {
        $ips[] = long2ip($i);
    }
    return $ips;
}

// 通过ping检查IP地址是否通畅
function pingIP($ip) {
    global $port;
    $command = "nc -zv -w 1 $ip $port 2>&1"; // 使用 nc 工具检查端口
    $output = shell_exec($command);
    if (strpos($output, 'succeeded') !== false) {
        return true;
    }
    return false;
}

// 记录到文件
function logIP($ip) {
    $file = fopen('ip1.txt', 'a');
    fwrite($file, $ip . PHP_EOL);
    fclose($file);
}

// 主函数
$ips = ipRange($start_ip, $end_ip);

// 使用并行执行
$command = '';
foreach ($ips as $ip) {
    $command .= 'echo "' . $ip . '" | xargs -P ' . $max_threads . ' -I {} php -r "if (file_exists(\'ipcheck1.php\')) { include \'ipcheck1.php\'; }" & ';
}

shell_exec($command);

?>
