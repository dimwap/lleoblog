<?php // ��������� �����

include "../config.php"; include $include_sys."_autorize.php";

otprav("helps('random','��������� ����� <b>".rand(RE("min"),RE("max"))."</b>, ���!');");

?>