CREATE TABLE importaciones_dbf (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha_inicio DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_fin DATETIME NULL,
    vtaids_concatenados LONGTEXT NOT NULL,
    cantidad_facturas INT NOT NULL DEFAULT 0
);


CREATE TABLE importaciones_dbf_detalle (
    id INT AUTO_INCREMENT PRIMARY KEY,
    importacion_id INT NOT NULL,
    secuencia INT NOT NULL, -- 1..100 para dejar el orden
    procod   VARCHAR(50)  NULL,
    bodcod   VARCHAR(50)  NULL,
    tmicod   VARCHAR(50)  NULL,
    docnum   VARCHAR(50)  NULL,
    vncfec   VARCHAR(50)  NULL,
    vnclot   VARCHAR(50)  NULL,
    vnccan   VARCHAR(50)  NULL,
    vncsal   VARCHAR(50)  NULL,
    prfcod   VARCHAR(50)  NULL,
    vncsumres VARCHAR(50) NULL,
    vnccns   VARCHAR(50)  NULL,
    vncfecdoc VARCHAR(50) NULL,
    empcod   VARCHAR(50)  NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_import_detalle
        FOREIGN KEY (importacion_id)
        REFERENCES importaciones_dbf(id)
        ON DELETE CASCADE
);
