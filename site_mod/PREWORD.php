<?php

function PREWORD($e) { global $include_sys,$IS,$REF,$article,$linksearch,$httpsite; $s='';

	$name=$IS['imgicourl']; if(substr($name,0,1)=='#') $name=false;
	$time_expirie=($article["DateTime"]<time()-86400*30);

if(!empty($linksearch)) {

	$u0=str_replace('&#34;','',$linksearch[0]);
	$u1=$GLOBALS['linksearch'][1];

if($u0!='') { // ���� ������ �� ����������
	$s.=LL('preword:poisk',array('name'=>$name,'site'=>h($u1),'string'=>h($u0),'time_expirie'=>$time_expirie));
	// $s.=($name?"����� �������, ".$name."!<p>":"")."����� ����� ".h($u1)." ������ ���� \"<b><u>".h($u0)."</u></b>\"?
	if(stristr($u0,"������")) $s.=LL('preword:download'); // ������ ������ \"������\". ����� - �������� ��������, � �� ������������.

} elseif( !strstr($REF,$httpsite) && !strstr($REF,"livejournal.com") ) { // ��� ���� ������ �� ������
	require_once $include_sys."_refferer.php";

$u=parse_url($REF); if(
strstr($u['host'],'lurkmore.to') ||
strstr($u['host'],'wikipedia.org')
) {
    $t=explode('.',$u['host']); $t=$t[sizeof($t)-2]; // ��������� wikipedai �� www.ru.wikipedia.org

    $s.=LL('preword:read',array('name'=>$name,
'site'=>$t, //substr($u['host'],0,strpos($u['host'],'.')),
'string'=>str_replace('_',' ',h(
trim(maybelink(urldecode($u['path']))," \r\t\n/\\")
)
),
'time_expirie'=>$time_expirie));
} else $s.=LL('preword:open',array('name'=>$name,'site'=>h(maybelink(urldecode($REF))),'time_expirie'=>$time_expirie));
	// �� ������ c <font color=green>".h($fromlink)."</font> �� ������� ������ �������� �� ����� �����.";
	}
} elseif($name!='') $s.=LL('preword:opoznan',$name) // ����������, ������� ".$name."!
.($IS['loginlevel']<3?" �� ����� ����������, ���� �� �� ���������."

.($GLOBALS['IS']['loginlevel']==2&&$GLOBALS['IS']['login']!=''&&$GLOBALS['IS']['password']!=''&&$GLOBALS['IS']['mailconfirm']==0?
" <font color=green>������, ����� ���������: �������� ����������� �����. ���������� ������ ���� <a href='#' alt=\"��, ��� ���� ��������, � �� ������ '�����' ���� � �� �� ��������\" onclick=\"majax('login.php',{a:'getinfo'})\">��������</a> � ��������� ��� email.</font>":'')

:"");

if($_GET['search']!='') $s.=LL('preword:search',array('search'=>h($_GET['search']),'normal'=>$GLOBALS['mypage']));
// �������� ���������� � ���������� ���� \"<span class=search>".h($_GET['search'])."</span>\", <a href='".$GLOBALS['mypage']."'>������������� � ���������� �����</a>";

return LL('preword',$s); // '"<div class='preword'>$s</div>";
}

?>