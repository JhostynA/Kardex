USE kardex;

-- CONSULTAS CON LAS TABLAS COLABORADORES


INSERT INTO roles (rol)
	VALUES
		('Administrador'),
		('Farmacéutico'),
		('Asistente de Farmacia'),
		('Cajero');
        
-- SELECT * FROM roles;


INSERT INTO personas (apepaterno, apematerno, nombres, nrodocumento, telprincipal, telsecundario) 
	VALUES
		('Aburto', 'Acevedo', 'Jhostyn', '74052670', '937175623', '987654322'),
		('López', 'Vega', 'Juan', '87654321', '912345678', '912345679'),
		('Sánchez', 'Gómez', 'Luis', '76543210', '923456789', '923456780'),
		('Martínez', 'Flores', 'Ana', '65432109', '934567890', '934567891'),
		('Gonzales', 'Torres', 'Carlos', '54321098', '945678901', '945678902');
        
-- SELECT * FROM personas;
-- SELECT * FROM colaboradores;

CALL spu_colaboradores_registrar(1,1,'jhostynA','$2y$10$fhLjvqGo/OvNs70fma8B1eIxZZULe8uqLSKpRKJM0JcFsFfmICu0q');


-- CALL spu_colaboradores_login('jhostynA');

CALL spu_personas_registrar('Castes', 'Manente', 'Jorge Perez', '74562345', '956326713', '945237825');

-- CALL spu_personas_buscar_dni('74052670');


-- CALL spu_listar_usuario();



-- CONSULTAS CON LAS TABLAS PRODUCTOS


INSERT INTO marcas (marca) 
	VALUES 
		('ABBOTT'), ('ABBOTT SIMILAC'), ('ABBVIE SPAIN');
        
-- SELECT * FROM marcas;


INSERT INTO tipoproductos (tipoproducto) 
	VALUES 
		('Analgesico'), ('Antibiotico'), ('Antihistaminico');
        
-- SELECT * FROM tipoproductos;

INSERT INTO tipopresentaciones (tipopresentacion) 
	VALUES 
		('Tableta'), ('Capsula'), ('Jarabe');
        
-- SELECT * FROM tipopresentaciones;

INSERT INTO productos (idtipoproducto, idmarca, idtipopresentacion, descripcion) 
	VALUES 
		(1, 1, '1', 'Ibuprofeno 400mg'),
		(2, 2, '2', 'Amoxicilina 500mg'),
		(3, 3, '3', 'Loratadina 10mg');
        
-- SELECT * FROM tipoproductos;
-- SELECT * FROM marcas;
-- SELECT * FROM tipopresentaciones;

CALL spu_registrar_producto(1, 1, 1, 'Paracetamol 500mg');



-- CALL spu_listar_productos();

-- CALL spu_listar_movimientos_producto(10);