<?php /* ������� �����, ������������������� �� ������

������ ���������� ��� ���������� ������ �� �����, �������� � ��� 'a':

��� <a{_IMGG: http://lleo.me/dnevnik/2012/11/AMR/Sharp-IS01.jpg � ��� ����� ������� �������� �����,<br>������� ����� ������� ��� ���������.<br>� ��� ����� ������������ ����� ��������������._}>��� �����</a>!

*/

function IMGG($e) { if(strstr($e,' ')) { list($e,$text)=explode(' ',$e,2);
$text=' title="'.$text.'"'; } else $text='';
	return " onclick='return bigfoto(this);' href='".h($e)."'".$text;
}

?>