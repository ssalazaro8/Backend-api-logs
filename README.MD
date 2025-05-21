# Backend API - Registro y Consumo de APIs Externas

## Descripción

Este proyecto es una API RESTful desarrollada en PHP con Slim Framework que consume datos de APIs externas públicas (https://jsonplaceholder.typicode.com) y registra cada petición realizada en una base de datos SQL Server.  
Permite listar usuarios, publicaciones, álbumes por usuario, y gestionar registros de logs (crear, editar, eliminar, listar).  
Incluye simulación de errores en el backend para probar el manejo de fallos.

---

## Tabla de Contenidos

- [Tecnologías Usadas](#tecnologías-usadas)
- [Requisitos Previos](#requisitos-previos)
- [Instalación](#instalación)
- [Configuración](#configuración)
- [Ejecución](#ejecución)
- [Rutas Disponibles](#rutas-disponibles)
- [Simulación de Errores](#simulación-de-errores)
- [Registro de Logs](#registro-de-logs)
- [Pruebas con Postman](#pruebas-con-postman)
- [Licencia](#licencia)
- [Contacto](#contacto)
- [Scripts SQL](#scripts-sql)

---

## Tecnologías Usadas

- PHP 8.x  
- Slim Framework  
- PDO para conexión con SQL Server  
- SQL Server como base de datos  
- Composer para gestión de dependencias  
- JSONPlaceholder API para datos externos  

---

## Requisitos Previos

- PHP 8.x instalado  
- Extensiones PHP necesarias: `pdo_sqlsrv`, `json`, `sqlsrv`, `curl` o `file_get_contents`  
- SQL Server instalado y accesible  
- Composer instalado  
- Servidor web o uso del servidor embebido PHP  

---

## Instalación

1. Clona el repositorio:

git clone https://github.com/ssalazaro8/Backend-api-logs.git


2. Instala las dependencias con Composer:

composer install


3. Configura la conexión a la base de datos en `config/database.php` o mediante variables de entorno.

4. Ejecuta el script SQL para crear la base de datos y tablas (ver sección [Scripts SQL](#scripts-sql)).

---

## Configuración

Edita el archivo `config/database.php` para ajustar los parámetros de conexión:

return [
'host' => 'localhost',
'dbname' => 'TuBaseDeDatos',
'username' => 'tu_usuario',
'password' => 'tu_contraseña',
];


---

## Ejecución

Inicia el servidor embebido de PHP:

php -S localhost:8000 -t public


El backend estará disponible en `http://localhost:8000`.

---

## Rutas Disponibles

| Método | Ruta                  | Descripción                         |
|--------|-----------------------|-----------------------------------|
| GET    | `/users`              | Lista usuarios                    |
| GET    | `/posts`              | Lista publicaciones               |
| GET    | `/albums/{userId}`    | Lista álbumes de un usuario       |
| GET    | `/logs`               | Lista registros de peticiones     |
| POST   | `/logs`               | Crea un registro de petición      |
| PUT    | `/logs/{id}`          | Actualiza un registro de petición |
| DELETE | `/logs/{id}`          | Elimina un registro de petición   |

---

## Simulación de Errores

- El backend simula errores en el 20% de las peticiones a las rutas que consumen APIs externas (`/users`, `/posts`, `/albums/{userId}`).
- Cuando ocurre un error simulado, la API responde con código HTTP 500 y un mensaje JSON:

{
"error": "Error simulado en API externa"
}


- Esto permite probar el manejo de errores en backend y frontend.

---

## Registro de Logs

- Cada petición realizada a las APIs externas se registra en la base de datos con:  
  - Fecha y hora de la petición (campo `CreatedAt` con valor por defecto)  
  - Endpoint consultado  
  - Método HTTP  
  - Datos retornados o mensaje de error (en formato JSON)  

- Los logs se crean tanto para respuestas exitosas como para errores.

---

## Pruebas con Postman

- Prueba las rutas listadas en [Rutas Disponibles](#rutas-disponibles).  
- Realiza varias peticiones para observar la simulación de errores.  
- Verifica que los logs se crean correctamente en la base de datos.

---


## Licencia

Este proyecto está bajo la licencia MIT. Consulta el archivo `LICENSE` para más detalles.

---

## Contacto
 
**Samuel Salazar** — [samuelsala24@gmail.com](samuelsala24@gmail.com)  
Repositorio: [https://github.com/ssalazaro8/Backend-api-logs.git](https://github.com/ssalazaro8/Backend-api-logs.git)

---

## Scripts SQL

Ejemplo básico para crear la tabla de logs:

CREATE TABLE RequestLogs (
Id INT IDENTITY(1,1) PRIMARY KEY,
Endpoint NVARCHAR(255),
Method NVARCHAR(10),
ResponseData NVARCHAR(MAX),
CreatedAt DATETIME DEFAULT GETDATE()
);

Ejecútalo en tu base de datos SQL Server.

---

# ¡Gracias por usar este proyecto!
