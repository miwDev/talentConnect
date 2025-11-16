# Portal de Empleo "TalentConnect"

Este proyecto es un portal de empleo diseñado para conectar a estudiantes (de segundo ciclo y egresados) con empresas. Permite a los alumnos encontrar ofertas de trabajo acordes a su perfil académico, mientras que las empresas pueden publicar vacantes y seleccionar a los candidatos más adecuados.

## Características Principales

* **Gestión de usuarios con tres roles diferenciados:** Administrador, Empresario y Alumno.
* **Sistema de registro y validación:** Los alumnos se registran directamente, mientras que las empresas requieren la aprobación de un administrador para activar su cuenta.
* **Funcionalidades CRUD:**
    * **Administrador:** Gestión completa de usuarios (empresas y alumnos) y ofertas de empleo.
    * **Empresario:** Creación, edición y eliminación de sus propias ofertas de empleo.
    * **Alumno:** Actualización de su perfil y CV.
* **Sistema de Postulación:** Los alumnos pueden buscar, filtrar y postularse a las ofertas de empleo.
* **Gestión de Perfiles:** Los alumnos pueden crear y actualizar su perfil, incluyendo la subida de su CV en formato PDF.
* **Notificaciones por Correo Electrónico:** Se notifica a las empresas sobre nuevas postulaciones y a los alumnos sobre nuevas ofertas que coinciden con su perfil.

## Roles y Funcionalidades

El portal define tres roles de usuario con permisos específicos:

### 1. Administrador
El administrador tiene el control total sobre la plataforma.
* Aprueba o rechaza las solicitudes de registro de nuevas empresas.
* Gestiona los datos del alumnado y de las empresas.
* Modera las ofertas de empleo (aprueba, edita, elimina).
* Genera informes y visualiza estadísticas de uso.

### 2. Empresario
Usuario encargado de publicar ofertas y gestionar la información de su empresa.
* Se registra en el portal (cuenta sujeta a validación por un administrador).
* Publica, edita y gestiona sus ofertas de empleo.
* Visualiza los perfiles y accede a los CVs (en PDF) de los alumnos que se postulan.
* Recibe notificaciones por correo cuando un alumno se postula a una de sus ofertas.

### 3. Alumno
Usuario que busca y aplica a ofertas de empleo.
* Se registra y mantiene su perfil profesional, incluyendo su CV en formato PDF.
* Busca, filtra y se postula a las ofertas de empleo disponibles.
* Consulta el listado de empresas y su historial de ofertas.
* Recibe notificaciones por correo sobre nuevas ofertas que coinciden con su perfil.

## Tecnologías Utilizadas

* **Backend:** PHP
* **Frontend:** HTML5, CSS3, JavaScript
* **Base de Datos:** MySQL o MariaDB
* **Gestión de Dependencias PHP:** Composer (detectado en la estructura de archivos `vendor/`).
* **Librerías (detectadas):**
    * `league/plates`: Sistema de plantillas nativo de PHP.
    * `dompdf/dompdf`: Para la generación de documentos PDF.
* **Contenerización:** Docker (basado en la presencia de `docker-compose.yml` y `dockerfile`).

## Estructura del Proyecto

El proyecto sigue una arquitectura PHP personalizada, con una clara separación de responsabilidades:

* `**App/API/**`: Contiene los *endpoints* de la API para la comunicación (ej. `ApiAlumno.php`, `ApiEmpresa.php`).
* `**App/core/**`: Contiene el núcleo de la aplicación:
    * `controller/`: Controladores que manejan la lógica de negocio y las peticiones (ej. `AlumnoController.php`, `Router.php`).
    * `data/`: Repositorios para la abstracción de la base de datos (ej. `AlumnoRepo.php`, `DBC.php`).
    * `model/`: Clases de entidad que representan los datos (ej. `Alumno.php`, `Oferta.php`).
    * `view/`: Plantillas de PHP (`templates/`) que componen la interfaz de usuario.
    * `helper/`: Clases de utilidad (ej. `Autoloader.php`, `Session.php`).
* `**App/public/**`: Directorio público que sirve como punto de entrada (`index.php`) y aloja los *assets* (CSS, JS, imágenes).
* `**App/vendor/**`: Dependencias gestionadas por Composer.
* `**docker-compose.yml**`: Archivo de configuración para el despliegue con Docker.

## Instalación y Puesta en Marcha

Este proyecto está configurado para ejecutarse utilizando Docker.

1.  Asegurarse de tener Docker y Docker Compose instalados en su máquina.
2.  Clonar este repositorio en su sistema local.
3.  Navegar al directorio raíz del proyecto donde se encuentra el archivo `docker-compose.yml`.
4.  Ejecutar el siguiente comando para construir e iniciar los contenedores:
    ```bash
    docker-compose up -d
    ```
5.  Una vez que los contenedores estén en funcionamiento, es posible que necesite instalar las dependencias de Composer (si no están incluidas en la imagen de Docker):
    ```bash
    docker-compose exec app composer install
    ```
6.  Acceder al portal a través del puerto configurado en el archivo `docker-compose.yml` (generalmente `http://localhost:PUERTO`).