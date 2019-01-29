<?php

/*
        $s="DELETE FROM $tb WHERE $a $u";
// if(msq_pole($table,$pole)===false) msq("ALTER TABLE `".$table."` ADD `".$pole."` ".$s
msq_pole($tb,$pole) // ���������, ���������� �� ����� ���� � ������� $tb
 // ���������, ���������� �� ����� �������
msq_index($tb,$index) // ���������, ���������� �� ����� ������
// �������� ���� � �������      function msq_change_pole($table,$pole,$s)
// �������� ���� �������        function msq_add_pole($table,$pole,$s)
// ������� ���� �� �������      function msq_del_pole($table,$pole)
// �������� ������ � �������    function msq_add_index($table,$pole,$s)
// ������� ������ �� �������    function msq_del_index($table,$pole)
// ������� �������              function msq_add_table($table,$s)
// ������� �������              function msq_del_table($table,$text)
*/


function installmod_init() {
    if(!msq_table('jur')) return false;
    $pp=ms("SHOW INDEX FROM `jur`","_a",0); if(sizeof($pp)>2) return false;
    return "�������� ������ `jur`";
}

function installmod_do() {

//   $pp=ms("SHOW INDEX FROM `jur`","_a",0); dier($pp);
/*

  `time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '����� ���������� ����������',

    [0] => Array
        (
            [Table] => jur
            [Non_unique] => 0
            [Key_name] => PRIMARY
            [Seq_in_index] => 1
            [Column_name] => acn
            [Collation] => A
            [Cardinality] => 389
            [Sub_part] => 
            [Packed] => 
            [Null] => 
            [Index_type] => BTREE
            [Comment] => 
            [Index_comment] => 
        )

    [1] => Array
        (
            [Table] => jur
            [Non_unique] => 1
            [Key_name] => acc
            [Seq_in_index] => 1
            [Column_name] => acc
            [Collation] => A
            [Cardinality] => 389
            [Sub_part] => 
            [Packed] => 
            [Null] => 
            [Index_type] => BTREE
            [Comment] => 
            [Index_comment] => 
        )




Array
(
    [0] => Array
        (
            [Table] => jur
            [Non_unique] => 0
            [Key_name] => PRIMARY
            [Seq_in_index] => 1
            [Column_name] => acn
            [Collation] => A
            [Cardinality] => 
            [Sub_part] => 
            [Packed] => 
            [Null] => 
            [Index_type] => BTREE
            [Comment] => 
            [Index_comment] => 
        )

    [1] => Array
        (
            [Table] => jur
            [Non_unique] => 0
            [Key_name] => PRIMARY
            [Seq_in_index] => 2
            [Column_name] => unic
            [Collation] => A
            [Cardinality] => 389
            [Sub_part] => 
            [Packed] => 
            [Null] => 
            [Index_type] => BTREE
            [Comment] => 
            [Index_comment] => 
        )

    [2] => Array
        (
            [Table] => jur
            [Non_unique] => 1
            [Key_name] => acc
            [Seq_in_index] => 1
            [Column_name] => acc
            [Collation] => A
            [Cardinality] => 389
            [Sub_part] => 
            [Packed] => 
            [Null] => 
            [Index_type] => BTREE
            [Comment] => 
            [Index_comment] => 
        )

)

*/

    if(!msq_pole('jur','time')) msq_add_pole('jur','time',"timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT '����� ���������� ���������� �������'");
    msq("ALTER TABLE `jur` CHANGE `acn` `acn` int(10) unsigned NOT NULL COMMENT '����� �������'");
    msq("ALTER TABLE `jur` DROP PRIMARY KEY");
    msq("ALTER TABLE `jur` ADD PRIMARY KEY(`acn`,`unic`)");
    return "�������� ������� `jur`, ������� ������";
}

?>