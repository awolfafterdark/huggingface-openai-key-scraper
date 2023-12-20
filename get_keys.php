<?php
set_time_limit(60);
$keys = [];
$pattern = '/(sk-\w)<\/span>([a-zA-Z0-9]{47})/';
$char = explode('--char=', $argv[1])[1];
$index = explode('--index=', $argv[2])[1];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://huggingface.co/search/full-text?q=sk-$char&limit=100&skip=$index");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
preg_match_all($pattern, $output, $keys);

foreach($keys[0] as $key){
    $key = str_replace('</span>', '', $key);
    $fd = fopen('keys.txt', 'a+');
    fwrite($fd, $key . PHP_EOL);
    fclose($fd);
}
?>