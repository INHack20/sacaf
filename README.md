Sistema De Control De Asignación
========================

Se encarga de distribuir el trabajo equitativamente entre los tribunales de control y de juicio, teniendo en cuenta los
horarios de trabajo y las restricciones que aplican, asi mismo lleva el control de las operaciones realizadas por cada
usuario de acuerdo a su nivel de acceso.

Pasos para la instalacion:
--------------------------------

Requerimientos:

	Apache 2
	Mysql
	PHP 5.3
	Consultar www.symfony.com para requerimientos del framework.

### 1) Descomprimir la aplicacion en el Servidor Web.

### 2) Verificar el correcto funcionamiento del Framework Symfony2 en el equipo ejecutando desde la raiz del proyecto el comando 
php app/check.php desde una terminal o visitando la pagina web/config.php
( En caso de error, instalar nuevamente las dependencias y librerias de terceros con el commando bin/vendors --reinstall )

### 3) Modificar el archivo app/config/parameters.ini y coloque los datos correspondientes a su conexion.

### 4) Ejecutar los siguientes comandos para crear la base de datos, cargar los Fixtures de prueba y instalar los recursos publicos.

	php app/console doctrine:database:create
	php app/console doctrine:schema:create
	php app/console doctrine:fixtures:load
	php app/console assets:install web

### 5) Ingresar al navegador e ingresar a la carpeta de la aplicacion, alli debe entrar a la direccion web/app.php para ingresar en
el entorno de produccion (para el entordo de desarrollo ingresar en web/app_dev.php).

### 6) Al cargar los fixtures se crea un usuario predeterminado en la base de datos con las siguientes credenciales:
	Nombre de Usuario: admin
	Contraseña: adminadmin
	Role: SUPER_ADMIN
