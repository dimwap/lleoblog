<?php // ����������� ����� ����������� ��� ���������

function NIKONOV($e) {

$c=array_merge(array(
'time'=>86400*8,
'tag'=>0, // 0 - search �� Header, 1 - search �� tag
'search'=>"\"majax('search.php',{a:'header',search:'{domain}'})\"",
'broken'=>"<p><font color=red><i>���� �� ������ ���� ����� ����� �������, ������, ���������� ���������� ����� ���������, ��� ����"
." <a href='{url}'>{domain}</a>, ��� �������� ������� ���� ��������, ����� ��������. ����������� ������� - {case}."
." ��� ���������� ��������� ������, ��� ��� �� ������� ��� ���� � ��� ������.</i></font>",
'nemalo'=>"<p><div style='border: 1px dotted black; margin-left:15%; margin-right:15%; padding:10pt; font-size: 12pt;'>"
."���� ����� ������� ��� ������� <a href='http://{domain}'>{domain}</a>, ��� � ���� ��������� �������. ������ ��� {domain} � ������� ������ �������� "
."����������, <span class=l onclick={search}>��� �� ������ ������</span></div>",
'continue'=>"<p><center><a href='{url}'>...������ ������ �����: {url}</a></center>"
),parse_e_conf($e));

// dier($c);

    if($c['tag']) $c['search']="\"majax('search.php',{a:'tag',tag:'{domain}'})\"";

	list($url,$text)=explode("\n",ltrim($c['body'],"\n"),2); $c['url']=c($url); $text=c($text);

	if(isset($GLOBALS['nikonov_no_epilog'])) return $text; // ���� ���������� ���������� - ������� �������.
	$a=parse_url($url); $a=explode('.',$a['host']); $c['domain']=h($a[sizeof($a)-2].'.'.$a[sizeof($a)-1]);

	if( time() > (strtotime(substr($GLOBALS['article']['Date'],0,10)) + $c['time']) ) return $text.mper($c['nemalo'],$c); // ���� ������ ������ - �� ��������

	$flag=$GLOBALS['hosttmp'].rpath($domain).".flag"; if(file_exists($flag)) { $c['case']=fileget($flag); return $text.mper($c['broken'],$c); }

	return mper($c['continue'].$c['nemalo'],$c);
}

?>