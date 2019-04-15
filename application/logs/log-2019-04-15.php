<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2019-04-15 01:52:49 --> Severity: Warning --> mysqli::real_connect(): (HY000/2002): No connection could be made because the target machine actively refused it.
 D:\Ampps\www\Sentral\imp\system\database\drivers\mysqli\mysqli_driver.php 202
ERROR - 2019-04-15 01:52:49 --> Severity: Warning --> mysqli::real_connect(): (HY000/2002): No connection could be made because the target machine actively refused it.
 D:\Ampps\www\Sentral\imp\system\database\drivers\mysqli\mysqli_driver.php 202
ERROR - 2019-04-15 01:52:49 --> Unable to connect to the database
ERROR - 2019-04-15 01:52:49 --> Unable to connect to the database
ERROR - 2019-04-15 01:53:07 --> Severity: Notice --> Use of undefined constant php - assumed 'php' D:\Ampps\www\Sentral\imp\application\modules\users\views\login_animate.php 6
ERROR - 2019-04-15 09:43:36 --> Query error: Unknown column 'status_cabang' in 'where clause' - Invalid query: SELECT * FROM trans_po_header WHERE status_cabang != "LUNAS" AND kdcab = 102
ERROR - 2019-04-15 10:01:27 --> Query error: Column 'harga_satuan' cannot be null - Invalid query: INSERT INTO `trans_pr_detail` (`no_pr`, `id_barang`, `nm_barang`, `satuan`, `qty_pr`, `harga_satuan`, `sub_total_pr`, `created_on`, `created_by`) VALUES ('102-PR-19A00001', 'HECT035', 'CT IMP K21 WHITE COFFEE TABLE', 'SET', '12', NULL, 0, '2019-04-15 10:01:27', '2019-04-15 10:01:27')
ERROR - 2019-04-15 06:35:00 --> Severity: Notice --> Use of undefined constant php - assumed 'php' D:\Ampps\www\Sentral\imp\application\modules\users\views\login_animate.php 6
ERROR - 2019-04-15 06:44:42 --> Severity: Notice --> Use of undefined constant php - assumed 'php' D:\Ampps\www\Sentral\imp\application\modules\users\views\login_animate.php 6
ERROR - 2019-04-15 13:44:54 --> Query error: Unknown column 't1.pusat' in 'where clause' - Invalid query: SELECT `t1`.*
FROM `menus` as `t1`
LEFT JOIN `menus` as `t2` ON `t1`.`id` = `t2`.`parent_id`
WHERE `t1`.`parent_id` =0
AND `t1`.`group_menu` = 1
AND `t1`.`status` = 1
AND `t1`.`pusat` =0
GROUP BY `t1`.`id`
ORDER BY `t1`.`order` ASC
ERROR - 2019-04-15 16:01:48 --> Severity: Warning --> Division by zero D:\Ampps\www\Sentral\imp\application\modules\receiving\views\konfirmasi.php 156
ERROR - 2019-04-15 16:01:48 --> Severity: Warning --> Division by zero D:\Ampps\www\Sentral\imp\application\modules\receiving\views\konfirmasi.php 156
ERROR - 2019-04-15 16:01:48 --> Severity: Warning --> Division by zero D:\Ampps\www\Sentral\imp\application\modules\receiving\views\konfirmasi.php 156
ERROR - 2019-04-15 16:01:48 --> Severity: Warning --> Division by zero D:\Ampps\www\Sentral\imp\application\modules\receiving\views\konfirmasi.php 156
ERROR - 2019-04-15 16:01:48 --> Severity: Warning --> Division by zero D:\Ampps\www\Sentral\imp\application\modules\receiving\views\konfirmasi.php 156
ERROR - 2019-04-15 16:01:48 --> Severity: Warning --> Division by zero D:\Ampps\www\Sentral\imp\application\modules\receiving\views\konfirmasi.php 156
ERROR - 2019-04-15 16:01:48 --> Severity: Warning --> Division by zero D:\Ampps\www\Sentral\imp\application\modules\receiving\views\konfirmasi.php 156
ERROR - 2019-04-15 16:03:08 --> Query error: Unknown column 'no_receive' in 'field list' - Invalid query: SELECT MAX(no_receive) as max_id
      FROM
      trans_receive WHERE LEFT(no_receive,3)='101' AND no_receive LIKE '%101-RC-19D%'
ERROR - 2019-04-15 16:07:59 --> Query error: Duplicate entry '101-PO-19A00001-DO-000061-4-2019' for key 'Kode AR' - Invalid query: INSERT INTO `ar_cabang` (`bln`, `debet`, `id_supplier`, `kdcab`, `kredit`, `nm_supplier`, `no_do`, `no_po`, `saldo_akhir`, `saldo_awal`, `tgl_receive`, `thn`) VALUES ('1',1377608428,'CN006','101',0,'BAZHOU FUJIA FURNITURE. CO, LTD','DO-000061','101-PO-19A00001',1377608428,0,'2019-01-15','2019'), ('2',0,'CN006','101',0,'BAZHOU FUJIA FURNITURE. CO, LTD','DO-000061','101-PO-19A00001',1377608428,1377608428,'2019-01-15','2019'), ('3',0,'CN006','101',0,'BAZHOU FUJIA FURNITURE. CO, LTD','DO-000061','101-PO-19A00001',1377608428,1377608428,'2019-01-15','2019'), ('4',0,'CN006','101',0,'BAZHOU FUJIA FURNITURE. CO, LTD','DO-000061','101-PO-19A00001',1377608428,1377608428,'2019-01-15','2019')
