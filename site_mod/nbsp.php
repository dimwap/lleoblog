<?php /* ������ �������� ������

������� ��� ��������� ������ �������� � ������ �����. ��� � ������ ���� �������� �������.
��� ������� � ������ ������ ����� �������� �� &nbsp;

{_nbsp:
�� �
     ����������
               ����� ��
                        ����
_}
*/



function nbsp($e) { 
	return preg_replace_callback("/\n +/si","nbsp_cb",$e);
}

function nbsp_cb($t) { return str_replace(' ','&nbsp;',$t[0]); }

?>