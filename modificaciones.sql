ALTER TABLE Productos ADD COLUMN VtaDetId_res INT(11) NULL AFTER VtaDetId;

SET SQL_SAFE_UPDATES = 0;

UPDATE Productos SET VtaDetId_res = VtaDetId;

SET SQL_SAFE_UPDATES = 1;