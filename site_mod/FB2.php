<?php /*

������ ��� ���������� ������� � FB2 (��������� �������������):

{_FB2:
BUTTON=>Download FB2
ALT=������� ��� �������� ��� ������� � ������� FB2
template=<form method='post' action='{action}'><input alt='{ALT}' type='submit' value='{BUTTON}'></form>
_}
*/

function FB2($e) {
    $cf=array_merge(array(
	'BUTTON'=>'Download FB2',
	'ALT'=>"������� ��� �������� ��� ������� � ������� FB2",
	'template'=>"<form method='post' action='{action}'><input alt='{ALT}' type='submit' value='{BUTTON}'></form>"
    ),parse_e_conf($e));
    $cf['action']=acc_link($GLOBALS['acc']).'fb2?'.$GLOBALS['article']['num'];
    return mpers($cf['template'],$cf);
}

?>