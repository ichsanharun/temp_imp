BEGIN
   IF NEW.tipe = 'IN' THEN
        BEGIN
        	    UPDATE barang_stock set qty_avl=qty_avl + NEW.qty, qty_stock = qty_stock+NEW.qty, harga = NEW.nilai_barang
                       WHERE id_barang= NEW.id_barang AND kdcab= NEW.kdcab;
        END;
   ELSE
        BEGIN
         	UPDATE barang_stock set qty_avl=qty_avl - NEW.qty, qty_stock = qty_stock-NEW.qty, harga = NEW.nilai_barang
  	WHERE id_barang= NEW.id_barang AND kdcab= NEW.kdcab;
        END;
    END IF;

END
