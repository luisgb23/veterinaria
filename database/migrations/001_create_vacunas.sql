-- Schema reconstructed from the vaccine operations in this repository.
-- Additive: does not replace an existing vacunas table or overwrite data.
CREATE TABLE IF NOT EXISTS vacunas (
    VacunaId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    MascotaId INT NOT NULL,
    VacunaFchIngreso DATE NOT NULL,
    VacunaFchVenc DATE NOT NULL,
    VacunaFchCreacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    VacunaFchModificacion DATETIME NULL,
    VacunaEstado SMALLINT NOT NULL DEFAULT 1,
    INDEX (VacunaFchVenc),
    CONSTRAINT vacunas_mascota_fk FOREIGN KEY (MascotaId)
        REFERENCES mascotas (MascotaId) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
