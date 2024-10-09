CREATE DATABASE kardex;
USE kardex;

-- TODO RELACIONADO CON COLABORADORES
CREATE TABLE roles (
    idrol 	INT PRIMARY KEY AUTO_INCREMENT,
    rol 	VARCHAR(50) NOT NULL,
    CONSTRAINT uk_rol_rol UNIQUE (rol)
);


CREATE TABLE personas (
    idpersona 		INT PRIMARY KEY AUTO_INCREMENT,
    apepaterno 		VARCHAR(20) 	NOT NULL,
    apematerno 		VARCHAR(20) 	NOT NULL,
    nombres 		VARCHAR(50) 	NOT NULL,
    nrodocumento 	CHAR(8) 		NOT NULL,
    telprincipal 	CHAR(9) 	 	NOT NULL,
    telsecundario 	CHAR(9)			NOT NULL,
    fecharegistro 	DATETIME 		NOT NULL DEFAULT NOW(),
    fechabaja 		DATETIME 		NULL,
    CONSTRAINT uk_nrodocumento_per UNIQUE (nrodocumento),
	CONSTRAINT uk_telprincipal_per UNIQUE (telprincipal)
);


CREATE TABLE colaboradores (
    idcolaboradores INT PRIMARY KEY AUTO_INCREMENT,
    idpersona 		INT,
    idrol 			INT,
    nomusuario 		VARCHAR(50) NOT NULL,
    passusuario 	VARCHAR(60) NOT NULL,
    fecharegistro 	DATETIME 	NOT NULL DEFAULT NOW(),
    fechabaja 		DATETIME 	NULL,
    CONSTRAINT uk_nomusuario_per UNIQUE (nomusuario),
    FOREIGN KEY (idpersona) REFERENCES personas(idpersona),
    FOREIGN KEY (idrol) REFERENCES roles(idrol)
);


DELIMITER $$
CREATE PROCEDURE spu_colaboradores_login(IN _nomusuario VARCHAR(50))
BEGIN
	SELECT
		COL.idcolaboradores,
        PER.apepaterno, PER.nombres,
        COL.nomusuario, COL.passusuario
		FROM colaboradores COL
        INNER JOIN personas PER ON PER.idpersona = COL.idcolaboradores
        WHERE COL.nomusuario = _nomusuario AND COL.fechabaja IS NULL;
END $$



DELIMITER $$
CREATE PROCEDURE spu_colaboradores_registrar
(
	IN _idpersona		INT,
    IN _idrol 			INT,
    IN _nomusuario  	VARCHAR(50),
    IN _passusuario 	VARCHAR(60)
)
BEGIN
	INSERT INTO colaboradores (idpersona, idrol, nomusuario, passusuario) VALUES
		(_idpersona, _idrol, _nomusuario, _passusuario);
	SELECT @@last_insert_id 'idcolaboradores';
END $$


DELIMITER $$
CREATE PROCEDURE spu_personas_registrar
(
	IN _apepaterno 		VARCHAR(20),
    IN _apematerno 		VARCHAR(20),
    IN _nombres 		VARCHAR(50),
    IN _nrodocumento	CHAR(8),
    IN _telprincipal 	CHAR(9),
    IN _telsecundario 	CHAR(9)
)
BEGIN
    INSERT INTO personas 
		(apepaterno, apematerno, nombres, nrodocumento, telprincipal, telsecundario) VALUES
        (_apepaterno, _apematerno, _nombres, _nrodocumento, _telprincipal, _telsecundario );
	SELECT @@last_insert_id 'idpersona';
END $$


DELIMITER $$
CREATE PROCEDURE spu_personas_buscar_dni(IN _nrodocumento CHAR(8))
BEGIN
	SELECT
		PER.idpersona,
        COL.idcolaboradores,
        PER.apepaterno, PER.apematerno, PER.nombres, PER.telprincipal, PER.telsecundario
		FROM personas PER
        LEFT JOIN colaboradores COL ON COL.idcolaboradores = PER.idpersona
        WHERE nrodocumento = _nrodocumento;
END $$


DELIMITER //
CREATE PROCEDURE spu_listar_usuario()
BEGIN
    SELECT p.apepaterno, p.apematerno, p.nombres, p.nrodocumento, 
           c.nomusuario, p.telprincipal, p.telsecundario, r.rol
    FROM personas p
    INNER JOIN colaboradores c ON p.idpersona = c.idpersona
    INNER JOIN roles r ON c.idrol = r.idrol;
END //
DELIMITER ;



-- TODO RELACIONADO CON PRODUCTOS 


CREATE TABLE marcas (
    idmarca 	INT PRIMARY KEY AUTO_INCREMENT,
    marca 		VARCHAR(50) NOT NULL,
    CONSTRAINT uk_marca UNIQUE (marca)
);


CREATE TABLE tipoproductos (
    idtipoproducto 		INT PRIMARY KEY AUTO_INCREMENT,
    tipoproducto 		VARCHAR(50) NOT NULL,
    CONSTRAINT uk_tipoproducto UNIQUE (tipoproducto)
);

CREATE TABLE tipopresentaciones (
    idtipopresentacion 		INT PRIMARY KEY AUTO_INCREMENT,
    tipopresentacion 		VARCHAR(50) NOT NULL,
    CONSTRAINT uk_tipopresentacion UNIQUE (tipopresentacion)
);


CREATE TABLE productos (
    idproducto         INT PRIMARY KEY AUTO_INCREMENT,
    idtipoproducto     INT,
    idmarca            INT,
    idtipopresentacion INT,
    stock_actual 	   INT DEFAULT 0,
    descripcion        VARCHAR(100) NOT NULL,
    FOREIGN KEY (idtipoproducto) REFERENCES tipoproductos(idtipoproducto),
    FOREIGN KEY (idmarca) REFERENCES marcas(idmarca),
    FOREIGN KEY (idtipopresentacion) REFERENCES tipopresentaciones(idtipopresentacion),
    CONSTRAINT uk_producto UNIQUE (descripcion, idtipopresentacion, idtipoproducto, idmarca)
);


DELIMITER //
CREATE PROCEDURE spu_registrar_producto (
    IN p_idtipoproducto INT,
    IN p_idmarca INT,
    IN p_idtipopresentacion INT,
    IN p_descripcion VARCHAR(100)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;
    START TRANSACTION;
    INSERT INTO productos (idtipoproducto, idmarca,idtipopresentacion, descripcion)
    VALUES (p_idtipoproducto, p_idmarca, p_idtipopresentacion, p_descripcion);
    SELECT @@last_insert_id 'idproducto';
    COMMIT;
END //
DELIMITER ;



DELIMITER //
CREATE PROCEDURE spu_listar_productos()
BEGIN
    SELECT 
		p.idproducto,
        p.descripcion, 
        p.stock_actual,
        m.marca, 
        t.tipoproducto,
        tp.tipopresentacion
    FROM 
        productos p
    INNER JOIN 
        marcas m ON p.idmarca = m.idmarca
    INNER JOIN 
        tipoproductos t ON p.idtipoproducto = t.idtipoproducto
    INNER JOIN 
        tipopresentaciones tp ON p.idtipopresentacion = tp.idtipopresentacion;
END //
DELIMITER ;



-- KARDEX 

CREATE TABLE kardex (
    idkardex 		INT PRIMARY KEY AUTO_INCREMENT,
    idproducto 		INT NOT NULL,
    tipomovimiento 	ENUM('entrada', 'salida') NOT NULL,
    stokactual 		INT NOT NULL,
    cantidad 		INT NOT NULL,
    fecharegistro 	DATE NOT NULL,  
    CONSTRAINT fk_kardex_producto FOREIGN KEY (idproducto) REFERENCES productos(idproducto)
);


DELIMITER //
CREATE PROCEDURE spu_manejar_kardex (
    IN p_idproducto INT,
    IN p_tipomovimiento ENUM('entrada', 'salida'),
    IN p_cantidad INT,
    IN p_fecharegistro DATE 
)
BEGIN
    DECLARE v_stockactual INT;
    SELECT stock_actual INTO v_stockactual FROM productos WHERE idproducto = p_idproducto;
    IF p_tipomovimiento = 'salida' AND p_cantidad > v_stockactual THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stock insuficiente';
    ELSE
        START TRANSACTION;
        IF p_tipomovimiento = 'entrada' THEN
            UPDATE productos SET stock_actual = stock_actual + p_cantidad WHERE idproducto = p_idproducto;
        ELSE
            UPDATE productos SET stock_actual = stock_actual - p_cantidad WHERE idproducto = p_idproducto;
        END IF;
        INSERT INTO kardex (idproducto, tipomovimiento, stokactual, cantidad, fecharegistro)
        VALUES (p_idproducto, p_tipomovimiento, v_stockactual, p_cantidad, p_fecharegistro);
        COMMIT;
    END IF;
END //
DELIMITER ;


DELIMITER //
CREATE PROCEDURE spu_listar_movimientos_producto(
    IN p_idproducto INT
)
BEGIN
    SELECT
        productos.descripcion AS nombre_producto,
        kardex.stokactual AS stock_anterior,
        kardex.tipomovimiento,
        kardex.cantidad,
        kardex.fecharegistro
    FROM
        kardex
    INNER JOIN
        productos ON kardex.idproducto = productos.idproducto
    WHERE
        kardex.idproducto = p_idproducto
    ORDER BY
        kardex.fecharegistro ASC;
END //
DELIMITER ;

