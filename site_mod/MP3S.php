<?php /* �������� ������������� � mp3

{_MP3S:
/VIDEO/mp3/Tikkey/��� ����� ������ - ���� �� �������� ������� �����.mp3 | ���� �� �������� ������� �����
/VIDEO/mp3/Tikkey/��� ����� ������ - ���� �������� ����� �����.mp3
/VIDEO/mp3/Tikkey/��� ����� ������ - ���� �� �������� ����.mp3
http://lleo.me/dnevnik/2013/11/Zilant/Tikkey_DURAKI.mp3
 _}

*/

$GLOBALS['audion']++;
SCRIPTS("

function changemp3(url,name,audion){ if(typeof('user_opt')!='undefined') user_opt.s=0;
zabil('audiosrc'+audion,'<div class=br>'+name+'</div><div><audio controls autoplay id=\"audiid'+audion+'\"><source src=\"'+h(url)+'\" type=\"audio/mpeg; codecs=mp3\"><span style=\"border:1px dotted red\">��� ������� �� ������������ MP3, ������� ���</span></audio></div>');
};

");


/*
idd('audiid'+audion).addEventListener('ended', function() {
alert(this.currentTime);
alert('end');
}, false);


�����-������� HTML5 ����� ������ ��������, ��������� ������ ����� JavaScript:

    currentSrc ��������� �������� ����, ������� � ������ ������ ��������������� ���������, ���� ������������ ����-���������;
    videoHeight � videoWidth i������ �������� ������� ����������;
    volume ��������� �������� � ��������� �� 0 �� 1, ������������ ������� ��������� (��������� ���������� ���������� ��� ��������; � ��� ������������ ���������� ���������� ���������);
    currentTime ������ ������� ������� ��������������� � ��������;
    duration� ����� ����� ������������ ��������� ����� � ��������;
    buffered � ������, �����������, ����� ����� ��������� ����� ���� �������;
    playbackRate � �������� ��������������� ����� (�� ��������� � 1). �������� ��� �������� ��� ��������� (1.5) ��� ���������� (0.5) ���������������;
    ended ���������, ��������� �� ����� �����;
    paused ������ ����� true ��� �������, � ����� � false (��� ������ ���������� ��������������� �����);
    seeking ���������, ��� ������� �������� ������� ��������� ������ � ��������� � ����� �������.

�����-������� HTML5 ����� �������� ��������� ������, ����������� ��� ��������� ��������:

    play �������� ��������� � ������������� �����;
    pause ������������� ������������ �������� �����������;
    canPlayType(type) ) ����������, ����� ������ ������������ �������. ���� �� ��������� ����� ��� ����� video/mp4, ������� ������� ������� probably, maybe, no ��� ������ �������;
    load i���������� ��� �������� ������ �����, ���� �� ��������� ������� src.

� ������������ HTML5 Media ���������� 21 �������; ��� ��������� �� �������� ����� ������������:

    loadedmetadata �����������, ����� ���������� �������� ������������ � �������;
    loadeddata �����������, ����� ������� ����� ������ ��������������� � ������� �������;
    play ��������� �����, ����� ��� ������ �� ��������� � ��������� paused ��� ended;.
    playing �����������, ����� ��������������� �������� ����� �����, ����������� ��� ������;
    pause ������������� ������������ �����;
    ended �����������, ����� ����������� ����� �����;
    progress ���������, ��� ���� ��������� ��������� ������ ��������� �����;
    seeking ����� true, ����� ������� ����� �����;
    seeked ����� false, ����� ������� �������� �����;
    timeupdate �����������, ����� ��������������� �����-������;
    volumechange �����������, ����� ���������� �������� muted ��� volume.


*/



function MP3Sname($l) { $n=explode('/',$l); $n=$n[sizeof($n)-1]; return str_ireplace('.mp3','',$n); }
function MP3Scby($t) { $GLOBALS['MP3Syoutube'][]=$t[1]; return ''; }

function MP3S($e) { // $e=urldecode($e);

    $e=strtr($e,'�','-');

$tmpl="<tr valign=center><td><img src='".$GLOBALS['www_design']."img/play.png' title='play' onclick='changemp3(\"{#url}\",\"{#name}\",".$GLOBALS['audion'].");'></td><td><a title='Download mp3' href=\"{#url}\">{name}</a>{next}</td></tr>";
$tmpl0="<tr><td colspan=2>&nbsp;</td></tr>";
$tmplb="<tr><td></td><td><b>{name}</b></td></tr>";
$tmpl_youtube=" &nbsp; &nbsp; <a href='http://www.youtube.com/watch?v={code}'>youtube</a>";

$o="<div id='audiosrc".$GLOBALS['audion']."'></div><table border=0 cellspacing=0 cellpadding=2>";

foreach(explode("\n",$e) as $l) { if(c($l)=='') { $o.=$tmpl0; continue; } $next='';
    if(strstr($l,'|')) { list($l,$n)=explode('|',$l); if(c($l)=='') { $o.=mpers($tmplb,array('name'=>$n)); continue; }
	$GLOBALS['MP3Syoutube']=array(); $n=preg_replace_callback("/(\@[^\s]+)/s",'MP3Scby',$n);
	if(str_replace(' ','',$n)=='') $n=MP3Sname($l);

	if(sizeof($GLOBALS['MP3Syoutube'])) foreach($GLOBALS['MP3Syoutube'] as $e) $next.=mpers($tmpl_youtube,array('code'=>substr($e,1)));

    } else { $n=MP3Sname($l); }
    $n=c($n); $l=c0($l);
    $o.=mpers($tmpl,array('url'=>$l,'name'=>$n,'next'=>$next));
}

return $o."</table>";
}

/*
<audio id="player" src="sound.mp3"></audio>
<div>
    <button onclick="document.getElementById('player').play()">���������������</button>
    <button onclick="document.getElementById('player').pause()">�����</button>
    <button onclick="document.getElementById('player').volume+=0.1">��������� +</button>
    <button onclick="document.getElementById('player').volume-=0.1">��������� -</button>
</div>


<img src="http://www.w3schools.com/images/compatible_ie.gif" alt="Internet Explorer" title="Internet Explorer" width="31" height="30">
<img src="/images/compatible_firefox.gif" alt="Firefox" title="Firefox" width="31" height="30">
<img src="/images/compatible_opera.gif" alt="Opera" title="Opera" width="28" height="30">
<img src="/images/compatible_chrome.gif" alt="Google Chrome" title="Google Chrome" width="31" height="30">
<img src="/images/compatible_safari.gif" alt="Safari" title="Safari" width="28" height="30">
*/

?>