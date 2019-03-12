<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2019-03-11 02:13:10 --> Severity: Notice --> Use of undefined constant php - assumed 'php' D:\Ampps\www\Sentral\imp\application\modules\users\views\login_animate.php 6
ERROR - 2019-03-11 11:47:44 --> Severity: Notice --> Undefined variable: bayar_dollar D:\Ampps\www\Sentral\imp\application\modules\hutang\controllers\Hutang.php 48
ERROR - 2019-03-11 11:47:44 --> Severity: Notice --> Undefined property: CI::$Model_hutang D:\Ampps\www\Sentral\imp\application\third_party\MX\Loader.php 310
ERROR - 2019-03-11 11:47:44 --> Severity: Error --> Call to a member function cek_data() on null D:\Ampps\www\Sentral\imp\application\modules\hutang\views\bayar_form.php 136
ERROR - 2019-03-11 11:47:59 --> Severity: Notice --> Undefined variable: bayar_dollar D:\Ampps\www\Sentral\imp\application\modules\hutang\controllers\Hutang.php 48
ERROR - 2019-03-11 11:47:59 --> Severity: Notice --> Undefined property: CI::$Model_hutang D:\Ampps\www\Sentral\imp\application\third_party\MX\Loader.php 310
ERROR - 2019-03-11 11:47:59 --> Severity: Error --> Call to a member function cek_data() on null D:\Ampps\www\Sentral\imp\application\modules\hutang\views\bayar_form.php 136
ERROR - 2019-03-11 11:48:06 --> Severity: Notice --> Undefined variable: bayar_dollar D:\Ampps\www\Sentral\imp\application\modules\hutang\controllers\Hutang.php 48
ERROR - 2019-03-11 11:48:06 --> Severity: Notice --> Undefined property: CI::$Model_hutang D:\Ampps\www\Sentral\imp\application\third_party\MX\Loader.php 310
ERROR - 2019-03-11 11:48:06 --> Severity: Error --> Call to a member function cek_data() on null D:\Ampps\www\Sentral\imp\application\modules\hutang\views\bayar_form.php 136
ERROR - 2019-03-11 11:48:25 --> Severity: Notice --> Undefined property: CI::$Model_hutang D:\Ampps\www\Sentral\imp\application\third_party\MX\Loader.php 310
ERROR - 2019-03-11 11:48:25 --> Severity: Error --> Call to a member function cek_data() on null D:\Ampps\www\Sentral\imp\application\modules\hutang\views\bayar_form.php 136
ERROR - 2019-03-11 13:18:56 --> Severity: Parsing Error --> syntax error, unexpected 'date' (T_STRING) D:\Ampps\www\Sentral\imp\application\modules\hutang\views\bayar_form.php 178
ERROR - 2019-03-11 13:23:52 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 43
ERROR - 2019-03-11 13:23:52 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 62
ERROR - 2019-03-11 13:23:52 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 126
ERROR - 2019-03-11 13:23:52 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 333
ERROR - 2019-03-11 13:23:52 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 360
ERROR - 2019-03-11 13:23:52 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 382
ERROR - 2019-03-11 13:23:52 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 500
ERROR - 2019-03-11 13:24:04 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 333
ERROR - 2019-03-11 13:24:04 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 360
ERROR - 2019-03-11 13:24:04 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Ampps\www\Sentral\imp\application\modules\barang\views\barang_form.php 382
ERROR - 2019-03-11 09:21:24 --> Severity: Notice --> Use of undefined constant php - assumed 'php' D:\Ampps\www\Sentral\imp\application\modules\users\views\login_animate.php 6
ERROR - 2019-03-11 16:21:30 --> Query error: Column 'kdcab' in where clause is ambiguous - Invalid query: SELECT *
FROM `pembayaran_piutang`
LEFT JOIN `trans_invoice_header` ON `trans_invoice_header`.`no_invoice`=`pembayaran_piutang`.`no_invoice`
WHERE `kd_pembayaran` = 'NOT NULL'
AND `kdcab` = '102'
ORDER BY `nomor` ASC
ERROR - 2019-03-11 16:21:53 --> Query error: Unknown column 'nomor' in 'order clause' - Invalid query: SELECT *
FROM `pembayaran_piutang`
LEFT JOIN `trans_invoice_header` ON `trans_invoice_header`.`no_invoice`=`pembayaran_piutang`.`no_invoice`
WHERE `kd_pembayaran` = 'NOT NULL'
AND `pembayaran_piutang`.`kdcab` = '102'
ORDER BY `nomor` ASC
ERROR - 2019-03-11 16:23:46 --> Query error: Unknown column 'pembayaran_piutan.kdcab' in 'where clause' - Invalid query: SELECT *
FROM `pembayaran_piutang`
LEFT JOIN `trans_invoice_header` ON `trans_invoice_header`.`no_invoice`=`pembayaran_piutang`.`no_invoice`
WHERE `kd_pembayaran` = 'NOT NULL'
AND `pembayaran_piutan`.`kdcab` = '102'
ORDER BY `kd_pembayaran` ASC
ERROR - 2019-03-11 16:25:02 --> Query error: Unknown column 'pembayaran_piutan.kdcab' in 'where clause' - Invalid query: SELECT *
FROM `pembayaran_piutang`
LEFT JOIN `trans_invoice_header` ON `trans_invoice_header`.`no_invoice`=`pembayaran_piutang`.`no_invoice`
WHERE 0 = 'kd_pembayaran IS NOT NULL'
AND `pembayaran_piutan`.`kdcab` = '102'
ORDER BY `kd_pembayaran` ASC
ERROR - 2019-03-11 16:38:30 --> Query error: Unknown column 'pembayaran_piutan.kdcab' in 'where clause' - Invalid query: SELECT *
FROM `pembayaran_piutang`
LEFT JOIN `trans_invoice_header` ON `trans_invoice_header`.`no_invoice`=`pembayaran_piutang`.`no_invoice`
WHERE 0 = 'kd_pembayaran IS NOT NULL'
AND `pembayaran_piutan`.`kdcab` = '102'
AND 1 = 'tgl_pembayaran LIKE \"%2019-03%\"'
ORDER BY `kd_pembayaran` ASC
ERROR - 2019-03-11 16:39:52 --> Query error: Unknown column 'pembayaran_piutan.kdcab' in 'where clause' - Invalid query: SELECT *
FROM `pembayaran_piutang`
LEFT JOIN `trans_invoice_header` ON `trans_invoice_header`.`no_invoice`=`pembayaran_piutang`.`no_invoice`
WHERE 0 = 'kd_pembayaran IS NOT NULL'
AND `pembayaran_piutan`.`kdcab` = '102'
AND `tgl_pembayaran` LIKE '%2019-03%'
ORDER BY `kd_pembayaran` ASC
ERROR - 2019-03-11 11:04:07 --> Severity: Parsing Error --> syntax error, unexpected '->' (T_OBJECT_OPERATOR) D:\Ampps\www\Sentral\imp\application\modules\reportsummary\controllers\Reportsummary.php 58
ERROR - 2019-03-11 18:04:21 --> Severity: Parsing Error --> syntax error, unexpected end of file D:\Ampps\www\Sentral\imp\application\modules\reportsummary\views\list.php 169
