<?php /* DAT

������� �������� ��������� �� ���������. � ������ ������ ��������� ����������� |, ������
�������� ���������� {0}, ������ {1} � �.�. ������ ������ ���������� �� ��������.

����� {n} ���������� �� ���������� ����� ������� ��������, ����� ���� {n2}, {n3} � �.�. - �� ���������� ����� � N ����� � ������ (001, 0001)

{_DAT: template=\n<p>{n2}. <a href='/dnevnik/{1}.html'>{1} ? {2}</a><br>{@MP3: http://lleo.me/audio/f5/{0}@}
facebook.mp3	| 2011/10/17 | ��� ���������� �����
konoplya.mp3	| 2011/10/03 | ��� �������� ��������
china.mp3	| 2011/09/26 | ��� ��� ��� � ����
shlagbaum.mp3	| 2011/09/19 | ��� ���������
_}
*/

function SELECT($e) {
    $c=array_merge(array(
	    'select'=>''
    ),parse_e_conf($e));
    // $c['template']=str_replace("\\n","\n",$c['template']);
    // $s.=mper($c['template'],$a);

	$sels=0; $r=array(); foreach(explode("\n",$c['body']) as $l) { if(empty($l))continue;
		$sel=0;
		if(strstr($l,'|')) {
		    list($value,$text)=explode('|',$l,2);
		    if(strstr($text,'|')) { list($text,)=explode('|',$text,2); $sel=1; $sels++; }
		} else { $value=$text=$l; }
		$r[]=array($value,$text,$sel);
	}
	if($sels>1) return "<font color=red>SELECT: Error selected; ".$sels."</font>";
	if(!$sels) $r[0][2]=1;

	$o=''; foreach($r as $p) $o.="<option value=\"".$p[0]."\" ".($p[2]?" selected":'').">".$p[1]."</option>";
	return "<select".($c['select']==''?'':" ".$c['select']).">".$o."</select>";
}
?>