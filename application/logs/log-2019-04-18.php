<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2019-04-18 02:35:50 --> Severity: Notice --> Use of undefined constant php - assumed 'php' D:\Ampps\www\Sentral\imp\application\modules\users\views\login_animate.php 6
ERROR - 2019-04-18 10:45:15 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 43
ERROR - 2019-04-18 10:45:15 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 62
ERROR - 2019-04-18 10:45:15 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 126
ERROR - 2019-04-18 10:45:15 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 333
ERROR - 2019-04-18 10:45:15 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 360
ERROR - 2019-04-18 10:45:15 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 382
ERROR - 2019-04-18 10:45:15 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 500
ERROR - 2019-04-18 10:45:22 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 333
ERROR - 2019-04-18 10:45:22 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 360
ERROR - 2019-04-18 10:45:22 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 382
ERROR - 2019-04-18 10:45:31 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 43
ERROR - 2019-04-18 10:45:31 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 62
ERROR - 2019-04-18 10:45:31 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 126
ERROR - 2019-04-18 10:45:31 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 333
ERROR - 2019-04-18 10:45:31 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 360
ERROR - 2019-04-18 10:45:31 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 382
ERROR - 2019-04-18 10:45:31 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 500
ERROR - 2019-04-18 10:45:38 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 500
ERROR - 2019-04-18 03:45:39 --> 404 Page Not Found: /index
ERROR - 2019-04-18 06:32:49 --> Severity: Notice --> Use of undefined constant php - assumed 'php' D:\Ampps\www\Sentral\imp\application\modules\users\views\login_animate.php 6
ERROR - 2019-04-18 06:32:56 --> Severity: Notice --> Use of undefined constant php - assumed 'php' D:\Ampps\www\Sentral\imp\application\modules\users\views\login_animate.php 6
ERROR - 2019-04-18 13:44:50 --> Query error: Duplicate entry 'W033' for key 'PRIMARY' - Invalid query: INSERT INTO `barang_koli_warna` (`id_koli_warna`, `koli_warna`, `sts_aktif`) VALUES ('W033', 'test warna', 'aktif')
ERROR - 2019-04-18 07:00:33 --> Severity: Runtime Notice --> Declaration of Barang_koli_model::delete() should be compatible with BF_Model::delete($id = NULL) D:\Ampps\www\Sentral\imp\application\modules\koli\models\Barang_koli_model.php 178
ERROR - 2019-04-18 07:01:12 --> Severity: Runtime Notice --> Declaration of Barang_koli_model::delete() should be compatible with BF_Model::delete($id = NULL) D:\Ampps\www\Sentral\imp\application\modules\koli\models\Barang_koli_model.php 178
ERROR - 2019-04-18 14:03:18 --> Severity: Notice --> Undefined variable: datkota D:\Ampps\www\Sentral\imp\application\modules\cabang\views\cabang_form.php 82
ERROR - 2019-04-18 14:03:18 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\cabang\views\cabang_form.php 82
ERROR - 2019-04-18 07:40:35 --> Severity: Parsing Error --> syntax error, unexpected ';' D:\Ampps\www\Sentral\imp\application\modules\koli\controllers\Koli.php 345
ERROR - 2019-04-18 15:02:40 --> Query error: Unknown column 'barang_stock.qty' in 'field list' - Invalid query: SELECT `barang_stock`.*, `barang_jenis`.`nm_jenis`, `barang_group`.`nm_group`, `barang_stock`.`satuan` AS `setpcs`, `barang_stock`.`qty` as `qty`
FROM `barang_stock`
LEFT JOIN `barang_group` ON MID(barang_stock.id_barang,3,2) = barang_group.id_group
LEFT JOIN `barang_jenis` ON LEFT(barang_stock.id_barang,2) = barang_jenis.id_jenis
WHERE `barang_stock`.`deleted` =0
AND `barang_stock`.`kategori` = 'set'
AND `barang_stock`.`kdcab` = '101'
GROUP BY `barang_stock`.`id_barang`
ORDER BY `barang_stock`.`nm_barang` ASC
ERROR - 2019-04-18 09:14:14 --> 404 Page Not Found: /index
ERROR - 2019-04-18 16:14:35 --> Severity: Error --> Call to undefined method CI_DB_mysqli_result::results() D:\Ampps\www\Sentral\imp\application\modules\barang_packing\controllers\Barang_packing.php 93
ERROR - 2019-04-18 16:19:16 --> Severity: Error --> Call to undefined method CI_DB_mysqli_result::results() D:\Ampps\www\Sentral\imp\application\modules\barang_packing\controllers\Barang_packing.php 93
ERROR - 2019-04-18 16:21:52 --> Severity: Error --> Call to undefined method CI_DB_mysqli_result::results() D:\Ampps\www\Sentral\imp\application\modules\barang_packing\controllers\Barang_packing.php 93
ERROR - 2019-04-18 09:38:59 --> 404 Page Not Found: /index
ERROR - 2019-04-18 17:12:36 --> Severity: 4096 --> Object of class Auth could not be converted to string D:\Ampps\www\Sentral\imp\application\modules\barang_packing\controllers\Barang_packing.php 93
