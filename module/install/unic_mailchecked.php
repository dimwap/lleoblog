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
	if(msq_pole('unic','mail_checked')===false) return false;
	return "������������� mail_checked (".installmod_allwork().")";
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

        while((time()-$starttime)<2 && $skip<$allwork) {
		$pp=ms("SELECT `mail`,`id` FROM `unic` WHERE `mail`!='' AND `mail_checked`='1' AND `mail` NOT LIKE '!%' LIMIT 50","_a",0);

		foreach($pp as $p) { $m=$p['mail'];
			if($m=='' or substr($m,0,1)=='!') continue;
			$o.=" ".$m;
			msq_update('unic',array('mail'=>e('!'.$m),'mail_checked'=>0),"WHERE `id`='".$p['id']."'");
		}
                usleep(100000);
                $skip+=50;
        }

        $o.=" ".$skip;
        if($skip<$allwork) return $skip;

	msq_del_pole('unic','mail_checked');

        $delknopka=1;
        return 0;
}

// ���������� ����� ����� ����������� ������ (����. ����� ������� � ���� ��� ���������).
// ���� ������ ������������ ������� - ������� 0.
function installmod_allwork() { return ms("SELECT COUNT(*) FROM `unic` WHERE `mail`!='' AND `mail_checked`='1' AND `mail` NOT LIKE '!%'","_l",0); }

?>