<?php /* ������ REDIR

�������
91.224.183.*
95.173.128.*
*/

function REDIR($e) {

//return '';

$conf=array_merge(array(
	'ip'=>'',
	'url'=>'/blocked.htm?redirect_from={url}',
	'error'=>301 // �������������
),parse_e_conf($e));

if($conf['ip']!='') {
	$q=(strstr($conf['ip'],' ')?explode(' ',$conf['ip']):array($conf['ip']));
	$k=1; foreach($q as $i) { $i=trim($i); if($i=='') continue;
		if(substr($GLOBALS['IP'],0,strlen($i))==$i){$k=0;break;}
	} if($k) return;
}

/*
        300 Multiple Choices (��������� �������).
        301 Moved Permanently (���������� ������������).
        302 Found (�������).
        303 See Other (�������� ������).
        304 Not Modified (�� ����������).
        305 Use Proxy (������������ ������).
        306 (���������������).
        307 Temporary Redirect (��������� ���������������).
*/

// return str_replace('{url}',$GLOBALS['mypage'],$conf['url']).'#'.$conf['error'];
redirect(str_replace('{url}',$GLOBALS['mypage'],$conf['url']),$conf['error']);
}

?>