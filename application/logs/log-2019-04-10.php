<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2019-04-10 02:25:03 --> Severity: Notice --> Use of undefined constant php - assumed 'php' D:\Ampps\www\Sentral\imp\application\modules\users\views\login_animate.php 6
ERROR - 2019-04-10 02:25:07 --> Severity: Notice --> Use of undefined constant php - assumed 'php' D:\Ampps\www\Sentral\imp\application\modules\users\views\login_animate.php 6
ERROR - 2019-04-10 11:51:55 --> Query error: Not unique table/alias: 'barang_group' - Invalid query: SELECT `barang_stock`.*, `barang_jenis`.`nm_jenis`, `barang_group`.`nm_group`, `barang_stock`.`satuan` AS `setpcs`, `barang_master`.`qty` as `qty`
FROM `barang_stock`
LEFT JOIN `barang_master` ON `barang_stock`.`id_barang` = `barang_master`.`id_barang`
LEFT JOIN `barang_group` ON `barang_group`.`id_group` = `barang_master`.`id_group`
LEFT JOIN `barang_jenis` ON `barang_stock`.`jenis` = `barang_jenis`.`id_jenis`
LEFT JOIN `barang_group` ON `barang_group`.`id_group` = MID(barang_stock.id_barang,2,2)
LEFT JOIN `barang_jenis` ON `barang_stock`.`jenis` = LEFT(barang_stock.id_barang,2)
WHERE `barang_stock`.`deleted` =0
AND `barang_stock`.`kategori` = 'set'
AND `barang_stock`.`kdcab` = '102'
GROUP BY `barang_stock`.`id_barang`
ORDER BY `barang_stock`.`nm_barang` ASC
