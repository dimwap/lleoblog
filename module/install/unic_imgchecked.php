<?php

/*
        $s="DELETE FROM $tb WHERE $a $u";
// if(msq_pole($table,$pole)===false) msq("ALTER TABLE `".$table."` ADD `".$pole."` ".$s
msq_pole($tb,$pole) // ���������, ���������� �� ����� ���� � ������� $tb
 // ���������, ���������� �� ����� �������
msq_index($tb,$index) // ���������, ���������� �� ����� ������
// �������� ���� � �������	function msq_change_pole($table,$pole,$s)
// �������� ���� �������	function msq_add_pole($table,$pole,$s)
// ������� ���� �� �������	function msq_del_pole($table,$pole)
// �������� ������ � �������	function msq_add_index($table,$pole,$s)
// ������� ������ �� �������	function msq_del_index($table,$pole)
// ������� �������		function msq_add_table($table,$s)
// ������� �������		function msq_del_table($table,$text)
*/

// ��� ������� ���������� 0, ���� ��������� ���� ������ �� ��������� (����. ������ ��� �������)
// ���� - ������ ��� ����������� ������ ������� ������.
function installmod_init(){
//	if(msq_pole($GLOBALS['db_unic'],'img')===false) return false;
	if(!($i=installmod_allwork())) return false;
	return "������������� IMG (".$i.")";

// http://lleo.me/dnevnik/user/114/userpick.jpg
}

/*
function installmod_get(){ global $skip,$lim,$o;
	$pp=ms("SELECT * FROM `dnevnik_zapisi` LIMIT $skip,$lim","_a",0);
	if($pp===false or !sizeof($pp)) { $o.="<p><hr>done"; return 0; }
	return $pp;
}
*/

// ��� ������� - ���� ������ ������. ���� ������ �� ������� ������ - ������� 0,
// ����� ������� ����� �������, � ������� ���������� ������, ����� �� ������ ������� ����������.
// skip - � ���� �������� (���������� 0), allwork - ����� ���������� (�������� �����), $o - ��, ��� ������ �� �����.

function installmod_do() { global $o,$skip,$allwork,$delknopka; $starttime=time();

	$o='';

// return;

        while((time()-$starttime)<2 && $skip<$allwork) {
		$pp=ms("SELECT `img`,`id` FROM ".$GLOBALS['db_unic']." WHERE `img`!='' AND `img` NOT LIKE '".e($GLOBALS['httpsite'])."%' LIMIT 5","_a",0);

		foreach($pp as $p) { $url=trim($p['img']); $id=$p['id'];

if($url=='https://ulogin.ru/img/photo.png'
|| $url=='http://ulogin.ru/img/photo.png'
|| $url=='https://ulogin.ru//img/photo.png'
|| $url=='https://fbstatic-a.akamaihd.net/rsrc.php/v2/yo/r/UlIqmHJn-SK.gif'
|| $url=='https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xfp1/v/t1.0-1/c15.0.50.50/p50x50/10354686_10150004552801856_220367501106153455_n.jpg?oh=8ab5ec7814307370244b6b8baf61bde4&oe=5583A92F&__gda__=1434999769_e0986db92e8097f1730c10f285e0e501'
|| site_validate($url)=='') { $o.=" <span style='font-size:50px'>#</span>"; msq_update($GLOBALS['db_unic'],array('img'=>''),"WHERE `id`='".$id."'"); continue; }

		    $img=load_farimg($url,$id);
		    if($img=='') $o.="<div><font color=red>ERROR URL: <a href='".h($url)."'>".h($url)."</a></font></div>";
		    else $o.=" <a href='".h($url)."' target='_blank'><img src='".$img."'></a>";
// return $skip;
//			if($m=='' or substr($m,0,1)=='!') continue;
//			$o.="<div><img src='".h($i)."'> ".$i."</div>";
//		    if($img!='') 
msq_update($GLOBALS['db_unic'],array('img'=>e($img)),"WHERE `id`='".$id."'");
		}
//                usleep(100000);
                $skip+=5;
        }

        $o.="<p>".$skip;
        if($skip<$allwork) return $skip;

//	msq_del_pole('unic','mail_checked');

//        $delknopka=1;
        return 0;
}

// ���������� ����� ����� ����������� ������ (����. ����� ������� � ���� ��� ���������).
// ���� ������ ������������ ������� - ������� 0.
function installmod_allwork() { return intval(ms("SELECT COUNT(*) FROM ".$GLOBALS['db_unic']." WHERE `img`!='' AND `img` NOT LIKE '".e($GLOBALS['httpsite'])."%'","_l",0)); }


//<---->$img=load_farimg($img,$GLOBALS['unic']); // ��������� ���� ��������
function load_farimg($url,$unic) { // ��������� ���� ��������
    $url=site_validate($url); if(empty($url)) return '';
//    ini_set('default_socket_timeout',10); // 900 Seconds = 15 Minutes
//    $s=fileget($url);
    $s=file_get_contents($url,false,stream_context_create(array('http'=>array('timeout'=>5))));

if(empty($s)) return '';
    $img=imagecreatefromstring($s); if(!is_resource($img)) return ''; // �� ������� ���������
    $W=imagesx($img); $H=imagesy($img); $itype=2;
    $GLOBALS['foto_replace_resize']=1; require_once $GLOBALS['include_sys']."_fotolib.php";
    $imgs=obrajpeg_sam($img,150,$W,$H,$itype,''); imagedestroy($img);
    $ff="user/".$unic."/userpick.jpg";
    $f=rpath($GLOBALS['filehost'].$ff); testdir(dirname($f));
    closeimg($imgs,$f,$itype,95);
    if(!is_file($f)) return '';
    return $GLOBALS['httphost'].$ff;
}

?>