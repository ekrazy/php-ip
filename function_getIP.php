<?php

function getIpRange(  $cidr) {

    list($ip, $mask) = explode('/', $cidr);

    $maskBinStr =str_repeat("1", $mask ) . str_repeat("0", 32-$mask );      //net mask binary string
    $inverseMaskBinStr = str_repeat("0", $mask ) . str_repeat("1",  32-$mask ); //inverse mask

    $ipLong = ip2long( $ip );
    $ipMaskLong = bindec( $maskBinStr );
    $inverseIpMaskLong = bindec( $inverseMaskBinStr );
    $netWork = $ipLong & $ipMaskLong; 

    $start = $netWork+1;//ignore network ID(eg: 192.168.1.0)

    $end = ($netWork | $inverseIpMaskLong) -1 ; //ignore brocast IP(eg: 192.168.1.255)
    return array('firstIP' => $start, 'lastIP' => $end );
}

function getEachIpInRange ( $cidr) {
    $ips = array();
    $range = getIpRange($cidr);
    for ($ip = $range['firstIP']; $ip <= $range['lastIP']; $ip++) {
        $ips[] = long2ip($ip);
    }
    return $ips;
}

$cidr = '172.169.0.0/16'; // max. 30 ips
$iprange = getEachIpInRange ( $cidr);

$i =1;
foreach($iprange as $ip){
    echo $i.". ".$ip;
    echo "<br>";
    $i++;
}
/* output: 
Array                                                                 
(                                                                     
    [0] => 172.16.0.1                                                 
    [1] => 172.16.0.2
    [2] => 172.16.0.3
    ...
    [27] => 172.16.0.28                                               
    [28] => 172.16.0.29                                               
    [29] => 172.16.0.30
) 
*/
?>
