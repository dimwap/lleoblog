<?php

include "../config.php"; include $include_sys."_autorize.php";

include_once $include_sys."/_podsveti.php"; // ��������� Diff - ������ ����, ��������, �� ��������
include_once $include_sys."/_one_pravka.php"; // ��������� ������ ������ � ����� �������

$id=RE0('id'); $a=RE('action').RE('a'); ADH();

	if($a=='1') pravka_submit($id,pravka_answer(RE('answer'))); // ������ �������
	if($a=='0') pravka_discard($id,pravka_answer(RE('answer'))); // ������ ���������

	if($a=='podrobno') pravka_showmore($id); // �������� ������
	if($a=='edit') pravka_edit($id); // edit ������ ����� ��������������
	if($a=='edit_txt') pravka_edit_txt($id); // edit-send - ������ ����������������� �����

	if($a=='edit_c') pravka_edit_c($id); // edit ������ ����� �������������� ��������
	if($a=='edit_c_txt') pravka_edit_c_txt($id); // ������� ����������������� answer

	if($a=='del') { if($GLOBALS['admin']) msq_del($GLOBALS['db_pravka'],array('id'=>$id)); pravka_otvet_e(); } // ������� ����� �� ����

	if($a=='opechatka') { // ����� �������� �� ������ �� ���������!
		$oid=RE('oid'); // ss es
		$data=RE('data'); // file#@arhive/no_humor/rosryba.htm
		$text=pravka_valitext(RE('text')); // ���� ���� � �������!
		$textnew=pravka_valitext(RE('textnew')); // ���� ���� � �������!
		pravka_priem($data,$text,$textnew);
	}

function nl2brp($s) { return str_replace(array("\n\n","\n"),array('<p>','<br>'),$s); }

	if($a=='textarea') { ADMA(1);
		$oid=RE('oid'); $o=RE('o'); $ss=RE0('ss');

		$n=RE0('n');
	        if($n>1) otprav("salert('����� ".h($o)." � ����� '".h($oid)."' ���������� ".$n."!<br>���������� �������� ����� ������� �����.',3000);");
		if($n<1) otprav('');

		$s=mpers(str_replace(array("\n","\r","\t"),'',get_sys_tmp("pravka.htm")),array(
				'adm'=>$ADM,
				'num'=>RE0('num'),
				'oid'=>$oid,
				'text'=>$o,
				'rows'=>page($o,50)
		    )
		);
		otprav("
	pravka_send=function(){
		majax('ajax_pravka.php',{a:'submit',oid:'".h($oid)."',es:".($ss+strlen(nl2brp($o))).",ss:".$ss.",textnew:idd('pravk').value,text:idd('pravk').defaultValue});
	};
	ohelp('opechatku','���������� ��������',\"".njsn($s)."\");
/*
	setkey('esc','',function(a,b){clean('opechatku')},true,1);
	setkey('enter','',function(a,b){pravka_send()},false,1);
	setkey('enter','ctrl',function(a,b){pravka_send()},false,1);
*/
	idd('pravk').focus();
");

	}

	if($a=='submit') { // ����� �������� �� ���������!
		ADMA(1);
		$oid=RE('oid'); // ss es
		if(substr($oid,0,1)=='a') $l='@dnevnik_comment@Answer@id@'.substr($oid,1);
		elseif(substr($oid,0,5)=='Body_') $l='@dnevnik_zapisi@Body@num@'.substr($oid,5);
		elseif(substr($oid,0,7)=='Header_') $l='@dnevnik_zapisi@Header@num@'.substr($oid,7);
		else $l='@dnevnik_comm@Text@id@'.$oid;
		$text=pravka_valitext(RE('text')); // ���� ���� � �������!
		$textnew=pravka_valitext(RE('textnew')); // ���� ���� � �������!

		pravka_priem($l,$text,$textnew);
	}

//	if($a == 'create') { idie('������� ����?!'); pravka_basa_create(); } // �������: ������� ����� ����!

idie('������� �������: '.h($a));

//===================== ������ ��������� ������ � ������� ===============
/*
function pravka_validata($data) {
		$d=preg_replace("/[^0-9a-z\._\-\#\/\:]/si","",$data); if($d!=$data) pravka_otvet('fuck you');
		return $data;
}
*/

function filename_valid($f) { return rpath($f); } // �� ������ ������ ��� ���� ������ �� ����������� �� �����

function pravka_valitext($s) {
	$s=h(urldecode($s));
	if(RE('hashpresent')==2) $s=strtr($s,'ABCEHKMOPTXaceopxy','������������������'); // ��� ��� ������ ����
	return $s;
}

function pravka_basa_replace($data,$txt) { if(!$GLOBALS['admin']) return; // �� �����������

if(isset($GLOBALS['rdonly'])) return;


        list($base,$table,$bodyname,$wherename,$whereid)=explode('@',$data);
	if($base=='file#') { // ���� ������ ��������������� ��� �����, �� $table - ��� ��� ���
		$i=file_put_contents($GLOBALS['host'].filename_valid($table),$txt); // �������� ����������� � ����
		chmod($GLOBALS['host'].filename_valid($table),0666);
		return $i; 
	} else { // ������ ��� ����
		return ms("UPDATE ".($base!=''?"`".e($base)."`.":'')."`".e($table)."`
			SET `".e($bodyname)."`='".e($txt)."'
			WHERE `".e($wherename)."`='".e($whereid)."'","_l",0);
	}
}

function pravka_oldtxt($data) {
        list($base,$table,$bodyname,$wherename,$whereid)=explode('@',$data);
	if($base=='file#') { // ���� ������ ��������������� ��� �����, �� $table - ��� ��� ���
		if($txt=file_get_contents(rpath($GLOBALS['host'].filename_valid($table)) ) ) return $txt;
		pravka_otvet("��� ������ ����� '".h($table)."'");
	} else { // ������ ��� ����
		 return ms("SELECT `".e($bodyname)."` FROM ".($base!=''?"`".e($base)."`.":'')."`".e($table)."`
				WHERE `".e($wherename)."`='".e($whereid)."'","_l",0);
	}
}

//##################################################################
//##################################################################
// ��� �������� � ������ ������� ��������

function pravka_basa_add($data,$stdprav,$metka='new') { // �������� ������ � ���� (����������)
	if(!$GLOBALS['admin']) return;
	pravka_basa_add1($data,$stdprav,$metka);
}

function pravka_basa_add1($data,$stdprav,$metka='new') { // �������� ������ � ���� (�������!)
global $text,$textnew,$login;
        $ara=array();
        $ara['stdprav']=e($stdprav);
        $ara['Date']=e($data);
//        $ara['lju']=e($GLOBALS['lju']);

	$ara['unic']=$GLOBALS['unic'];
	$ara['acn']=$GLOBALS['acn'];

/*
        $ara['sc']=e($GLOBALS['sc']);
        $ara['ipbro']=e($_SERVER['REMOTE_ADDR']."\n".$_SERVER['HTTP_X_FORWARDED_FOR']."\n".$_SERVER['HTTP_USER_AGENT']);
        $ara['Mail']=e(str_replace('mailto:','',$_COOKIE['CommentaryAddress']));
        $ara['Name']=e($_COOKIE['CommentaryName']);
        $ara['login']=e($login);
*/
        $ara['text']=e($text);
        $ara['textnew']=e($textnew);
        $ara['metka']=e($metka);
	msq_add($GLOBALS['db_pravka'],$ara); // ������ � ����
}

function pravka_basa_p($id) {
	$p=ms("SELECT * FROM `".$GLOBALS['db_pravka']."` WHERE `id`='".e($id)."'",'_1',0);
	if($p['id']!=$id) pravka_otvet("������ ������!!!");

	if(!$GLOBALS['pravshort']) {
		$p['text']=pravka_bylo($p['stdprav']); // �� ���� �� ���������� ��� ����?
		$p['textnew']=pravka_stalo($p['stdprav']); // �� ���� �� ���������� ��� ����?
	}
	return $p;
}

function pravka_basa_metka($id,$metka,$answer) { if(!$GLOBALS['admin']) return;
if(isset($GLOBALS['rdonly'])) return;
	msq_update($GLOBALS['db_pravka'],array('metka'=>e($metka),'Answer'=>e($answer)),"WHERE `id`='".e($id)."'");
}

function pravka_basa_getmetka($data,$stdprav) { // �������� ����� ��� ������ ������
	return ms("SELECT `metka` FROM `".$GLOBALS['db_pravka']."` WHERE `Date`='".e($data)."' AND `stdprav`='".e($stdprav)."'",'_l',0);
}


//======================================================================================================
function pravka_edit_c($id) { // editor ������������
	$p=pravka_basa_p($id); // ����� ������ �� ���� ������
	if($p['Answer']=='') pravka_otvet_e($p);
	pravka_otvet_e($p,pravka_textarea($id,$p['Answer'],'edit_c_txt'));
}

function pravka_edit_c_txt($id) { // editor ������������
	$p=pravka_basa_p($id);
	$p['Answer']=RE('answer');
	pravka_basa_metka($id,$p['metka'],e($p['Answer']));
	pravka_otvet_e($p);
}

function pravka_edit($id) { // editor
	$p=pravka_basa_p($id); // ����� ������ �� ���� ������
	$p['oldtxt'] = pravka_oldtxt($p['Date']); // ����� �������� ����
	$stdprav = pravka_stdprav($p,200); // ����� ������� �����
	$text = $p['metka']=='submit' ? pravka_stalo($stdprav) : pravka_bylo($stdprav);
	$n=substr_count($p['oldtxt'],$text); if($n != 1) {
unset($p['oldtxt']);
pravka_otvet_e($p,"
�� ������� ����� �����: ��� ����������� ".intval($n)." ���.
<p>stdprav='".h($stdprav)."'
<p>text='".h($text)."'
<p>p='".nl2br(h(print_r($p,1)))."'
"); }
	pravka_textarea($id,$text,'edit_txt');
	pravka_otvet_e($p,pravka_textarea($id,$text,'edit_txt'));
}


function pravka_edit_txt($id) { // editor
	$textnew=str_replace('\r','',RE('answer'));
	$p=pravka_basa_p($id); // ����� ������ �� ���� ������
	$p['oldtxt'] = pravka_oldtxt($p['Date']); // ����� �������� ����
	$stdprav = pravka_stdprav($p,200); // ����� ������� �����
	if($p['metka']=='submit') $text=pravka_stalo($stdprav); else $text=pravka_bylo($stdprav);
	if(substr_count($p['oldtxt'],$text) != 1) pravka_otvet_e($p,'�� ������� ����� ��� ����� ��������������.');
	if($text == $textnew) pravka_otvet_e($p,"��, �� � �����? ���������� �����������.");
	$stdprav=std_pravka($textnew,$text,$p['oldtxt']); // ��������� ����������� ����� ����� ������
	if($GLOBALS['pravka_paranoid']) pravka_basa_add($p['Date'],$stdprav,'submit'); // ������������� ���������� ����
	pravka_basa_replace($p['Date'],str_replace($text,$textnew,$p['oldtxt'])); // ��������� ������ �� ������
	$p['Answer'] .= '<i>��� ����� � �������������� �����:</i><p>'.str_replace("\n","\n<br>",$stdprav); $p['metka'] = 'discard';
	pravka_basa_metka($id,$p['metka'],$p['Answer']); // �������� ��� discard
	pravka_otvet_e($p); // ������ �����
}


function pravka_showmore($id) { // �������� ������
	$p=pravka_basa_p($id); // ����� ������ �� ���� ������
	$p['oldtxt'] = pravka_oldtxt($p['Date']); // ����� �������� ����
	$p['stdprav'] = pravka_stdprav($p,500); // ����� ������� �����
	pravka_otvet_e($p);
}

####################################################

function pravka_submit($id,$answer) { // ������� ������
	$p=pravka_basa_p($id); // ����� ������ �� ���� ������
	if($p['metka']=='discard') $answer='�����������, ����� �������: '.$answer; // ������������ �����
	$oldtxt=pravka_oldtxt($p['Date']); // ����� �������� ����
	if(substr_count($oldtxt,$p['text'])!=1) $answer .= " �� ��� ����� ��� ����������!";
	else pravka_basa_replace($p['Date'],str_replace($p['text'],$p['textnew'],$oldtxt)); // ������� ������
	$p['Answer'] .= pravka_answer_n($answer,1); $p['metka'] = 'submit';
	pravka_basa_metka($id,$p['metka'],$p['Answer']); // �������� ��� submit
	pravka_otvet_e($p); // ������ �����
}

function pravka_discard($id,$answer) { // ��������� ������
	$p=pravka_basa_p($id); // ����� ������ �� ���� ������
	$metkanew='discard';
	if($p['metka']=='submit') { // ���� ���� ������� - �������
		$answer='�����������, ����� ��������: '.$answer; // ������������ �����
		$text=pravka_bylo($p['stdprav']); // ��� ����
		$textnew=pravka_stalo($p['stdprav']); // ��� �����
		$oldtxt=pravka_oldtxt($p['Date']); // ����� �������� ����
		if(substr_count($oldtxt,$textnew)!=1) { $answer .= '�������� �� �������.'; $metkanew='submit'; }
		else pravka_basa_replace($p['Date'],str_replace($textnew,$text,$oldtxt)); // ������� ������
		}
	$p['Answer'] .= pravka_answer_n($answer,0);
	$p['metka'] = $metkanew;
	pravka_basa_metka($id,$p['metka'],$p['Answer']);
	pravka_otvet_e($p); // ������ �����
}

###################################################

function pravka_priem($data,$text,$textnew) { // global $_RESULT; // ����� �������� �� ���������!

	$text=str_ireplace('&quot;','"',$text);
	$textnew=str_ireplace('&quot;','"',$textnew);

	$oldtxt=pravka_oldtxt($data); // ����� �������� ����
	$nzamen=substr_count($oldtxt,$text); // ������� ��� ����������� ���� �������� � ������ (����, ����� 1)
if($oldtxt == '') pravka_otvet("������ �����-��. ��� ����� ������ � ����.");
if($text == $textnew) pravka_otvet("��, �� � �����? ���������� �����������.");
if($text == '') pravka_otvet("�������� ���-������ � ���������.\n����� ����� �����?");
if($nzamen == 0) {
if($GLOBALS['ADM']) {
	pravka_otvet("������. old:<p>'".h($text)."'<hr>new:<p>'".h($oldtxt)."'");
	if(preg_match("/\&[a-z0-9\#]+\;/si",$oldtxt,$m))
	pravka_otvet("������, �������������� �� �������.\n\n�����! � ��������-�� ������ �� ��������� ������ ���� '".h($m[0])."'
".(preg_match("/^[^@]*@site@[^@]+@[^@]+@(\d+)$/",$data,$m)?" � <a href='".$wwwhost."adminsite/?mode=one&edit=".$m[1]."'>������ ���� ����� #".$m[1]."</a>":'') );
} pravka_otvet("������, �������������� �� �������.\n\n�����, ��� ������� HTML ��������?");
}

if($nzamen > 1) pravka_otvet("��, ����� �������������� ����������� ��������� ���.\n���������� �������� ������� ��������.");

	$stdprav=std_pravka($textnew,$text,$oldtxt); // ��������� ����������� ����� ����� ������


#	$stdprav=std_pravka(pravka_stalo($stdprav),pravka_bylo($stdprav),$oldtxt); // � ��� ��� ���������, ������ � ��������� �� �����
# �� ���� ������ ��� ���������! ���� ������

	if(!$GLOBALS['ADM']) {
	$metka=pravka_basa_getmetka($data,$stdprav); // �������� �����, ���� ��� ����� ������
if($metka=='new') pravka_otvet("����� ������ ��� ��������\n� ��� ������������.");
if($metka=='discard') pravka_otvet("����� ������ ��� ������������,\n�� ����� ����� �� ���������.\n\n��� �����, ������ �� ��� �����.\n����� �������. � ������.");
if($metka=='submit') pravka_otvet("��... ����� ������ ��� ������������,\n� ���� ���� ������������ �������.\n���������, ������ �� �� �� ������ �� ������.\n�����, ��� ��������� ������� �����?\n�����������-�� ��������...");
	pravka_basa_add1($data,$stdprav,'new');
//	pravka_otvet_nbody(podsvetih(podsveti($textnew,$text)));
	pravka_otvet_nbody($textnew);
	}

	if($GLOBALS['pravka_paranoid']) pravka_basa_add($data,$stdprav,'submit'); // ������������� ���������� ����
	pravka_basa_replace($data,str_replace($text,$textnew,$oldtxt)); // ��������� ������ �� ������
	pravka_otvet_nbody($textnew);
}

//=============================== ������ ����������� ==========================
function pravka_otvet_nbody($s) { global $oid; otprav("
var s=stripp(vzyal('".$oid."'));
zabil('".$oid."',s.substring(0,".RE('ss').")+nl2brp('".njsn($s)."')+s.substring(".RE('es').",s.length));
clean('opechatku');
");
}

function pravka_otvet_e($p=0,$ext=''){

// idie(h("zabil('".$GLOBALS['id']."',\"".($p===0?'':njsn(_one_pravka($p,$ext)))."\")"));

	otprav("zabil('".$GLOBALS['id']."',\"".($p===0?'':njsn(_one_pravka($p,$ext)))."\")");
}

function pravka_otvet($s) { idie($s); }


##############################################################################################################

function pravka_answer($answer) { // ����������� ����������� �������
$a=array(
	'da'=>'�� �������! ������� �������!',
	'ugovorili'=>'��... �� ���������? ��... �������, ��. �������.',
	'zadumano'=>'��������, �� ����� ���� ���� ������ ���.',
	'gramotei'=>'�, ����...',
	'len'=>'��, ��� ���-�� � ���� ��������... �����, ����� ����� ���� "���������" ���������?',
	'inache'=>'������, � ������� �� ����.',
	'spam'=>'��������� - �����.'
);
foreach($a as $l=>$m) if($l==$answer) return $m;
return $answer;
}

function pravka_answer_n($answer,$n) { if($answer!='') return '<div class='.($n?'y':'n').'>'.e(h($answer)).'</div>'; }

function pravka_textarea($id,$text,$modescript) { $texth=h($text); $ide=$id."_e";
return "<table><tr>
<td><TEXTAREA id='$ide' class='t' cols='50' rows='".max(page($texth),3)."'>".$texth."</TEXTAREA></td>
<td valign=top>
<input value='SEND' class='t' onclick=\"pravka($id,'$modescript',idd('$ide').value)\" type='button'>
</td></tr></table>";
}

// ��� ������ � �������� - ���� �� ���� ���������� ���� ����������������!
//$arhdir=$_SERVER['DOCUMENT_ROOT'].'arhive/'; // ��� ����������, ��� ������, ���� ��� � ������ (��������� windows-1251)
//$arhbasa='dnevnik_zapisi'; // ��� �������, ��� ������, ���� ��� � MySQL (� ���� - � ���� 'Body' �� ����� 'Data' ���� 2004-01-14
//include_once($_SERVER['DOCUMENT_ROOT'].'/dnevnik/_msq.php'); msq_open('lleo','windows-1251'); // ���������� MySQL
//include_once($_SERVER['DOCUMENT_ROOT'].'/dnevnik/_autorize.php'); // ���������� ����������� ������ $IS_EDITOR=true ���� �����
//include_once($_SERVER['DOCUMENT_ROOT'].'/sys/pravka/_podsveti.php'); // ��������� Diff - ������ ����, ��������, �� ��������
//include_once($_SERVER['DOCUMENT_ROOT'].'/sys/pravka/_one_pravka.php'); // ��������� ������ ������ � ����� �������
//include($_SERVER['DOCUMENT_ROOT'].'/sys/pravka/ajax_pravka_code.php'); // ������� ��������� - ��� ��������������� ��������

idie(nl2br(h(__FILE__.": unknown action `".$a."`")));
?>