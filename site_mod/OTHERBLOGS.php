<?php

function OTHERBLOGS($e) {

/*lleo*/ if($GLOBALS['httpsite'] == "http://lleo.me") { // ������ ��� ����� �����������, ��������� ���� ������ �������
/*lleo*/ $l=file_get_contents($GLOBALS['host_log']."blogs.txt"); $m=explode("\n",$l); $s=$a='';
/*lleo*/ foreach($m as $l) { $l=h($l); if($l!='') { list($link,$admname)=explode(' ',$l,2);
/*lleo*/ 	if(substr($link,0,1)!='#') $a.= "<br><a href='$link'>".($admname!=''?$admname:$link)."</a>";
/*lleo*/ }} if($a!='') $s .= "<div style='text-align: left; font-size:11px;'><p>������ �����:<br>$a</div>";
/*lleo*/ }
/*lleo*/ return $s;

return '';

}

?>