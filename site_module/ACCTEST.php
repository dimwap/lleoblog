<?php

/*  */

function ACCTEST_ajax(){ // if_iphash(RE('iphash'));
	
	$a=RE('a');

    if($a='addadmin') {
	global $acc,$acn;
	ADMA(); 

	$un=RE0('un'); if(!$un) idie("Error unic #".$un." (\"<b>".h(RE('un'))."</b>\") Numeric only.");

	$is=getis($un);
	if($is==false) idie("Error: unic #".$un." not found!");
	$name="<div class=ll onclick=\"majax('login.php',{a:'getinfo',unic:".$unic."})\">".$is['imgicourl']."</div>";
	if($is['loginlevel']<2) idie("������������ ".$name." ������������ �������� ���� �������");


	if(0!=ms("SELECT COUNT(*) FROM `jur` WHERE `unic`='".e($un)."' AND `acc`='".e($acc)."'","_l",0)) idie("���� ������� ��� �����");

// dier(ms("SELECT MAX(`acn`) AS `acn` FROM `jur`",'_l'));
// SELECT MAX(article) AS article FROM shop
// idie("ADD: acn=$acn acc=$acc unic=$un");
//    $max=1*ms("SELECT MAX(`acn`) AS `acn` FROM `jur`",'_l')+1; // ����� ����������
	msq_add('jur',array('acc'=>e($acc),'unic'=>$un,'acn'=>e($acn)));

    dier(ms("SELECT * FROM `jur`"),$GLOBALS['msqe']);

	idie("n=$n".$GLOBALS['msqe']);


	dier($is);
	idie($name." unic #".$un);

	// $pp=ms("SELECT `unic`,`acn` FROM `jur` WHERE `acc`='".e($acc)."'","_a",0);
// acn: int(10) unsigned NOT NULL auto_increment
//acc: varchar(32) NOT NULL
//unic: int(10) unsigned NOT NULL

	idie($a);

    }

	if($a=='mailto') {
		return "

wewew

";
//  ".$mail."
	}

	$acc=RE('acc');
}

function ACCTEST($e) { global $admin,$acc,$acn,$ADM,$IS,$httphost,$db_unic;
$conf=array_merge(array(
'template'=>"<br><a href='{acc_link}'>{acc}</a> (<a href='{acc_link}contents'>{count}</a>)"
),parse_e_conf($e));

	$o="<h1>������� `".h($acc)."`</h1>";
	$pp=ms("SELECT `unic`,`acn` FROM `jur` WHERE `acc`='".e($acc)."'","_a",0);
	if(!sizeof($pp)) return $o."<p>�������� `".h($acc)."` �� ������� �� ����������.
	<br><br>�� ������ ��� �������: ��������� � ����� <span class=ll onclick=\"majax('login.php',{a:'getinfo'})\">������ ��������</span> login: ".h($acc).", ����� ���� ����� �� <a href='".$GLOBALS['wwwhost']."acc'>".$GLOBALS['wwwhost']."acc</a> � ������� ���� ����������� �������.";

	    foreach($pp as $p) { $unic=$p['unic'];
		$is=getis($unic);
		$o.="<div>������� �".$p['acn']." <b>".$acc."</b> - ����� <div class=ll onclick=\"majax('login.php',{a:'getinfo',unic:".$unic."})\">".$is['imgicourl']."</div> (unic #".$unic.")</div>";
	    }


	$o.="<p>�������� ��� ������ ������ unic: <input id='addunic' type=text size=10 value=''> <input type=button value='add admin' onclick=\"majax('module.php',{mod:'ACCTEST',a:'addadmin',un:idd('addunic').value})\">";


	return $o."<p>��� �������: ��� ����� ���������� � ���� ����������� unic = ".$GLOBALS['unic']
	."<p>�� � ��� �� �������, ��� ��� ������� ��� ��� ���� <a href='".$httphost."/login'>��������������</a> ��� ����������, ����� �������� � ���� ������?"
	."<p>���������: ���� � ����� ������� ����� ���� �������� �� �� ������ ���������� ������, ������ �� �� ���������� ������� �������� ".h($acc).". ����� �������� �� ����� �������� ������ �������� ����� ��������� ����� � ����� ������� ����.";
}
?>