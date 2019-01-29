<?php
// rss ������� ��������

$GLOBALS['rssmode']=1;

function RSS($e) { global $wwwcharset,$foto_www_preview,$admin_name,$httphost,$admin_name,$httpsite,
$mypage,$admin_name,$ttl_longsite,$podzamok,$unic;

$conf=array_merge(array(
'length'=>isset($_GET['length'])?intval($_GET['length']):0, // 260
'type'=>"blog", // 'comm'
'rss_skip'=>10,
'image'=>$httphost."design/userpick.jpg",
'{image_w}'=>120,
'{image_h}'=>155,
'generator'=>"Binoniq 3.0",
'charset'=>$wwwcharset,

'title'=>$admin_name,
'title_comm'=>"",
'link_blog'=>$httphost,
'description'=>$admin_name.": ����",
'author'=>$httphost,

'item_blog'=>"
<item>
	<guid isPermaLink='true'>{link}</guid>
	<author>{author}</author>
	<pubDate>{pubdate}</pubDate>
	<link>{link}</link>
	<description>{body}</description>
	<title>{title}</title>
	<comments>{link}</comments>
</item>
",


'item_comm'=>"
<item>
	<guid isPermaLink='true'>{link}</guid>
	<ya:post>{post}</ya:post>
	<pubDate>{pubdate}</pubDate>
	<author>{name}</author>
	<link>{link}</link>
	<title>{title_comm}</title>
	<description>{text}</description>
</item>
",

'template_blog'=>"<?xml version='1.0' encoding='{charset}'?>
<rss version='2.0' xmlns:ya='http://blogs.yandex.ru/yarss/' xmlns:wfw='http://wellformedweb.org/CommentAPI/'>

<channel>
  <title>{title}</title>
  <lastBuildDate>{lastupdate}</lastBuildDate>
  <link>{link_blog}</link>
  <description></description>
  <generator>{generator}</generator>
  <wfw:commentRss>{httphost}rssc</wfw:commentRss>
  <ya:more>{page}?skip={skip}</ya:more>
  <image>
    <url>{image}</url>
    <width>{image_w}</width>
    <height>{image_h}</height>
  </image>

{items}

</channel>
</rss>",

'template_comm'=>"<rss version='2.0' xmlns:ya='http://blogs.yandex.ru/yarss/'>

<channel>
	<title>{title}: comments</title>
	<link>{link_blog}</link>
	<generator>{generator}</generator>
	<lastBuildDate>{lastupdate}</lastBuildDate>
	<ya:more>{more}</ya:more>
	<category>ya:comments</category>

{items}

</channel>
</rss>"

),parse_e_conf($e));

if(c0($conf['body'])!='') $conf['template']=$conf['body'];
else $conf['template']=$conf['template_'.$conf['type']];

$subst1=array("{foto_www_preview}");
$subst2=array($foto_www_preview);

$skip=intval($_GET['skip']);
$s='';

if($conf['type']=='blog') {
// ============= page ================
include_once $GLOBALS['include_sys']."_onetext.php"; // ��������� �������

$pp=ms("SELECT `Date`,`Body`,`Header`,`DateUpdate`,`Access`,`num` FROM `dnevnik_zapisi` ".WHERE("`DateDatetime`!=0").ANDC()." ORDER BY `Date` DESC LIMIT ".$skip.",".intval($conf['rss_skip']),"_a");

$lastupdate=0; foreach($pp as $p) {
	$lastupdate = max($lastupdate,$p["DateUpdate"]);
	$link=$httphost.$p["Date"].".html"; // ������ ������ �� ������

		$p['Body']=RSS_zaban($p['Body']); // ���������� ����������
		$Body=onetext($p,1); // ���������� ����� ������� ��� ��������
		if($conf['length']) $Body=RSSZ_mode1($Body,$link,$conf); // ���� � ���������� ������� �� ������ ������ RSS
		$Body=zamok($p['Access']).$Body; // �������� �������� ���������
		$Body=str_replace($subst1,$subst2,$Body);

	$Header=$p["Date"].($p["Header"]?" - ".$p["Header"]:"");

$s .= mper($conf['item_blog'],array_merge($conf,array(
'pubdate'=>date("r", $p["DateUpdate"]),
'title'=>h($Header),
'link'=>$link,
'body'=>h($Body),
'author'=>$conf['author']
)));

}
// ============= page ================
} elseif($conf['type']=='comm') {

// ����� ������������ Date - num, ���� �� ����� ������ ��� �� ������ ����� ��� ��?! ����������!!!
//$e=ms("SELECT `num`,`Date` FROM `dnevnik_zapisi` WHERE 1=1".ANDC(),"_a",$ttl_longsite);
//$d=array(); foreach($e as $l) $d[$l['num']]=getlink($l['Date']); unset($e);

$whe=array();
if(!$podzamok) $whe[]="(c.`scr`='0' OR c.`unic`='".$unic."')";
if(isset($_GET['unic'])) $whe[]="c.`unic`='".e($_GET['unic'])."'";
if(isset($_GET['name'])) $whe[]="c.`Name`='".e($_GET['name'])."'";

$pp=ms("SELECT c.`id`,c.`Text`,c.`Name`,c.`Parent`,c.`Time`,c.`DateID`,z.`Date`
FROM `dnevnik_comm` AS c LEFT JOIN `dnevnik_zapisi` AS z ON c.`DateID`=z.`num`
".WHERE(implode(' AND ',$whe))." ORDER BY c.`Time` DESC LIMIT ".$skip.",".intval($conf['rss_skip']));

foreach($pp as $p) { $post=$p['Date'];

$s .= mper($conf['item_comm'],array_merge($conf,array(
'post'=>$post,
'link'=>$post."#".$p['id'],
'pubdate'=>date("r", $p['Time']),
'parent'=>($p['Parent']!=0?"        <ya:parent>".$post."#".$p['Parent']."</ya:parent>":''),
'name'=>h(strtr($p['Name'],"\r","")),
'text'=>h(strtr($p['Text'],"\r","")),
'title_comm'=>$conf['title_comm'],
)));

}

} else return "unknown type `".h($conf['type'])."`<br>may be: `blog` (blog items) or `comm` (comments)";


//--------------------

$s=mper($conf['template'],array_merge($conf,array(
'lastupdate'=>date("r",$lastupdate),
'httphost'=>$httphost,
'page'=>$httpsite.$mypage,
'skip'=>intval($skip+$conf['rss_skip']),
'items'=>$s,
)));

if($conf['charset']!=$wwwcharset) $s=iconv($wwwcharset,$conf['charset']."//IGNORE",$s);

ob_clean(); ob_end_clean();
header("Content-Type: text/xml; charset='".$conf['charset']."'");
check_if_modified($lastupdate,"$lastupdate"); // ����� ��������� ����������� (��� �� ��� ETag)
die($s);

}
//=========================================================================================================

// ��������� ������� ��������� �����������
function check_if_modified($date, $etag = NULL) { $cache = NULL;
        if( isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) $cache = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $date;
        if( $cache !== false && isset( $_SERVER['HTTP_IF_NONE_MATCH'] ) ) {
                $cache = $_SERVER['HTTP_IF_NONE_MATCH'] == '*' || isset( $etag )
                        && in_array( '"'.$etag.'"', explode( ', ',$_SERVER['HTTP_IF_NONE_MATCH'] ) );
                }
        if($cache) { header('HTTP/1.1 304 Not Modified'); ob_clean(); exit(); }
        else {
                header( 'Last-Modified: '.date( DATE_RFC822, $date ) );
                if( isset( $etag ) ) header( 'ETag: "'.$etag.'"' );
        }
}


// ������� ���������� RSS, ���� �� ��������

function RSS_zaban($s) { global $admin,$IP,$BRO; 	// ���� ��� ���������� ������, ���� � ������
	if( $IP=='78.46.74.53' // http://feedex.net/view/lleo.aha.ru/dnevnik/rss.xml
		|| strstr($BRO,'eedjack') // BRO='Feedjack 0.9.10 - http://www.feedjack.org/'
		// || $_SERVER["REMOTE_ADDR"]=='79.165.191.215' // Feed43.com? http://feeds.feedburner.com/lleo ?
//		|| strstr($_SERVER["HTTP_USER_AGENT"],'Wget/') // � ����� ������ ������!
		|| $IP=='140.113.88.218' || strstr($BRO,'Yahoo Pipes')
	) return "��� ��� ������ ����� ������� <a href=".mkna("�������� RSS!",
"�� ��������� ����������, ��� ��� ���� ������ � ������ � �� ��������� ������ RSS ���������� ���������.",
"������ � ������� ������� ��-�����������.").">��������� �����</a>";
	return $s;
}


function RSSZ_mode1($s,$link,$conf) { global $admin,$BRO,$IP;
	$sim=strip_tags(html_entity_decode($s)); // ������� ��� ����
	// $sim=ereg_replace("{_[^}]*_}",'',$sim); // ������� ��� ������ � �������� �������
        $sim=preg_replace("/\{_[^\}]+_\}/si",'',$sim); // ������� ��� ������ � �������� ������� ���� �������� (!)
	$bukv=round(((strlen($sim))+99 )/100)*100;
	$sim=trim(preg_replace("/^(.{".intval($conf['length'])."260}[^\.\?\!]*[\.\!\?]).*$/si","$1",$sim))
	."... [<a href='$link'>������ ���������: �������� $bukv ��������</a>]\n\n";
	// if(strstr($s,'<img')) $sim .= " + �������� ��� �����";
	// if(strstr($s,'<script')) $sim .= " + ������� �����-��";
	// if(strstr($s,'<object') || strstr($s,'<OBJECT')) $sim .= " + ���� �������� (�����, ����� ��� ������?)";
	// $sim .= ".";

	// ���� ��� ������

	if( strstr($BRO,'Yandex') || $IP=='78.110.50.100' ) return $sim."
\n<p><b>��������� ������ ��� ������� ����� RSS-����� �������? ����� ���� ������ ��������� �������
��� ���������� � ������ - � ������ ��������, ��� �����, ��������, �������, �������, ��������, ����������� � �������.
��������� ������ ����� �������� �� ������ �������� ������ �� ���� ����� (������� ������� <a href=".$httphost."/about_rss>�����</a>).</b>";

	// ���� ��� ����� ���������� ��

	// 204.9.177.18 (): , 'LiveJournal.com (webmaster@livejournal.com; for http://www.livejournal.com/users/lleo_run/; 488 readers)'
	if(strstr($BRO,'LiveJournal.com') )
	return $sim."\n�������� ��-����������! ���������� ����������� ������ �� ���� �����, ����� � �� �� �����.";

	return $sim;
}

function mkna($name,$prichina,$delat) { // ������� ������ ������ �����
	$stroka=$name."%".$prichina."%".$delat;	$stroka=base64_encode($stroka);
	$stroka=str_replace("=","",$stroka); $stroka=str_replace("/","-",$stroka);
	return "http://natribu.org/?".$stroka;
}

?>