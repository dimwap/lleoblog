<?php

/*  */

function ClaudFlare_reg($acc) { // ��������� ����� �� CloudFlare
    if(!isset($GLOBALS['dyndns_clowdflare_mail']) || !isset($GLOBALS['dyndns_clowdflare_token'])) return false;
    $site=explode('/',$GLOBALS['httpsite'].'/',4); $site=$site[2]; if(empty($site)) idie("Error domain `".h($GLOBALS['httpsite'].'/')."`");
    include_once $GLOBALS['include_sys']."protocol/CloudFlare.php";

//    $otv1="record already exists";

    return // "salert(\"".njsn(nl2br(h(
	cloud_add_items($acc,$site,'CNAME',120,'true')
	."<br>"
	.cloud_add_items('www.'.$acc,$site,'CNAME',120,'true')
    // )))."\",3000);"
    ;
}

function ACC_ajax(){ if_iphash(RE('iphash'));
	$acc=RE('acc');
	if(preg_match("/[^a-z0-9\_\-]+/s",$l)) idie("� ����� ������ `".h($acc)."` ���������� �������!");
  if(($acn=ms("SELECT `acn` FROM `jur` WHERE `acc`='".e($acc)."' LIMIT 1","_l",0))!==false) {
    return "idie(\"������� ��� ����������."
.njsn($GLOBALS['IS']['login']==$acc?"<p>�������� <a href=\"javascript:majax('editor.php',{acn:".$acn.",a:'newform',hid:hid})\">����� �������</a>":'')
."\");"
    .ClaudFlare_reg($acc);
    }

  if(($u=ms("SELECT `id` FROM ".$GLOBALS['db_unic']." WHERE `login`='".e($acc)."'","_l",0))===false) idie("User `".h($acc)."` not found!");

    $o='';

    $max=1*ms("SELECT MAX(`acn`) AS `acn` FROM `jur`",'_l')+1; // ����� ����������

  msq_add('jur',array('acc'=>e($acc),'unic'=>$u,'acn'=>$max));
  $acn=ms("SELECT `acn` FROM `jur` WHERE `acc`='".e($acc)."'","_l",0);

    $o.=ClaudFlare_reg($acc);
    return $o."
idie(\"User: `".h($acc)."` unic=$u <font color=green>CREATED</font> with id=$acn"
."<p>�������� <a href=\"javascript:majax('editor.php',{acn:".$acn.",a:'newform',hid:hid})\">����� �������</a>"
."\");";

}

function ACC($e) { global $admin,$acc,$acn,$ADM,$IS,$httphost;
$conf=array_merge(array(
'mode'=>"admin",
'sort'=>'',
'day'=>30,
'all'=>0,
'visible'=>1,
'maketwo'=>0,
'template'=>"<br><a href='{acc_link}'>{acc}</a> (<a href='{acc_link}contents'>{count}</a>)"
),parse_e_conf($e));

if($conf['mode']=='list') { // AND z.DateDate>".(time()-$conf['day']*86400) //,z.COUNT(*) as `count`
	$pp=ms("SELECT `acc`,`acn` FROM `jur`".($conf['sort']!=''?" ORDER BY `".e($conf['sort'])."`":''),"_a",5000);
	$x=strstr($conf['template'],'{count}');
	$o=''; foreach($pp as $p) {
// die(WHERE()."   ### $acn");
		$count=($x?ms("SELECT COUNT(*) FROM `dnevnik_zapisi` WHERE `acn`='".$p['acn']."'"
		.($conf['day']?" AND `DateDate`>".(time()-$conf['day']*86400):'')
		.($conf['visible']?" AND `Access`='all'":'')
		,"_l"):0);
		if($count || $conf['all']) $o.=mper($conf['template'],array('acc'=>h($p['acc']),'acc_link'=>acc_link($p['acc']),'count'=>$count));
	}
	return $o;
}

if($conf['mode']=='count') { return ms("SELECT COUNT(*) FROM `jur`","_l"); }

// if($conf['mode']=='admin' && !$admin) { if(empty($acc)) return "Admin only!"; /* redirect($httphost.'acc'); */ }

// return "admin: ".intval($admin);

	// ����� ����� ������� �������:
//	if($admin&&!empty($acc)) return "<span class='ll' onclick=\"if(confirm('create?'))majax('module.php',{mod:'ACC',acc:'$acc',iphash:'".iphash()."'});\">Create '".h($acc)."'?</span>";

	// ����� ����� ������� �������:
	if(empty($IS['login'])) return "� ��� �� ��������� ���� `login` � <span class='ll' onclick=\"majax('login.php',{a:'getinfo'})\">��������</span>";
	if(empty($IS['password'])&&empty($IS['openid'])) return "� ��� �� ��������� ���� `password` � <a href=\"javascript:majax('login.php',{action:'openid_form'})\">��������</a>. ��� �� ���������� ������� ���� �������, ����� ����������� �������� ������?";
	$l=h($IS['login']);

	if(preg_match("/[^a-z0-9\_\-]+/s",$l)) return "� ����� ������ `$l` ���������� ������� (��������� ��������: a-z0-9_-). ������� ����� ����������� ���. ������ ������������� � ������� ����� ������� ;)";

	if($acc!='') return "���� ������ �������� �� ������: <a href='".$GLOBALS['httphost']."acc'>".$GLOBALS['httphost']."acc</a>";
//	    return "������ ������� ���� ������� <b>$l</b>? <input type='button' value='Create ".$l."' onclick=\"if(confirm('create?'))majax('module.php',{mod:'ACC',acc:'$l',iphash:'".iphash()."'});\">";
// �� ����� ��� �������, ����� �� ������ <a href='".acc_link($l)."acc'>".acc_link($l)."acc</a>";

// return 'D';

	if(0!=ms("SELECT COUNT(*) FROM `jur` WHERE `acc`='".e($acc)."'","_l",0))
	    return "������� `$acc` ��� ������, ���������� ���������� � ���: <a href='".h(acc_link($l))."acctest'>".h(acc_link($l))."acctest</a>";

	if($conf['maketwo']==0 && 0!=ms("SELECT COUNT(*) FROM `jur` WHERE `acc`='".e($l)."'","_l",0)) {
	    return ClaudFlare_reg($l)."<p>Account <b>$l</b> already created, see more: <a href='".h(acc_link($l))."acctest'>".h(acc_link($l))."acctest</a>";
	}
//	.($acc!=''?"<p>�������� ���������� ������ ����� �������, ����� ��������� ��������� ������������� �������� ��� ������ ������������."
//	."<p>���� ������ ������� ���� ������� <b>$l</b>, �� �������� ����� � ������ �������� ������ ����������� ��� - ����� ������ ������������� � ������� ����� ��������.":'');

/*
	if($conf['maketwo']==0 && $l!=$acc) return "� ����� <span class=ll onclick=\"majax('login.php',{a:getinfo})\">��������</span> �������� ����� <b>$l</b>, � �� ������-�� ��������� ������� ������� <b>$acc</b>. �������� ���������� ������ ����� �������, ����� ��������� ��������� ������������� �������� ��� ������ ������������."
."<p>�� �����������, ���� �� ������:"
."<p>1. ���� ������ ������� ���� ������� <b>$acc</b>, �� ����� ��� �������, ����� �� ������ <a href='".acc_link($acc)."acc'>".acc_link($acc)."acc</a>"
."<p>2. ���� ������ ������� ���� ������� <b>$l</b>, �� �������� ����� � ������ �������� ������ ����������� ��� - ����� ������ ������������� � ������� ����� �������� ;)";
*/

	return "��, ����� ����� ������� ������ ��� ������ �������� <b>$l</b>:<p>
<center><input type='button' value='������� ������ ".$l."' onclick=\"if(confirm('create?'))majax('module.php',{mod:'ACC',acc:'$l',iphash:'".iphash()."'});\"></center>";
}

?>