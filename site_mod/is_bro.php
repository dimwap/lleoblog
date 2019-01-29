<?php /* ���� ������ �� �������� � ������������� ref,ip,bro

���� �������� ������ ��������� �� ��������� ������ (������ ��� �� ������ ����� ������� ������,
����� ��� ������) - �������� ����� ����� ������������ |, ���� �� ����� - �� ����� ����� |.

{_is_bro: | _}

*/

function is_bro($e) {
	$conf=array_merge(array('ip'=>'','bro'=>'','ref'=>''),parse_e_conf($e));
	if(substr_count($conf['body'],'|')!=1) return "<font color=red>MODULE: is_bro ERROR: ����� ������ ���� ��������� |</font>";
	list($a,$b)=explode('|',$conf['body']);

	return trim(ifipbro($conf)?$a:$b);
}


function ifipbro($conf) { global $IP,$BRO,$REF;

$e=$conf['ip']; if($e!='') { // �����������: �������, ������, |
    $e=strtr($e,', ','||'); $q=(strstr($e,'|')?explode('|',$e):array($e));
    foreach($q as $i) { $i=trim($i); if($i=='') continue;
	if(substr($IP,0,strlen($i))==$i) return $i;
    }
}

$e=$conf['bro']; if($e!='') { // ����������� ������ |
    $q=(strstr($e,'|')?explode('|',$e):array($e));
    foreach($q as $i) { $i=trim($i); if($i=='') continue;
	if(strstr($GLOBALS['BRO'],$i)) return $i;
    }
}

$e=$conf['ref']; if($e!='') { // �����������: �������, ������, |
    $e=strtr($e,', ','||'); $q=(strstr($e,'|')?explode('|',$e):array($e));
    foreach($q as $i) { $i=trim($i); if($i=='') continue;
	if(strstr($GLOBALS['REF'],$i)) return $i;
    }
}
return false;
}

?>