<?php /* ����������� ������ � ��������� - ���� �������� � $Date

� ������ ����������� ������������ ���� ����������� �������� ��������� �� ����� ������� � �������. � ���� ������ �
��������� ���� �������� ��������� ���� ������, ��� ������� �������� � ������������� �������, � ����� ��������,
��������:



{_COMENTS:
socialmedia=lj:lleo:lj
#��� socialmedia=autopost ������ ����� ��� {_NO:autopost:flat:http://... url_}
off_socialmedia=<p>����������� � ���� ������� �� ���� ������� ���������, ������� �� ���������. �� �� ������ ����� � <a href='{0}?mode=reply#add_comment'>�������� ����������� � ���� ������� ��</a>. ������, ��� ����� � ��� ����������� �� �����, �� ��� ������������� ���� ��� �������� ��������.
_}

*/

/*
		SCRIPTS("page_onstart.push(\"var c=gethash_c(); if(c){ if(idd(c)) kl(idd(c)); else majax('comment.php',{a:'loadpage_with_id',page:0,id:c,dat:".$article['num']."});}"
."var r=f5_read('hidcom'+num); if(r) { r=r.split(','); for(var i in r) {if(r[i]) hide_comm(r[i]);} }"
."var r=f5_read('ueban'); if(r) { r=r.split(',');"
."var s=[],p=idd(0).getElementsByTagName('DIV'),i,j,u; if(p){for(i=0;i<p.length;i++){ u=1*p[i].getAttribute('unic'); if(!u) continue; if(!s[u]) s[u]=[]; s[u].push(p[i].id); }"
."for(i in r) { u=s[r[i]]; if(u) for(j in u) if(!isNaN(u[j])) hide_comm(u[j]); }"
."}}"
."\");");
*/

function COMENTS($e) { global $article, $podzamok, $load_comments_MS, $enter_comentary_days, $N_maxkomm, $idzan;

$conf=array_merge(array(
'ostalnye'=>LL('comm:ostalnye'), // ���������
'off'=>LL('comm:off'), //<p>����������� � ���� ������� ������ ���������, ������� �� ���������.
'friends_only'=>LL('comm:friends_only'), //<p>� ���� ������� �������� ����������� ����� ������ ������ (��������, ��).
'login_only'=>LL('comm:login_only'), //<p>� ���� ������� �������� ���������� ����� ������ ������������. ������������ ����� <span class='ll' {majax}>�����</span>
'login_only_done'=>LL('comm:login_only_done'), //<p>� ���� ������� �������� ���������� ����� ������ ������������. ������������ ����� <span class='ll' {majax}>�����</span>
'disabled'=>LL('comm:disabled'), //<p>����������� � ���� ������� ������������� �����������, ������ ��� ������ ������ {1} ���� ��� ����� ��������� ��������� {2}. �� ���� ���-�� ������, �� ������ ������ �������� ��� ������: <a href=mailto:{mail}>{mail}</a>
'disabled_login'=>LL('comm:disabled_login'), //<p>����������� � ���� ������� ���� �������� ��������� ������ ������������, �� ������������� ����������� � ���, ������ ��� ������ ������ {1} ���� ��� ����� ��������� ��������� {2}. �� ���� ���-�� ������, �� ������ ������ �������� ��� ������: <a href=mailto:{mail}>{mail}</a>
'screen'=>LL('comm:screen'), //<p>����������� � ���� ������� ���������� - ��� ����� ����� ������ ��� � ���.
'screen_nofriend'=>LL('comm:screen_nofriend'), //<p>����������� � ���� ������� ����������, �� � ������ (� ����) ��� ����� �������.
'comment_this'=>LL('comm:comment_this'), //<div id='commpresent' class='l' style='font-weight: bold; margin: 20pt; font-size: 16px;' {majax}>�������� �����������</div>
'future'=>LL('comm:future'), //<blockquote style='border: 3px dotted rgb(255,0,0); padding: 2px;'><font size=2>������� ���������� ������� ������, � ��� ������ ������, ��� ������� ��� ������, � �������� �������� ����������.</font></blockquote>
'page'=>LL('comm:page'), //<div style='margin: 50px;'>{0}</div>
'button'=>LL('comm:button'), //<input TYPE='BUTTON' VALUE='�����������{dopload}: {podzamok?|��������|} {idzan}' {majax}>
'nobutton'=>LL('comm:nobutton'), //�����������{dopload} {podzamok?|��������|} {idzan}:
's'=>LL('comm:s'), //<div class=r style='margin: 50px;'>{0}</div>
'pro'=>LL('comm:pro'), //<div id=0>{0}<div></div></div>
'nocomments'=>LL('comm:nocomments'), //<p class=z>������������ ��� ��� ��� ��� ������
'itogo'=>LL('comm:itogo'), //<center><p class=br>����� ������������: {nmas}</p>{u?<p>�������� ������ �������� ����������� - <span {majax}>�������� ���</a>||}</center>
'k'=>LL('comm:k'), //<input type='button' value='{n}' {majax}{u? disabled='disabled'||}>
'addprevnext'=>1 // 1- ���������� ��������� �����
),parse_e_conf($e));

//===================================
// ��� ���� � �������������?
$s='';

$dopload="";

$comments_form=true; // �������� ����� ������ ������������
$comments_knopka=false; // �������� ������ �������� ������������
$comments_list=false; // ������� �������� ������������
$comments_screen=true;

$pro='';

	get_counter($article); // ���������� �������� ��������, ���� �� ����

$comments_timed=(
		$article["counter"] > $N_maxkomm // ���������� ���������� ���������
		|| $article["DateTime"] < time()-86400*$enter_comentary_days // ������� ������ �������
		?true:false);

switch($article["Comment_view"]) { // Comment_view enum('on', 'off', 'rul', 'load', 'timeload')
	case 'on': $comments_knopka=false; $comments_list=true; break;
	case 'off': $comments_knopka=false; $comments_form=false; $comments_list=false; break;
	case 'rul': $comments_knopka=true; $comments_list=true; $load_comments_MS=" AND `rul`='1'";
$dopload=$conf['ostalnye']; // " ���������";
break;
	case 'load': $comments_knopka=true; $comments_list=false; break;
	case 'timeload': $comments_knopka=$comments_timed; $comments_list=!$comments_timed; break;
	}

switch($article["Comment_write"]) { // Comment_write enum('on', 'off', 'friends-only', 'login-only', 'timeoff', 'login-only-timeoff')
	case 'on': $comments_form=true; break;
	case 'off': $comments_form=false;
	    if(isset($conf['socialmedia'])) {

		    if(strstr($article['Body'],'{_NO:autopost:')) {
			$url=site_validate(preg_replace("/^.*\{\_NO\:autopost\:[a-zA-Z]+\:(.+?)\_\}.*$/s","$1",$article['Body']));
		        $s.=mpers($conf['off_socialmedia'],array($url)); // "����������� ���������";
			break;
		    }

	list($net,$user)=explode(':',$conf['socialmedia'],2);
	if(false!==($l=ms("SELECT `id` FROM `socialmedias` WHERE `num`='".$article['num']."' AND `net`='".e($conf['socialmedia'])."'".ANDC(),"_l"))
) {
    include_once $GLOBALS['include_sys'].'protocol/protocols.php';
    $fn=$net.'_url'; if(!function_exists($fn)) return "<font color=red>COMMENT: error protocol: ".h($net)." (".h($fn).")</font>";
    $url=call_user_func($fn,$l,$user);
    $s.=mpers($conf['off_socialmedia'],array($url)); // "����������� ���������";
} else $s.=$conf['off'];
} else $s.=$conf['off']; // "����������� ���������";
break;
	case 'friends-only': $comments_form=$podzamok; if($podzamok)
$s.=$conf['friends_only']; // "�������� ����������� ����� ������";
break;
	case 'login-only': $comments_form=$GLOBALS['IS']['loginlevel']==3?true:false;

$s.=mpers($comments_form?$conf['login_only_done']:$conf['login_only']
,array('0'=>$GLOBALS['IS']['imgicourl'],'majax'=>"onclick=\"ifhelpc('".$GLOBALS['httphost']."login','logz','Login')\""));
// "<p>� ���� ������� �������� ���������� ����� ������ ������������. ������������ ����� �����";
break;
	case 'timeoff': $comments_form=!$comments_timed; if(!$comments_form)
$s.=mpers($conf['disabled'],array('1'=>$enter_comentary_days,'2'=>$N_maxkomm,'mail'=>$GLOBALS['admin_mail']));
// "����������� �����������, ������ ��� ������ ".$enter_comentary_days." ���� ��� ��������� ".$N_maxkomm.". ������ �������� mailto";
break;
	case 'login-only-timeoff': $comments_form=($login?!$comments_timed:false); if(!$comments_form)
$s.=mpers($conf['disabled_login'],array('1'=>$enter_comentary_days,'2'=>$N_maxkomm,'mail'=>$GLOBALS['admin_mail']));
// "����������� ���� ��������� ������������, �� ����������� � ���
break;
	}

switch($article["Comment_screen"]) { // Comment_screen  enum('open', 'screen', 'friends-open')
	case 'open': $comments_screen=false; break;
	case 'screen': $comments_screen=true; if($comments_form)
$s.=$conf['screen']; // "����� ����� ������ ��� � ���";
break;
	case 'friends-open': $comments_screen=!$podzamok; if($comments_form && $podzamok)
$s.=$conf['screen_nofriend']; // "� ������ (� ����) ��� ����� �������.
break;
	}

if(strstr($_SERVER["HTTP_USER_AGENT"],'Yandex') || $GLOBALS['IP']=='78.110.50.100') { // ������ �������
	$comments_form=false; // ��������� ����������� - �� ���� (����� ������� ��������� �����������?)
	$comments_knopka=false; // �������� ������������ - �������� � �������� (������ �� ����� �������� ������, � ����� �� �������������)
	$comments_list=true;
	}

//===================================

if($comments_form) { // ��������� �������� �����������
	$s.= mpers($conf['comment_this'],array('majax'=>"onclick=\"majax('comment.php',{a:'comform',id:0,lev:0,comnu:comnum,dat:".$article['num']."});\"")); // �������� �����������
	if ( $article["DateTime"] > time() ) $s.=$conf['future']; // ������� ���������� ������� ������
}

	$idzan=get_idzan($article['num']);
if($idzan) { // ���� ������ ���� �����������
	if($comments_list) { // ������� �������� ����������
		if(isset($conf['comment_tmpl'])) $GLOBALS['comment_tmpl']=c($conf['comment_tmpl']);
		include_once $GLOBALS['include_sys']."_onecomm.php";
		$pro=load_comments($article,$conf['addprevnext']);
		SCRIPTS("page_onstart.push(\"var c=gethash_c(); if(c){ if(idd(c)) kl(idd(c)); else majax('comment.php',{a:'loadpage_with_id',page:0,id:c,dat:".$article['num']."});}"
."var r=f5_read('hidcom'+num); if(r) { r=r.split(','); for(var i in r) {if(r[i]) hide_comm(r[i]);} }"
."var r=f5_read('ueban'); if(r) { r=r.split(',');"
."var s=[],p=idd(0).getElementsByTagName('DIV'),i,j,u; if(p){for(i=0;i<p.length;i++){ u=1*p[i].getAttribute('unic'); if(!u) continue; if(!s[u]) s[u]=[]; s[u].push(p[i].id); }"
."for(i in r) { u=s[r[i]]; if(u) for(j in u) if(!isNaN(u[j])) hide_comm(u[j]); }"
."}}"
."\");");
	} elseif($comments_knopka) { // ���������� �� ������
		$pro=mpers($conf['page'],array(get_comm_button($article['num'],$dopload,$comments_knopka)) );
		SCRIPTS("page_onstart.push(\"var c=gethash_c(); if(c) majax('comment.php',{a:'loadpage_with_id',page:0,id:c,dat:".$article['num']."});\");");
	}
}

return ($s!=''?mpers($conf['s'],array($s)):'').mpers($conf['pro'],array($pro))
.($GLOBALS['admin']?"".$GLOBALS['msqe']."":'')
;

}
?>