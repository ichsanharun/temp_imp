<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2019-03-13 02:17:52 --> Severity: Notice --> Use of undefined constant php - assumed 'php' D:\Ampps\www\Sentral\imp\application\modules\users\views\login_animate.php 6
ERROR - 2019-03-13 03:02:51 --> 404 Page Not Found: ../modules/purchaserequest/controllers/Purchaserequest/new_create
ERROR - 2019-03-13 03:02:55 --> 404 Page Not Found: ../modules/purchaserequest/controllers/Purchaserequest/new_create
ERROR - 2019-03-13 10:03:12 --> Query error: Unknown column 'm.id_supplier' in 'field list' - Invalid query: SELECT
                s.id_barang,
                s.nm_barang,
                s.harga,
                s.satuan,
                s.qty_stock,
                s.qty_avl,
                m.cbm_each,
                m.gross_weight,
                m.id_supplier
                FROM
                barang_stock s INNER JOIN barang_master m
                ON s.id_barang = m.id_barang
                WHERE kdcab='102' AND id_supplier = 'HEBC001'
ERROR - 2019-03-13 03:34:13 --> 404 Page Not Found: ../modules/purchaserequest/controllers/Purchaserequest/new_create
