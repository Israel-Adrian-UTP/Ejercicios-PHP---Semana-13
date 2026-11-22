# Taller de Programación Web - Semana 13: Aplicación Web - BD Juguetería

Este proyecto corresponde a la práctica de la Semana 13 del curso **Taller de Programación Web**. Consiste en una aplicación web en PHP con MySQLi para gestionar tipos de juguetes y productos de una juguetería, incluyendo registro, listado, eliminación y carga de imágenes.

---

## Estructura del Proyecto

* `bd_jugueteria.sql`: Script SQL que define la base de datos `bd_jugueteria` y las tablas `tipo` y `producto`.
* `index.php`: Archivo principal que implementa la clase `Conectar` (conexión y operaciones con MySQLi), el manejo de peticiones `POST` y `GET`, y la interfaz HTML con formularios y tablas.
* `img/`: Carpeta donde se copian y almacenan las imágenes subidas de los productos.

---

## Modelo de Base de Datos (`bd_jugueteria.sql`)

La base de datos cuenta con dos tablas relacionadas mediante clave foránea con eliminación en cascada (`ON DELETE CASCADE`):

### Tabla `tipo`
| Campo | Tipo | Restricciones | Descripción |
| :--- | :--- | :--- | :--- |
| `codtip` | `INT` | `AUTO_INCREMENT`, `PRIMARY KEY` | Identificador único del tipo de juguete |
| `nomtip` | `VARCHAR(50)` | `NOT NULL` | Nombre del tipo de juguete |

### Tabla `producto`
| Campo | Tipo | Restricciones | Descripción |
| :--- | :--- | :--- | :--- |
| `codpro` | `INT` | `AUTO_INCREMENT`, `PRIMARY KEY` | Identificador único del producto |
| `nompro` | `VARCHAR(100)` | `NOT NULL` | Nombre del producto |
| `prepro` | `DECIMAL(10,2)` | `NOT NULL` | Precio del producto |
| `canpro` | `INT` | `NOT NULL` | Cantidad en inventario |
| `fotpro` | `VARCHAR(255)` | - | Ruta de la imagen del producto |
| `codtip` | `INT` | `FOREIGN KEY` -> `tipo(codtip)` | Referencia al tipo de juguete (`ON DELETE CASCADE`) |

---

## Funcionalidades Implementadas (`index.php`)

* **Conexión a Base de Datos:** La clase `Conectar` establece la conexión hacia `localhost` con la base de datos `bd_jugueteria`, usuario `root`, contraseña vacía y codificación `utf8`.
* **Gestión de Tipos:** Permite registrar nuevos tipos (`agregarTipo`), listarlos (`listarTipo`) y eliminarlos (`eliminarTipo`) mediante parámetros `GET` con confirmación en JavaScript.
* **Gestión de Productos:** Permite registrar productos (`agregarProducto`) asociándolos a un tipo desde un `<select>`, subir su imagen a la carpeta `img/` (`copy`), listarlos mediante `INNER JOIN` con `tipo` (`listarProducto`) y eliminarlos (`eliminarProducto`).
* **Patrón PRG (Post/Redirect/Get):** Tras ejecutar acciones de registro o eliminación, redirige a `index.php` mediante `header("Location: index.php")`.

---

## Instalación y Ejecución

1. Iniciar los servicios de **Apache** y **MySQL** en tu entorno local (por ejemplo, XAMPP o Laragon).
2. Crear la base de datos `bd_jugueteria` y ejecutar el script `bd_jugueteria.sql` en MySQL / phpMyAdmin.
3. Colocar `index.php` y la carpeta `img/` dentro del directorio del servidor web (por ejemplo, `htdocs/semana13/`).
4. Abrir el navegador y acceder a la ruta correspondiente, por ejemplo:
   ```text
   http://localhost/semana13/index.php