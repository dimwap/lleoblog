<?php /* DIR

������� ������ �����
{_DIR: temp/novygod _}
*/

function DIRLIST($e) { if($e=='') $e=$GLOBALS['article']['Date'];
    $u=rpath($GLOBALS['filehost'].$e);
    if(is_file($u.'/.htaccess')) return ".htaccess";
    $k=strlen($GLOBALS['filehost']); $a=glob($u.'/*'); foreach($a as $n=>$l) {
	if(!is_file($l)) { unset($a[$n]); continue; } // ������ �����
	$l='/'.substr($l,$k);
//	$l='/'.dirname($l).'/pre/'.basename($l);
	$a[$n]=$l;
    }
    return implode("\n",$a);
}
?>