<?php /* ��� ����� ���������

��� ����� ������ ���������� ���������� (���������� PHP, � �� CSS - ����� ����������� ��� ��������� � �����������).

�������� ��������� {_ZAG:������� ������ - 2_}.
*/

function ZAG($e) {
    if(!function_exists('mb_strtoupper')) return $e;
    return mb_strtoupper($e,$GLOBALS['wwwcharset']);
}
?>