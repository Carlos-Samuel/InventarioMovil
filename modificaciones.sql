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
