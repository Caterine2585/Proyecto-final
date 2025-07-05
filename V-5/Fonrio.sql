/*DDL*/
/* Creacion de  base de datos*/
CREATE DATABASE FONRIO;
USE FONRIO;

/* Creacion de  tablas*/
CREATE TABLE Estados (
    ID_Estado VARCHAR(20) PRIMARY KEY,
    Nombre_Estado VARCHAR(15) NOT NULL
);


CREATE TABLE Roles (
    ID_Rol VARCHAR(20) PRIMARY KEY,
    Nombre VARCHAR(30) NOT NULL
);


CREATE TABLE Categorias (
    ID_Categoria VARCHAR(20) PRIMARY KEY,
    Nombre_Categoria VARCHAR(30) NOT NULL
);


CREATE TABLE Gamas (
    ID_Gama VARCHAR(20) PRIMARY KEY,
    Nombre_Gama VARCHAR(15) NOT NULL
);


CREATE TABLE Proveedores (
    ID_Proveedor VARCHAR(20) PRIMARY KEY,
    Nombre_Proveedor VARCHAR(45) NOT NULL,
    Correo_Electronico VARCHAR(30) UNIQUE NOT NULL,
    Telefono VARCHAR(15) NOT NULL,
    ID_Estado VARCHAR(20) NOT NULL,
    FOREIGN KEY (ID_Estado) REFERENCES Estados(ID_Estado)
);


CREATE TABLE Clientes (
    Documento_Cliente VARCHAR(20) PRIMARY KEY,
    Nombre_Cliente VARCHAR(20) NOT NULL,
    Apellido_Cliente VARCHAR(20) NOT NULL,
    Telefono VARCHAR(15) NOT NULL,
    Fecha_Nacimiento DATE NOT NULL,
    Genero CHAR(1) CHECK (Genero IN ('F','M')) NOT NULL,
    ID_Estado VARCHAR(20) NOT NULL,
    FOREIGN KEY (ID_Estado) REFERENCES Estados(ID_Estado)
);


CREATE TABLE Empleados (
    Documento_Empleado VARCHAR(20) PRIMARY KEY,
    Tipo_Documento VARCHAR (10) NOT NULL,
    Nombre_Usuario NVARCHAR(30) NOT NULL,
    Apellido_Usuario NVARCHAR(30) NOT NULL,
    Edad VARCHAR(20) NOT NULL,
    Correo_Electronico VARCHAR(100) UNIQUE NOT NULL,
    Telefono VARCHAR(10) NOT NULL,
    Genero CHAR(1) CHECK (Genero IN ('F','M')) NOT NULL,
    ID_Estado VARCHAR(20) NOT NULL,
    ID_Rol VARCHAR(20) NOT NULL,
	Fotos VARCHAR(200) NOT NULL,
    FOREIGN KEY (ID_Estado) REFERENCES Estados(ID_Estado),
    FOREIGN KEY (ID_Rol) REFERENCES Roles(ID_Rol)
);

CREATE TABLE Contrasenas (
    ID_Contrasena INT AUTO_INCREMENT PRIMARY KEY,
    Documento_Empleado VARCHAR(20) UNIQUE NOT NULL,
    Contrasena_Hash VARCHAR(255) NOT NULL,
    Fecha_Creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Documento_Empleado) REFERENCES Empleados(Documento_Empleado)
);

CREATE TABLE Productos (
    ID_Producto VARCHAR(20) PRIMARY KEY,
    Nombre_Producto VARCHAR(30) NOT NULL,
    Descripcion TEXT NOT NULL,
    Precio_Venta Decimal NOT NULL,
    Stock_Minimo INT NOT NULL,
    ID_Categoria VARCHAR(20) NOT NULL,
    ID_Estado VARCHAR(20) NOT NULL,
    ID_Gama VARCHAR(20) NOT NULL,
    FOREIGN KEY (ID_Categoria) REFERENCES Categorias(ID_Categoria),
    FOREIGN KEY (ID_Estado) REFERENCES Estados(ID_Estado),
    FOREIGN KEY (ID_Gama) REFERENCES Gamas(ID_Gama)
);


CREATE TABLE Compras (
    ID_Entrada VARCHAR(20) PRIMARY KEY,
    Precio_Compra Decimal NOT NULL,
    ID_Producto VARCHAR(20) NOT NULL,
    Documento_Empleado VARCHAR(20) NOT NULL,
    FOREIGN KEY (ID_Producto) REFERENCES Productos(ID_Producto),
    FOREIGN KEY (Documento_Empleado) REFERENCES Empleados(Documento_Empleado)
);

CREATE TABLE Detalle_Compras (
    Fecha_Entrada DATE NOT NULL,
    Cantidad INT NOT NULL,
    ID_Proveedor VARCHAR(20) NOT NULL,
    ID_Entrada VARCHAR(20) NOT NULL,
    FOREIGN KEY (ID_Proveedor) REFERENCES Proveedores(ID_Proveedor),
    FOREIGN KEY (ID_Entrada) REFERENCES Compras(ID_Entrada)
);


CREATE TABLE Ventas (
    ID_Venta VARCHAR(20) PRIMARY KEY,
    Documento_Cliente VARCHAR(20) NOT NULL,
    Documento_Empleado VARCHAR(20) NOT NULL,
    FOREIGN KEY (Documento_Cliente) REFERENCES Clientes(Documento_Cliente),
    FOREIGN KEY (Documento_Empleado) REFERENCES Empleados(Documento_Empleado)
);


CREATE TABLE Detalle_Ventas (
    Cantidad INT NOT NULL,
    Fecha_Salida DATE NOT NULL,
    ID_Producto VARCHAR(20) NOT NULL,
    ID_Venta VARCHAR(20) NOT NULL,
    FOREIGN KEY (ID_Producto) REFERENCES Productos(ID_Producto),
    FOREIGN KEY (ID_Venta) REFERENCES Ventas(ID_Venta)
);

CREATE TABLE Detalle_Devoluciones (
    ID_Devolucion VARCHAR(20) PRIMARY KEY,
    Cantidad_Devuelta INT NOT NULL,
    ID_Venta VARCHAR(20) NOT NULL,
    FOREIGN KEY (ID_Venta) REFERENCES Ventas(ID_Venta)
);


CREATE TABLE Devoluciones (
    ID_Devolucion VARCHAR(20) PRIMARY KEY,
    Fecha_Devolucion DATE NOT NULL,
    Motivo VARCHAR (45) NOT NULL,
    FOREIGN KEY (ID_Devolucion) REFERENCES Detalle_Devoluciones(ID_Devolucion)
);


/*DML  Inserción de datos en las tablas*/

INSERT INTO Estados (ID_Estado, Nombre_Estado) VALUES
('EST001', 'Activo'),
('EST002', 'Inactivo'),
('EST003', 'En proceso');


INSERT INTO Roles (ID_Rol, Nombre) VALUES
('ROL001', 'Administrador'),
('ROL002', 'Empleado');


INSERT INTO Categorias (ID_Categoria, Nombre_Categoria) VALUES
('CAT001', 'Celulares'),
('CAT002', 'Cargadores'),
('CAT003', 'Audifonos'),
('CAT004', 'Forros'),
('CAT005', 'Strap_Phone');


INSERT INTO Gamas (ID_Gama, Nombre_Gama) VALUES
('GAM001', 'Baja'),
('GAM002', 'Media'),
('GAM003', 'Alta');


INSERT INTO Proveedores(ID_Proveedor,Nombre_Proveedor,Correo_electronico,Telefono,ID_Estado) VALUES
('PROV001', 'Claro_Colombia', 'Claro1@proveedor.com', '3012345678', 'EST002'),
('PROV002', 'Alkosto', 'Alkosto@proveedor.com', '3023456789', 'EST001'),
('PROV003', 'Éxito', 'Éxito@proveedor.com', '3034567890', 'EST002'),
('PROV004', 'Mercado_Libre', 'Mercado_Libre@proveedor.com', '3045678901', 'EST001'),
('PROV005', 'Accesorios_Colombia', 'Accesorios@proveedor.com', '3056789012', 'EST003');


INSERT INTO Clientes(Documento_Cliente,Nombre_Cliente,Apellido_Cliente,Telefono,Fecha_Nacimiento,Genero,ID_Estado ) VALUES
('CLI006', 'Juan', 'Pérez', '3144574273', '1990-05-10', 'M', 'EST001'),
('CLI007', 'Ana', 'Gómez', '3144574272', '1985-03-15', 'F', 'EST001'),
('CLI008', 'Luis', 'Ramírez', '3124574271', '2000-07-20', 'M', 'EST002'),
('CLI009', 'Carla', 'López', '3144774271', '1998-12-01', 'F', 'EST001'),
('CLI010', 'Alex', 'Moreno', '3144574275', '1995-09-30', 'M', 'EST003');

INSERT INTO Empleados(Documento_Empleado,Tipo_Documento,Nombre_Usuario,Apellido_Usuario,Edad,Correo_electronico,Telefono,Genero,ID_Estado,ID_Rol,Fotos) VALUES
('4245','CC','dsf','sdf','28','ffsd@gmil.com','1278123581','M','EST001','ROL002','fotos del empleado'),
('1111111111','CC','Raul','Riscas','111','raul@gmail.com','1111111111','F','EST001','ROL002','fotos del empleado'),
('1013262102','CC','Kevin Alexis','Morales Avendaño','18','kevin@gmail.com','3209323422','M','EST001','ROL001','fotos del empleado'),
('1028880412','CC','Santiago','Saenz','20','krevin1978@gmail.com','3227268756','M','EST001','ROL002','fotos del empleado'),
('1032939708','CC','Heidy','Lozano','18','182020leon@gmail.com','3227240211','F','EST001','ROL002','fotos del empleado');

INSERT INTO Contrasenas (ID_Contraseña,Documento_Empleado, Contrasena_Hash,Fecha_Creacion)
VALUES
('6','4245','Hola','2025-07-04 14:39:58'),
('5','1111111111','Hola','2025-07-02 17:26:20'),
('3','1013262102','Hola','2025-06-30 14:06:16'),
('4','1028880412','12345','2025-07-01 07:45:41'),
('4','1032939708','12345','2025-07-01 07:45:41');



-- Actualización para hashear las contraseñas
-- Inserta o actualiza una contraseña segura:
UPDATE Contrasenas
SET Contrasena_Hash = SHA2('clave_segura', 256)
WHERE Documento_Empleado = 'EMP001';

INSERT INTO Productos(ID_Producto,Nombre_Producto,Descripcion,Precio_Venta,Stock_Minimo,ID_Categoria,ID_Estado,ID_Gama) VALUES
('PROD006', 'Redmi Note 12', 'Celular Xiaomi gama media con buena batería', 850000, 5, 'CAT001', 'EST001','GAM001'),
('PROD007', 'Motorola G60', 'Smartphone Motorola con cámara de 108MP', 950000, 4, 'CAT001', 'EST001','GAM001'),
('PROD008', 'Samsung A54', 'Celular Samsung gama media-alta', 1250000, 6, 'CAT001', 'EST001','GAM001'),
('PROD009', 'iPhone 13', 'iPhone 13 de 128GB', 3300000, 2, 'CAT001', 'EST001','GAM001'),
('PROD010', 'Cargador Tipo C', 'Cargador rápido universal tipo C', 50000, 10, 'CAT002', 'EST001','GAM001');


INSERT INTO Compras(ID_Entrada,Precio_Compra,ID_Producto,Documento_Empleado ) VALUES
('323',  120.00,  'PROD006', '1028880412'),
('78',  655.00,  'PROD009', '1013262102'),
('ENT001',  100000.00,  'PROD009', '1013262102'),
('id',  344.00,  'PROD009', '1032939708');

INSERT INTO Detalle_Compras(Fecha_entrada,Cantidad,ID_Proveedor,ID_Entrada ) VALUES
('2022-06-21','12','PROV001','323'),
('2023-02-14','10','PROV002','ENT001'),
('2025-07-04','155','PROV002','78'),
('2025-07-04','2','PROV004','id');

INSERT INTO Ventas(ID_Venta,Documento_Cliente,Documento_Empleado)VALUES
('12','CLI006','1013262102'),
('23','CLI007','1032939708'),
('453','CLI007','1032939708'),
('56','CLI006','1032939708');




INSERT INTO Detalle_Ventas(Cantidad,Fecha_Salida,ID_Producto,ID_Venta  ) VALUES
('34','2025-07-11','PROD006','12'),
('1','2025-07-04','PROD006','23'),
('50','2025-07-04','PROD006','56'),
('20','2025-07-04','PROD006','453');

INSERT INTO Detalle_Devoluciones(ID_Devolucion,Cantidad_Devuelta,ID_Venta) VALUES
('DEV001', '1','23');


INSERT INTO Devoluciones(ID_Devolucion,Fecha_Devolucion,Motivo) VALUES
('DEV001', '2025-07-04','FEO');



/*DQL Consultas-9*/

/*Consulta total de compras por cliente*/
SELECT C.Documento_Cliente, CONCAT(C.Nombre_Cliente, ' ', C.Apellido_Cliente) AS Cliente,
       COUNT(V.ID_Venta) AS Total_Compras
FROM Clientes C
JOIN Ventas V ON C.Documento_Cliente = V.Documento_Cliente
GROUP BY C.Documento_Cliente, C.Nombre_Cliente, C.Apellido_Cliente;


/*Consulta total de ventas por empleado*/
SELECT E.Documento_Empleado, CONCAT(E.Nombre_Usuario, ' ', E.Apellido_Usuario) AS Empleado,
       COUNT(V.ID_Venta) AS Ventas_Realizadas
FROM Empleados E
JOIN Ventas V ON E.Documento_Empleado = V.Documento_Empleado
GROUP BY E.Documento_Empleado, E.Nombre_Usuario, E.Apellido_Usuario;


/*Cuenta empleados por rol mostrando nombre del rol y total*/
SELECT R.Nombre AS Rol, COUNT(*) AS Total_Empleados
FROM Empleados E
JOIN Roles R ON E.ID_Rol = R.ID_Rol
GROUP BY R.Nombre;


/* Cuenta total de personas por género (empleados y clientes juntos)*/
SELECT Genero, COUNT(*) AS Cantidad
FROM (
    SELECT Genero FROM Empleados
    UNION ALL
    SELECT Genero FROM Clientes
) AS Personas
GROUP BY Genero;


/*Consulta, cuenta la cantidad de productos con estado Activo*/
SELECT COUNT(*) AS Productos_Activos
FROM Productos
WHERE ID_Estado = 'EST001'
/*Lista el correo  indicando si son de proveedores o empleados*/
SELECT  'Proveedores' AS Rol, Correo_Electronico
FROM Proveedores
UNION
SELECT 'Empleados' AS Rol,Correo_Electronico
FROM Empleados;


/* Muestra todas las gamas ordenadas alfabéticamente por nombre*/
SELECT * FROM Gamas ORDER BY Nombre_Gama;



/*Muestra una lista de los productos vendidos con la fecha de salida,
 el nombre completo del cliente y la cantidad, ordenados desde la venta más reciente.*/
SELECT 
    DV.Fecha_Salida, 
    CONCAT(C.Nombre_Cliente, ' ', C.Apellido_Cliente) AS Cliente,
    P.Nombre_Producto,
    DV.Cantidad
FROM Detalle_Ventas DV
JOIN Ventas V ON DV.ID_Venta = V.ID_Venta
JOIN Clientes C ON V.Documento_Cliente = C.Documento_Cliente
JOIN Productos P ON DV.ID_Producto = P.ID_Producto
ORDER BY DV.Fecha_Salida DESC;




/* Muestra el nombre de los proveedores activos y la cantidad de 
compras únicas que han realizado.*/
SELECT 
    PR.Nombre_Proveedor, 
    COUNT(DISTINCT DC.ID_Entrada) AS Numero_Compras
FROM Proveedores PR
JOIN Detalle_Compras DC ON PR.ID_Proveedor = DC.ID_Proveedor
WHERE PR.ID_Estado = 'EST001'
GROUP BY PR.Nombre_Proveedor
ORDER BY Numero_Compras DESC;



/*Muestra el documento, nombre y contraseña encriptada de los empleados*/
SELECT 
    E.Documento_Empleado,
    CONCAT(E.Nombre_Usuario, ' ', E.Apellido_Usuario) AS Empleado,
    C.Contrasena_Hash
FROM Empleados E
JOIN Contrasenas C ON E.Documento_Empleado = C.Documento_Empleado;



/*TRIGGER-5*/
/* Disminuir stock tras una venta (AFTER INSERT en Detalle_Ventas)*/
DELIMITER //

CREATE TRIGGER trg_UpdateStockAfterVenta
AFTER INSERT ON Detalle_Ventas
FOR EACH ROW
BEGIN
    UPDATE Productos
    SET Stock_Minimo = Stock_Minimo - NEW.Cantidad
    WHERE ID_Producto = NEW.ID_Producto;
END;

//DELIMITER ;




/*Aumentar stock tras una compra (AFTER INSERT en Detalle_Compras)*/
DELIMITER //

CREATE TRIGGER trg_UpdateStockAfterCompra
AFTER INSERT ON Detalle_Compras
FOR EACH ROW
BEGIN
    UPDATE Productos
    SET Stock_Minimo = Stock_Minimo + NEW.Cantidad
    WHERE ID_Producto = (
        SELECT ID_Producto
        FROM Compras
        WHERE ID_Entrada = NEW.ID_Entrada
    );
END;

//DELIMITER ;



/*Productos con bajo stock*/
DELIMITER //

CREATE PROCEDURE sp_ProductosBajoStock(IN Minimo INT)
BEGIN
    SELECT ID_Producto, Nombre_Producto, Stock_Minimo
    FROM Productos
    WHERE Stock_Minimo <= Minimo;
END;

//DELIMITER ;




/*Resumen de ventas por rango de fechas*/
DELIMITER //

CREATE PROCEDURE sp_ResumenVentasPorFecha(
    IN FechaInicio DATE,
    IN FechaFin DATE
)
BEGIN
    SELECT 
        V.ID_Venta, 
        DV.Fecha_Salida AS Fecha,
        CONCAT(C.Nombre_Cliente, ' ', C.Apellido_Cliente) AS Cliente,
        DV.ID_Producto, 
        P.Nombre_Producto AS Producto,
        DV.Cantidad, 
        P.Precio_Venta, 
        (DV.Cantidad * P.Precio_Venta) AS Total
    FROM Ventas V
    INNER JOIN Clientes C ON V.Documento_Cliente = C.Documento_Cliente
    INNER JOIN Detalle_Ventas DV ON V.ID_Venta = DV.ID_Venta
    INNER JOIN Productos P ON DV.ID_Producto = P.ID_Producto
    WHERE DV.Fecha_Salida BETWEEN FechaInicio AND FechaFin;
END;

//DELIMITER ;


/*Registrar una venta*/
DELIMITER //

CREATE PROCEDURE sp_RegistrarVenta(
    IN Documento_Cliente VARCHAR(20),
    IN Fecha DATETIME,
    IN ID_Producto VARCHAR(20),
    IN Cantidad INT,
    IN Documento_Empleado VARCHAR(20)
)
BEGIN
    DECLARE ID_Venta VARCHAR(20);

    -- Generar un ID único básico con CONCAT + RAND
    SET ID_Venta = CONCAT('VEN', FLOOR(100000 + (RAND() * 899999)));

    INSERT INTO Ventas (ID_Venta, Documento_Cliente, Documento_Empleado)
    VALUES (ID_Venta, Documento_Cliente, Documento_Empleado);

    INSERT INTO Detalle_Ventas (ID_Venta, ID_Producto, Cantidad, Fecha_Salida)
    VALUES (ID_Venta, ID_Producto, Cantidad, Fecha);
END;

//DELIMITER ;
