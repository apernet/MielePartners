--
-- CAMBIAMOS EMAIL DE CUENTAS
--
UPDATE cuentas SET email='pruebas@correo.com.mx';

--
-- CAMBIAMOS EMAIL DE USUARIOS
--
UPDATE usuarios SET email='pruebas@correo.com.mx';

--
-- CAMBIAMOS EMAIL DE USUARIOS
--
UPDATE cotizaciones SET email='pruebas@correo.com.mx';
UPDATE referidos SET email='pruebas@correo.com.mx' where email IS NOT NULL;