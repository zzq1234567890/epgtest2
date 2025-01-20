<?php

// 定义IP范围
$startIp = ip2long('118.170.0.0');
$endIp = ip2long('118.170.255.255');
$port = 2390;
$timeout = 1;
$maxThreads = 100;
$results = [];

$multiHandle = curl_multi_init();
$handles = [];
$activeThreads = 0;
$found = false;

// 创建并行线程池
function createThreadPool($startIp, $endIp, $port, $timeout, $maxThreads, &$results, &$multiHandle, &$handles, &$activeThreads, &$found) {
    for ($ip = $startIp; $ip <= $endIp && !$found; $ip++) {
        $ipAddress = long2ip($ip);
        $url = "http://$ipAddress:$port";
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        curl_multi_add_handle($multiHandle, $ch);
        $handles[$ipAddress] = $ch;
        $activeThreads++;
        
        if ($activeThreads >= $maxThreads) {
            manageThreads($multiHandle, $handles, $results, $activeThreads, $found);
        }
    }
    while ($activeThreads > 0 && !$found) {
        manageThreads($multiHandle, $handles, $results, $activeThreads, $found);
    }
}

// 管理并行线程
function manageThreads(&$multiHandle, &$handles, &$results, &$activeThreads, &$found) {
    do {
        $status = curl_multi_exec($multiHandle, $running);
        if ($running) {
            curl_multi_select($multiHandle);
        }
    } while ($running && $status == CURLM_OK);
    
    foreach ($handles as $ipAddress => $handle) {
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if ($httpCode == 200) {
            $results[] = $ipAddress;
            $found = true;
            break;
        }
        curl_multi_remove_handle($multiHandle, $handle);
        curl_close($handle);
        unset($handles[$ipAddress]);
        $activeThreads--;
    }
    if ($found) {
        foreach ($handles as $handle) {
            curl_multi_remove_handle($multiHandle, $handle);
            curl_close($handle);
        }
    }
}

// 开始扫描
createThreadPool($startIp, $endIp, $port, $timeout, $maxThreads, $results, $multiHandle, $handles, $activeThreads, $found);

curl_multi_close($multiHandle);

// 将结果写入文件
if (!empty($results)) {
    file_put_contents('ip4.txt', implode(PHP_EOL, $results));
}

echo "完成。找到 ".count($results)." 个通畅的 IP 地址，端口 $port.";

?>
