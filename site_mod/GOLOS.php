<?php // ������ ����������� v.2
/*

���� ������ ���� �� ��������!!!
� ������������ � ���� ���� ���!!!!


��� ����� ��� ����������� � �������:

{_GOLOS2: ����������_���_������_�����������:

1. ��� �� �������, ��� ���?
-- ��� �����������
-- ��� ��������, ��� �������� ������ �����������
-- ��� ��������, ��� �������� ������, � ����� ����� ����������

2. ��� �� ������ ���� ������?
-- ����� ��������
-- �������� �� ������� RSS
-- �� ���, �����, ��������...

3. �������� �� ��� ������ ��������?
-- ��-����� ���� ������������: ��� ����� ���������� � ���� �������
-- � ������ � ���� ������ "���������� ������"

_}
*/

function GOLOS_ajax() { $golosnum=RE('golosnum');

	dier(nl2br(h(print_r($_REQUEST,1))));

	// ��������� ���
	return "alert('num:".$golosnum."'); zabil('golosovalka_".$golosnum."','###'); idie('".(1)."');";
}


function GOLOS($e) {
$conf=array_merge(array(
'number'=>false,
'output'=>"{num}<br><img src='{www_design}img/gol.gif' width='{kx}' style='height:14px !important' height='14'><br><span class=br><b>{kpx}%</b> ({x})</span><br>",
'input'=>"<div><label><input name='{id}' type='radio' value='{i}'> {text}</label></div>",
'template'=>"<p><b>{text}</b><br><ul>{answer}</ul>"
),parse_e_conf($e));

	$vopr=golos_chit($conf['body']);

	$golosnum=0;

//	$golosoval=ms("SELECT COUNT(*) FROM `$GOLOS_db_golosa` AS a, `$GOLOS_db_result` AS r WHERE a.unic='".$unic."' AND a.golosid=r.golosid AND r.golosname='".e($golosname)."'","_l");
//	if($admin) $golosoval=false;

/*
	// ����� ����������
	if($golosoval) {
		$s=ms("SELECT `text`,`n` FROM `$GOLOS_db_result` WHERE golosname='".e($golosname)."'",'_1');
		$go=unserialize($s['text']); $nn=$s['n'];

		$k=($nn?(640/$nn):0); // ����������� ����������� array_sum($go[$n])
		$kp=($nn?(100/$nn):0);
	} else {
		$nn=intval(ms("SELECT `n` FROM `$GOLOS_db_result` WHERE golosname='".e($golosname)."'",'_l'));
	}
*/
//	if($admin) $s.=nl2br(golos_recalculate($golosname)).'<p>';

//	$s="<p>��� �������������: <b>".$nn."</b>";

	$n=0; $s=''; foreach($vopr as $vop=>$var) { $n++; $c='';
		foreach($var as $i=>$va) {
			if($golosoval) { // ���� ���������
				$x=$go[$n][$i+1];
				$c.=mper($conf['output'],array(
//					'www_design'=>$GLOBALS['www_design'],
					'num'=>$va,
					'x'=>intval($x),
					'kx'=>floor($k*$x),
					'kpx'=>floor($kp*$x)
				));
			} else { // ���� �� ���������
				$c.=mper($conf['input'],array(
//					'www_design'=>$GLOBALS['www_design'],
					'text'=>$va,
					'id'=>"gls_".$n, //."_".($i),
					'i'=>$i,
				));
			}
		}
	$s.=mper($conf['template'],array(
//			'www_design'=>$GLOBALS['www_design'],
			'answer'=>$c,
			'text'=>$vop,
			'i'=>$i
		));
	}

	if(!$golosoval) $s="<form onsubmit=\"return send_this_form(this,'module.php',{mod:'GOLOS',golosnum:'$golosnum'})\">"
."<input type='hidden' name='hash' value='______md5(acn,n)____'>"
.$s
."<br><input type='submit' value='����������'>"
."</form>";

	$s="<center><table width=90% cellspacing=20><td align=left>".$s."</td></table></center>";

/*
	if($golosoval) { $s .= "<p><center><b>�������, ��� �������������!</b></center>"; // ���� ���������
	} else { // ���� �� ���������

<input type=hidden name=golosname value='".$golosname."'>
<input type=hidden name=golos_return value='".$mypage."'>
<input type=hidden name=vopr value='".sizeof($vopr)."'>
".$s."
".$ca."

}
*/

//$article['Body'] = preg_replace("/\{golosovalka[^\}]*\}/si",$s,$article['Body']);
return "<div id='golosovalka_".$golosnum."'>$s</div>";
}


//=======================================================================================
//=======================================================================================
//=======================================================================================
//=======================================================================================
//=======================================================================================
//=======================================================================================
//=======================================================================================
//=======================================================================================

function post_code() {
	$golosname=$_POST['golosname'];
	$vopr=intval($_POST['vopr']);
	if(!$vopr) idie('error n');


	$gol=array(); for($i=1;$i<=$vopr;$i++) { $g=intval($_POST[$golosname.'_'.$i]);
		if(!$g) // dier($_POST);
idie("������ ��������� ������ ������������.<br>���������� ��������� � �������� �����.");
		else $gol[$i]=$g;
	}

	if(!golos_update($golosname,$gol)) idie("������: �� ��� ����������!"); // �������� �����, ���� �� ��������� �����
	golos_calculate($golosname,$gol); // ����������� ���������
	redirect($_POST['golos_return']);
}

//=======================================================================================
//=======================================================================================
//=======================================================================================
//=======================================================================================
//=======================================================================================
//=======================================================================================
//=======================================================================================
//=======================================================================================

// �������� � ���� ������� ����� �����
function golos_update($name,$gol) { global $GOLOS_db_golosa,$GOLOS_db_result,$unic;
	$golosid=ms("SELECT `golosid` FROM `$GOLOS_db_result` WHERE `golosname`='".e($name)."'","_l");
	if(!$golosid) { msq_add($GOLOS_db_result, array('golosname'=>e($name)) ); $golosid=msq_id(); } // ���� �� ���� - �������
	return msq_add($GOLOS_db_golosa, array( 'unic'=>$unic,'golosid'=>$golosid,'value'=>e(serialize($gol)) ) );
}

// ������ ����� ����� (����������� ����������)
function golos_calculate($name,$gol) { global $GOLOS_db_result;
	$g=ms("SELECT `n`,`text` FROM `$GOLOS_db_result` WHERE `golosname`='".e($name)."'","_1",0); if($g===false) $g=array();
	$go=unserialize($g['text']); if($go===false) $go=array();
	foreach($gol as $i=>$j) $go[$i][$j]++; // �������� ����� ��������� ��������
	msq_add_update($GOLOS_db_result,array( 'golosname'=>e($name),'n'=>(intval($g['n'])+1),'text'=>e(serialize($go)) ),'name');
}


// �������� ����������� �������� (��� ������ ������)
function golos_recalculate($name) { global $GOLOS_db_golosa,$GOLOS_db_result;

	$summ=0; // ����� �����
	$go=array(); // ��� ����������� ������ ��������
	$mes=''; // ������ ��� ��������� ���������

	$golosid=ms("SELECT `golosid` FROM `$GOLOS_db_result` WHERE `golosname`='".e($name)."'","_l"); // ���� �� ����� �����������?
	if(!$golosid) { msq_add($GOLOS_db_result, array('golosname'=>e($name)) ); $golosid=msq_id(); } // ���� ��� - �������

	$limit=1000; // ����������� �������� �� 1000 ����
	$start=0; // ������� � ������ 0
	$stop=0; while($stop++<1000) { // ����������� �� ��������� - 1000 ��� �� 1000 ������� (1 ��� ������������?)
		$pp=ms("SELECT `value` FROM `$GOLOS_db_golosa` WHERE `golosid`='".e($golosid)."' LIMIT $start,$limit","_a",0);
		if(!sizeof($pp)) break;
		$start+=$limit;
		foreach($pp as $p) {
			$g=unserialize($p['value']); if($g===false) { $mes.=' error 1'; break; } // ���� ������ ������������ - ������
			foreach($g as $i=>$v) $go[$i][$v]++; // ������ ����� �������� �� ������� ������
			$summ++; // ������� ����� +1
		}
	}


	$mmes=''; // ������ ��� �������������� ��������� ���������

	$p=ms("SELECT `n`,`text` FROM `$GOLOS_db_result` WHERE `golosname`='".e($name)."'",'_1',0);
	$go0=unserialize($p['text']); // ���������� �������� ��������
	$summ0=$p['n']; // ����� �������� ��������

	if($summ!=$summ0) $mmes.="\n���! �� ������� ����� ������������: ".$summ0.", � ���������: ".$summ."\n";
	if(sizeof($go0)!=sizeof($go)) $mmes.="\n���! �� ����� �����: � ����: ".sizeof($go0).", � ���������: ".sizeof($go)."\n";

	foreach($go as $i=>$g) {
	   if(sizeof($go0[$i])!=sizeof($g)) $mmes.="\n $i) �� ������� ������: ".sizeof($go0[$i]).", � ����: ".sizeof($g)."\n";
	   foreach($g as $k=>$l) if($go0[$i][$k]!=$l) $mmes.="\n $i($k): ".$go0[$i][$k]." != $l";
	}

	if($mmes=='') $mes .= "<font color=green>��������: ��� �������</font>";
	else { $mes.=$mmes;
		// ������������:
		$mes .= "<p><font color=red>UPDATE: "
		.msq_add_update($GOLOS_db_result,array( 'golosname'=>e($name),'n'=>e($summ),'text'=>e(serialize($go)) ),'golosname')
		."</font>";
	}
	
return $mes;
}


function golos_chit($s) { // ���������� �����������
	preg_match_all("/#+\n*([^#]+)/si","#".str_replace("\n\n","#",$s),$m);
	$v=array(); foreach($m[1] as $k=>$l) {
		$z=trim( preg_replace("/^([^\n]+)\n.*$/s","$1",$l) );
		preg_match_all("/\n+[\s\-".chr(151)."]+([^\n]+)/s",trim($l),$p);
		if($z && sizeof($p[1])) $v[$z]=$p[1];
	}
	return $v;
}

?>