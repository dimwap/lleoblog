<?php /* �������� ��� ���

���� ����� �������� (� ��� ��� ��������� ������ � �����) - ������ ���� �� ���� ������ �������� [...], ���� ������� (������ � ���������� �����) - �������� ����� [��������&nbsp;����������] �� ������.
��� ������� ������ ����� �������� ��, ��� ���� ������.

����� ����� ������ ��������������, ������ �� � ������ � ���������� �������.

<script>function cut(e,d){e.style.display='none';e.nextSibling.style.display=d;}</script>
<style>.cut{cursor:pointer;color:blue;text-align:center;}.cut:hover{text-decoration:underline;}</style>

��� � �������� ��������, ��� ������� ������ {_cut:����_}.
�������� ���� ��� ���� ������ ������ {_cut:�������_}.
��������� � ����� �������� � ������ {_cut:������_}.
������ ����� ���� ����, {_cut:[���������� ������� ����]������ ��� ����� �����_}.

*/

SCRIPTS("cut","function cut(e){
e.style.display='none';
var n=e.nextSibling;
alert(n.tagName);
n.style.display=n.tagName=='DIV'?'block':'inline';
}");
STYLES("cut","
.cut,.cutnc{cursor:pointer;color:blue;}
.cut{text-align:center}
.cut:hover,.cutnc:hover{text-decoration:underline;}
");

function cut_($e) { return '##';

$conf=array_merge(array(
'otl'=>0,
'center'=>0,
'txt'=>'span',
'cut'=>'span',
'close'=>'',
'title'=>'',
'class'=>'cut',
'style'=>'cursor:pointer;color:blue;',
'text'=>"[��������&nbsp;����������]"
),parse_e_conf($e)); $e=$conf['body'];

	$t=$conf['text'];

	if($conf['center']) $conf['style'].='text-align:center;';
//	if($conf['inline']) $conf['style'].='display:inline;';
//	if(stristr($e,'#nocenter#')) $e=str_ireplace('#nocenter#','',$e); else $scut.='text-align:center;';

	if(preg_match("/^\s*\[(.*?)\]([^\]].*?)$/si",$e,$m)) { $e=c($m[2]); $t=$m[1]; }

	if(strstr($e,"\n")||stristr($e,'<p')||stristr($e,'<div')) $conf['txt']='div';
	if(strstr($t,"\n")||stristr($t,'<p')||stristr($t,'<div')) $conf['cut']='div';

//if($GLOBALS['admin'])
	$s="<".$conf['cut'].(empty($conf['title'])?'':' title="'.$conf['title'].'"')
." class='".$conf['class']."'"
." style=\"".$conf['style']."\""
." onclick='cut(this)'>".$conf['text']."</".$conf['cut'].">"
."<".$conf['txt']." style='display:none'>".trim($e)."</".$conf['txt'].">";

if($conf['otl']) $s=h($s);

return $s;

}

?>