<?php

function installmod_init() {
    //    $f=$GLOBALS['foto_file_small']."_fotoset.dat";
	if(strstr($GLOBALS['httphost'],'://lleo.me/dnevnik/')) return false;
    //    if(($r=unserialize(file_get_contents($f)))===false || !sizeof($r)) return false;
    //    if($GLOBALS['acc']!='') return "ERROR: acc!=''!";
	return "Patch CSS";
}

function installmod_do() { global $o; $o='';

    $file2=$GLOBALS['filehost']."template/blog.html";
    $s=fileget($file2);

    if(strstr($s,"'lleo.me'")) {
	$g="\n@@@@@@@@@@@@@@@@\n((((((((((((((((   ";
	$gg="   )))))))))))))))))\n@@@@@@@@@@@@@@@\n";

	$s=preg_replace("/<\!\-\-LiveInternet counter\-\->(.*?)<\!\-\-\/LiveInternet\-\->/si",'',$s);
	$s=preg_replace("/<\!\-\-LiveInternet logo\-\->(.*?)<\!\-\-\/LiveInternet\-\->/si",'',$s);
	$s=preg_replace("/<\!\-\-Openstat\-\->(.*?)<\!\-\-\/Openstat\-\->/si",'',$s);
	$s=preg_replace("/<script>(.*?i,s,o,g,r,a,m.*?'lleo\.me'.*?)<\/script>/si",'',$s);
	$s=preg_replace("/\{(_SIGNAL\:.*?_)\}/si",'',$s);
	$s=preg_replace("/\{(_BTCINFO\:.*?_)\}/si",'',$s);

	$s=preg_replace("/\{(_NO\:\s*YANDEXCOUNT.*?_)\}/si",'',$s);
	$s=preg_replace("/\{(_NO\:\s*ADDTHIS.*?_)\}/si",'',$s);
	$s=preg_replace("/\{(_NO\:.*?_)\}/si",'',$s);

	$s=preg_replace("/\{(_PHPEVAL\:.*?_)\}/si",'',$s);
	$s=preg_replace("/\{(_REKOMENDA\:.*?_)\}/si",'',$s);

$s=str_replace("<div><a title='������ �������� ��������� ������ ��� �����?<br>��� ��������, �� ���� ������������ �����������.<br>��� ����������� �� �������� ��������� ��������.' href='/reklama'>������� � �����</a></div>",'',$s);
$s=str_replace("<div><a title='������� �� �������� � ����������� ����� /blog (��-�� ���������� � ����� /dnevnik), ��� ��������������, ��� ����� ���������� ���� ��� ������ ����� (�� ��������).' href=/blog/lleoblog>������ �����</a></div>","",$s);

	$s=preg_replace("/(<div title=\"�������������� \(������ � ��������\),<br>��� ����.*?<\/div>)/si",'',$s);
	$s=preg_replace("/(<div title=\"���������������� ������������� �������<br>��� ����.*?)<\/div>/si",'',$s);
	$s=preg_replace("/(<div title='����� ��������� ����������� ���� ���� ������.*?)<\/div>/si",'',$s);
	$s=preg_replace("/\n<div><a href='https\:\/\/www\.instagram\.com\/lleokaganov.*?<\/div>/si",'',$s);
	$s=preg_replace("/\n<div style='margin-left:15px;margin-bottom:10px;' class='l r' alt='�������� ������������� ������� � ����������.+?<\/div>/si",'',$s);
	$s=preg_replace("/\noff_socialmedia=<p>����������� � ���� ������� �� ���� ������� ���������, ������� �� ���������[^\n]+/si",'',$s);
	$s=preg_replace("/url\('\/dnevnik\/design\/userpick\/[^\.]+.jpg'\)/si","url('".$GLOBALS['www_design']."userpick.jpg')",$s);

$s=str_replace("<div><a href='http://onlime.ru'><img border=0 src='https://lleo.me/dnevnik/2015/03/onlimeru.gif' title='��� ������� �������� ���������, ����� ���� � ���� � ��������'></a></div>","",$s);

$s=str_replace("<div class=r>Bitcoin: \$</div>","",$s);
$s=str_replace("<div class=br>��������� ���: \$</div>","",$s);
$s=str_replace("<div>��� ������ ��� ���� <a href='http://lleo.me/dnevnik/free'>���� ���</a></div>","",$s);
$s=str_replace("{_SCRIPT_ADD: {httphost}js/kuku.js _}","",$s);
$s=str_replace("\nsocialmedia=lj:lleo","",$s);

$s=str_replace("������� ������� ��������<br>","�������<br>",$s);

	$s=preg_replace("/<div[^>]*>\s*<\/div>/si",'',$s);
	$s=preg_replace("/\n{3,}/si","\n\n",$s);

$s=str_replace("sys.css? ","sys.css?rand=2 ",$s);

    $f3=$file2.".rename_".date("Y-m-d_H-i-s").".old";
    rename($file2,$f3);
    if(!is_file($f3)) idie('Error save: '.$f3);

    fileput($file2,$s);
    if(fileget($file2)!=$s) idie('Error save: '.$file2);

    $o.="<div>".$file2." - patched</div>
<div>OLD File blog.html renamed to: ".$f3."</div>";


    }


// � ������ ���������� CSS

$r=glob($GLOBALS['filehost']."css/*.css");

foreach($r as $l) {
    $s=fileget($l);

    if(	stristr($s,"url(/dnevnik/design/") || stristr($s,"url(/blog/design/") 
|| stristr($s,"url('/dnevnik/design/") || stristr($s,"url('/blog/design/") 
|| stristr($s,"url(\"/dnevnik/design/") || stristr($s,"url(\"/blog/design/") 

) { // ))

        $o.="<br>".$l;
	$s0=preg_replace("/url\([\'\"]*\/dnevnik\/design\/([^\'\"\)]+)[\'\"]*\)/si","url('".$GLOBALS['www_design']."$1')",$s);
	$s0=preg_replace("/url\([\'\"]*\/blog\/design\/([^\'\"\)]+)[\'\"]*\)/si","url('".$GLOBALS['www_design']."$1')",$s0);
	if($s0!=$s) { fileput($l,$s0); if(fileget($l)!=$s0) idie('Error save: '.$l); else $o.="<font color=green> pathed succesfull</font>"; }

    } else { 
//$o.="<br>".$l." <font color=green>NO NEED</font>";
}


}

// idie($o);

    //    if($GLOBALS['acc']!='') idie("ERROR: acc!=''!");
    //    $f=$GLOBALS['foto_file_small']."_fotoset.dat";
    //    saveset(unserialize(file_get_contents($f)));
//	unlink($f);
	return $o;
}

?>