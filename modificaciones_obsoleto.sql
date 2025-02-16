-- Primeras
    ALTER TABLE productos
    ADD ProCod VARCHAR(50);

    INSERT INTO `estados`(`idEstados`, `Descripcion`) VALUES (8, 'No Procesado');

-- Segundas
    ALTER TABLE facturas
    ADD PrfCod CHAR(10);


--Terceras

    ALTER TABLE facturas
    ADD COLUMN estadoImpresion VARCHAR(256) DEFAULT 'Previo';

--Cuarto

ALTER TABLE `Facturas`
ADD COLUMN `vtaid_res` INT(11) NULL AFTER `vtaid`,
MODIFY COLUMN `vtaid` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id Venta';
