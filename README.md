
# 📅 Sistema de Gestión de Horarios Academicos
Un sistema completo de gestión de horarios académicos desarrollado con Laravel, diseñado para instituciones educativas que necesitan organizar y administrar horarios de clases de manera eficiente.


## 🌟 Introducción y Descripción del Proyecto

El **Sistema de Gestión de Horarios Universitarios** es una aplicación web integral y robusta, desarrollada sobre el framework Laravel 10 y PHP 8.1+. Su objetivo principal es proporcionar a las instituciones de educación superior una herramienta eficiente y centralizada para la compleja tarea de planificación, creación, administración y consulta de horarios académicos.

Este sistema aborda los desafíos comunes en la gestión de horarios, como la asignación de aulas, la disponibilidad de docentes, la prevención de conflictos horarios, y la gestión de diferentes tipos de carga horaria (teórica, práctica, laboratorio). Con una interfaz de usuario intuitiva basada en AdminLTE y Bootstrap 5, y componentes interactivos como FullCalendar y DataTables, busca simplificar los procesos administrativos y mejorar la organización académica.

La arquitectura del sistema está diseñada para ser modular, escalable y mantenible, siguiendo el patrón Modelo-Vista-Controlador (MVC). Se destaca por su sistema de autenticación personalizado (basado en cédula de identidad y preguntas de seguridad) y funcionalidades administrativas clave como la gestión de respaldos y una bitácora de actividades detallada para auditoría y seguimiento.

---

## 📖 Tabla de Contenidos

- [🌟 Introducción y Descripción del Proyecto](#-introducción-y-descripción-del-proyecto)
- [🎯 Casos de Uso Principales](#-casos-de-uso-principales)
  - [Administrador del Sistema](#administrador-del-sistema)
  - [Coordinador Académico](#coordinador-académico)
- [🚀 Características Principales](#-características-principales)
- [🏗️ Arquitectura del Sistema](#️-arquitectura-del-sistema)
  - [Principios Arquitectónicos](#principios-arquitectónicos)
  - [Componentes Principales](#componentes-principales)
  - [Diagrama de Arquitectura](#diagrama-de-arquitectura)
- [🛠️ Tecnologías Utilizadas](#️-tecnologías-utilizadas)
- [📋 Requisitos del Sistema](#-requisitos-del-sistema)
- [⚙️ Flujo de Trabajo con Git](#️-flujo-de-trabajo-con-git)
- [🚀 Instalación](#-instalación)
- [📖 Uso del Sistema](#-uso-del-sistema)
- [🤝 Contribución](#-contribución)
- [📝 Licencia](#-licencia)
- [👨‍💻 Autores](#-autores)
- [🆘 Soporte](#-soporte)

---

## 🎯 Casos de Uso Principales

El sistema está diseñado para ser utilizado principalmente por dos roles con diferentes responsabilidades:

### Administrador del Sistema
El Administrador tiene control total sobre la configuración y los datos maestros del sistema.
-   **UC-ADM-001:** Gestionar Carreras (crear, editar, eliminar, listar).
-   **UC-ADM-002:** Gestionar Asignaturas (crear, editar, eliminar, listar, definir carga horaria).
-   **UC-ADM-003:** Gestionar Docentes (registrar, editar, eliminar, listar, asignar dedicaciones).
-   **UC-ADM-004:** Gestionar Secciones Académicas (crear, editar, eliminar, listar).
-   **UC-ADM-005:** Gestionar Semestres y Turnos (crear, editar, eliminar, listar).
-   **UC-ADM-006:** Gestionar Períodos Académicos (definir fechas de inicio y fin).
-   **UC-ADM-007:** Gestionar Usuarios Coordinadores (crear cuentas, asignar carreras, gestionar permisos básicos).
-   **UC-ADM-008:** Realizar y Restaurar Respaldos de la Base de Datos.
-   **UC-ADM-009:** Consultar Bitácora de Actividades del Sistema (auditoría).
-   **UC-ADM-010:** Configurar Parámetros Generales del Sistema.

### Coordinador Académico
El Coordinador Académico es responsable de la planificación y gestión de los horarios de las carreras que le han sido asignadas.
-   **UC-COO-001:** Crear y Configurar Horarios (seleccionar carrera, semestre, turno, período académico).
-   **UC-COO-002:** Asignar Asignaturas a Bloques Horarios.
-   **UC-COO-003:** Asignar Docentes a las Asignaturas dentro de los bloques horarios.
-   **UC-COO-004:** Definir Tipos de Horas (teórica, práctica, laboratorio) para cada bloque asignado.
-   **UC-COO-005:** Visualizar Horarios de forma interactiva (vista de calendario, tabla).
-   **UC-COO-006:** Modificar y Ajustar Horarios existentes.
-   **UC-COO-007:** Detectar y Resolver Conflictos horarios básicos (ej. docente duplicado en mismo bloque).
-   **UC-COO-008:** Consultar disponibilidad de docentes y aulas (implícito en la creación de horarios).
-   **UC-COO-009:** Utilizar su perfil para recuperar contraseña mediante preguntas de seguridad.

*(Nota: La visualización de horarios por parte de docentes y estudiantes se considera una extensión futura o una integración, ya que el sistema actual se enfoca en la administración y coordinación).*

---

## 🚀 Características Principales

### 📊 Gestión Integral de Entidades Académicas
- **Gestión de Carreras**: Administración completa de carreras universitarias.
- **Gestión de Asignaturas**: Control de materias con carga horaria diferenciada.
- **Gestión de Docentes**: Registro y administración de profesores con dedicaciones.
- **Gestión de Secciones**: Organización por secciones académicas.
- **Gestión de Semestres**: Control de períodos académicos por carrera.
- **Gestión de Turnos**: Administración de turnos (mañana, tarde, noche).

### 🗓️ Sistema de Horarios Avanzado
- **Horarios Flexibles**: Soporte para horarios recurrentes y fechas específicas.
- **Gestión por Bloques**: Sistema de bloques de 45 minutos para mayor flexibilidad.
- **Tipos de Horas**: Diferenciación entre horas teóricas, prácticas y de laboratorio.
- **Calendario Interactivo**: Visualización intuitiva de horarios (usando FullCalendar).
- **Soft Deletes**: Eliminación lógica para mantener historial y permitir recuperación.

### 👥 Sistema de Usuarios y Permisos
- **Autenticación Segura**: Sistema de login con "cédula" como identificador principal.
- **Preguntas de Seguridad**: Recuperación de contraseña mediante preguntas personalizadas.
- **Gestión de Coordinadores**: Administración de coordinadores por carrera (modelo `User`).

### 📈 Funcionalidades Administrativas
- **Períodos Académicos**: Gestión de períodos con fechas de inicio y fin.
- **Sistema de Respaldos**: Backup y restauración de la base de datos.
- **Bitácora de Actividades**: Registro completo de acciones del sistema para auditoría.
- **Filtros Avanzados**: Búsqueda y filtrado por múltiples criterios en las interfaces (usando DataTables).

### 🔗 Relaciones Complejas
- **Asignatura-Docente**: Relación muchos a muchos para asignación flexible.
- **Asignatura-Sección**: Configuración por carrera, semestre y turno.
- **Carga Horaria**: Control detallado de horas académicas por tipo.

---

## 🏗️ Arquitectura del Sistema

### Principios Arquitectónicos
El sistema se adhiere a los siguientes principios:
-   **Modularidad:** Separación de preocupaciones en componentes bien definidos (Modelos, Vistas, Controladores, Servicios).
-   **Escalabilidad:** Diseñado para manejar un crecimiento en datos y usuarios, aprovechando las capacidades de Laravel y la base de datos relacional.
-   **Mantenibilidad:** Código organizado y comentado, siguiendo las convenciones de Laravel para facilitar actualizaciones y correcciones.
-   **Seguridad:** Protección contra vulnerabilidades comunes (XSS, CSRF) mediante las herramientas de Laravel, y un sistema de autenticación robusto.

### Componentes Principales
-   **Framework Base:** Laravel 10.x, proporcionando la estructura MVC, ORM (Eloquent), sistema de rutas, plantillas Blade, y más.
-   **Capa de Presentación (Frontend):**
    -   **Plantillas Blade:** Para la renderización dinámica de HTML.
    -   **Bootstrap 5 & AdminLTE:** Para un diseño responsivo y una interfaz de administración profesional.
    -   **JavaScript (Vite, FullCalendar, DataTables):** Para interactividad y mejora de la experiencia de usuario.
-   **Capa de Aplicación (Backend):**
    -   **Controladores:** Gestionan las solicitudes HTTP, interactúan con los modelos y devuelven respuestas (vistas o JSON).
    -   **Modelos Eloquent:** Representan las entidades de la base de datos y manejan la lógica de negocio relacionada con los datos. El modelo `Horario` es central.
    -   **Middleware:** Para filtrar solicitudes HTTP (ej. autenticación, CSRF).
    -   **Sistema de Rutas:** Define los endpoints de la aplicación y los asocia a los controladores.
    -   **Módulos Funcionales:** Agrupación lógica de controladores y modelos para funcionalidades específicas (Gestión Académica, Gestión de Horarios, Administración).
-   **Capa de Datos:**
    -   **Base de Datos Relacional:** MySQL o PostgreSQL.
    -   **Migraciones y Seeders de Laravel:** Para la gestión del esquema de la base de datos y la carga de datos iniciales.
-   **Autenticación y Autorización:**
    -   Sistema de autenticación personalizado (cédula + contraseña).
    -   Recuperación de contraseña mediante preguntas de seguridad.
    -   Protección de rutas para roles específicos (Coordinadores, Administradores).

### Diagrama de Arquitectura
Este diagrama ilustra las principales capas y componentes del sistema y cómo interactúan.

```mermaid
graph TD
    %% Define styles for layers
    classDef frontend fill:#E6F3FF,stroke:#333,stroke-width:2px;
    classDef applogic fill:#E6FFE6,stroke:#333,stroke-width:2px;
    classDef datalayer fill:#FFF5E6,stroke:#333,stroke-width:2px;
    classDef infra fill:#F0F0F0,stroke:#333,stroke-width:2px;
    classDef user fill:#FFEBCC,stroke:#333,stroke-width:2px;

    %% Users
    AdminUser["Administrador"]:::user
    CoordinatorUser["Coordinador"]:::user

    subgraph "Capa de Presentación (UI/Frontend)"
        direction LR
        Browser["Navegador Web"]
        subgraph "Interfaz de Usuario"
            Blade["Plantillas Blade"]
            Assets["Assets (CSS/JS via Vite)"]
            Bootstrap["Bootstrap 5 & AdminLTE"]
            JSComp["FullCalendar, DataTables"]
        end
    end
    class Browser,Blade,Assets,Bootstrap,JSComp frontend;

    subgraph "Capa de Aplicación (Backend - Laravel)"
        direction TB
        HTTPKernel["Kernel HTTP (Middleware)"]
        Routing["Sistema de Rutas"]
        
        subgraph "Módulos de Controladores"
            AuthController["AuthController (Login, SecQ)"]
            AcademicMgmtCtrl["Gestión Académica (Carreras, Asignaturas, etc.)"]
            PersonnelMgmtCtrl["Gestión de Personal (Docentes, Coords)"]
            ScheduleMgmtCtrl["Gestión de Horarios (HorarioController)"]
            AdminSysCtrl["Admin. Sistema (Respaldos, Bitácora)"]
        end
        
        Services["Servicios y Lógica de Negocio Adicional"]
    end
    class HTTPKernel,Routing,AuthController,AcademicMgmtCtrl,PersonnelMgmtCtrl,ScheduleMgmtCtrl,AdminSysCtrl,Services applogic;

    subgraph "Capa de Datos y Dominio"
        direction TB
        EloquentModels["Modelos Eloquent (Horario, User, Asignatura, etc.)"]
        DBAL["Abstracción de BD (PDO)"]
        subgraph "Base de Datos"
            MySQL_PG["MySQL / PostgreSQL"]
            Migrations["Migraciones y Seeders"]
        end
    end
    class EloquentModels,DBAL,MySQL_PG,Migrations datalayer;

    subgraph "Infraestructura y Entorno"
        PHPRuntime["PHP 8.1+"]
        WebServer["Servidor Web (Nginx/Apache)"]
        Composer["Composer (Dependencias PHP)"]
        NodeJS["Node.js/NPM (Dependencias JS, Vite)"]
    end
    class PHPRuntime,WebServer,Composer,NodeJS infra;

    %% Interactions
    AdminUser --> Browser
    CoordinatorUser --> Browser
    Browser -- HTTP Requests --> HTTPKernel
    HTTPKernel -- Pasa a --> Routing
    Routing -- Despacha a --> AuthController
    Routing -- Despacha a --> AcademicMgmtCtrl
    Routing -- Despacha a --> PersonnelMgmtCtrl
    Routing -- Despacha a --> ScheduleMgmtCtrl
    Routing -- Despacha a --> AdminSysCtrl
    
    AuthController -- Usa --> EloquentModels
    AcademicMgmtCtrl -- Usa --> EloquentModels
    PersonnelMgmtCtrl -- Usa --> EloquentModels
    ScheduleMgmtCtrl -- Usa --> EloquentModels
    AdminSysCtrl -- Usa --> EloquentModels
    Services -- Usado por/Usa --> AuthController
    Services -- Usado por/Usa --> AcademicMgmtCtrl
    Services -- Usado por/Usa --> PersonnelMgmtCtrl
    Services -- Usado por/Usa --> ScheduleMgmtCtrl
    Services -- Usado por/Usa --> AdminSysCtrl
    
    EloquentModels -- Interactúa vía DBAL con --> MySQL_PG
    Migrations -- Modifican esquema de --> MySQL_PG
    
    HTTPKernel -- Retorna HTTP Responses (Vistas Blade/JSON) --> Browser
    Blade -- Renderiza HTML con datos de --> AuthController
    Blade -- Renderiza HTML con datos de --> AcademicMgmtCtrl
    Blade -- Renderiza HTML con datos de --> PersonnelMgmtCtrl
    Blade -- Renderiza HTML con datos de --> ScheduleMgmtCtrl
    Blade -- Renderiza HTML con datos de --> AdminSysCtrl
    Assets -- Servidos a --> Browser

    %% Infrastructure Support
    PHPRuntime -- Ejecuta --> HTTPKernel
    WebServer -- Recibe de/Envía a --> Browser
    WebServer -- Sirve --> PHPRuntime
    Composer -- Gestiona dependencias de --> PHPRuntime
    NodeJS -- Compila --> Assets
````

-----

## 🛠️ Tecnologías Utilizadas

  - **Framework**: Laravel 10.x
  - **PHP**: ^8.1
  - **Base de Datos**: MySQL 5.7+ / PostgreSQL 10+
  - **Frontend**:
      - Plantillas Blade
      - Bootstrap 5
      - AdminLTE (para el panel de administración)
      - FullCalendar (para visualización de horarios)
      - DataTables (para tablas de datos interactivas)
  - **Autenticación**: Laravel UI (personalizado para usar "cédula" y preguntas de seguridad)
  - **Compilación de Assets**: Vite
  - **Servidor Web**: (Recomendado Nginx o Apache)

-----

## 📋 Requisitos del Sistema

  - PHP 8.1 o superior
  - Composer (para dependencias de PHP)
  - MySQL 5.7+ o PostgreSQL 10+
  - Node.js y NPM (para la compilación de assets de frontend)
  - Extensiones PHP requeridas:
      - BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

-----

## ⚙️ Flujo de Trabajo con Git

Este proyecto utiliza un flujo de trabajo basado en ramas de características (Feature Branch Workflow), similar a Gitflow pero simplificado.

1.  **Ramas Principales:**

      * `main`: Contiene el código de producción estable. Solo se fusiona desde `develop` para los lanzamientos. No se hacen commits directos.
      * `develop`: Rama de integración principal. Contiene las últimas características desarrolladas y es la base para las nuevas funcionalidades. Es la rama de "próximo lanzamiento".

2.  **Ramas de Soporte:**

      * **Ramas de Característica (`feature/nombre-caracteristica`):**
          * Se crean a partir de `develop`.
          * Ej: `feature/gestion-aulas`, `feature/reporte-docentes`.
          * Una vez completada la característica, se fusiona de nuevo en `develop` mediante un Pull Request (PR).
      * **Ramas de Corrección (`bugfix/nombre-correccion`):**
          * Se crean a partir de `develop` para corregir errores no críticos.
          * Se fusionan de nuevo en `develop` mediante un PR.
      * **Ramas de Hotfix (`hotfix/nombre-hotfix`):**
          * Se crean a partir de `main` para corregir errores críticos en producción.
          * Una vez completado, el hotfix se fusiona tanto en `main` como en `develop` (para asegurar que la corrección también esté en el desarrollo futuro) y se etiqueta en `main`.

3.  **Proceso General:**

    1.  Asegúrate de que tu copia local de `main` y `develop` esté actualizada:
        ```bash
        git checkout main
        git pull origin main
        git checkout develop
        git pull origin develop
        ```
    2.  Para una nueva característica, crea una rama a partir de `develop`:
        ```bash
        git checkout -b feature/mi-nueva-caracteristica develop
        ```
    3.  Trabaja en tu característica, haciendo commits pequeños y descriptivos:
        ```bash
        # ...haces cambios...
        git add .
        git commit -m "Implementa X parte de mi-nueva-caracteristica"
        ```
    4.  Publica tu rama de característica en el repositorio remoto:
        ```bash
        git push origin feature/mi-nueva-caracteristica
        ```
    5.  Cuando la característica esté completa (y probada localmente), crea un Pull Request (PR) en GitHub desde tu rama `feature/mi-nueva-caracteristica` hacia `develop`.
    6.  El PR será revisado por otros miembros del equipo. Se pueden solicitar cambios.
    7.  Una vez aprobado, el PR se fusiona (merge) en `develop`.
    8.  Para un lanzamiento (release), se crea un PR desde `develop` hacia `main`. Una vez fusionado, se crea una etiqueta (tag) en `main`:
        ```bash
        git checkout main
        git merge develop
        git tag -a v1.0.0 -m "Versión 1.0.0"
        git push origin main --tags
        ```

-----

## 🚀 Instalación

Sigue estos pasos para configurar el proyecto en tu entorno local:

1.  **Clonar el repositorio**

    ```bash
    git clone [https://github.com/D13G0ARJ/horario-universidad-.git](https://github.com/D13G0ARJ/horario-universidad-.git)
    cd horario-universidad-
    ```

2.  **Instalar dependencias de PHP**

    ```bash
    composer install
    ```

3.  **Instalar dependencias de Node.js**

    ```bash
    npm install
    ```

4.  **Configurar el archivo de entorno**
    Copia el archivo de ejemplo y genera la clave de la aplicación:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5.  **Configurar la base de datos**
    Edita el archivo `.env` con las credenciales de tu base de datos:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=horario_universidad
    DB_USERNAME=tu_usuario
    DB_PASSWORD=tu_contraseña
    ```

6.  **Ejecutar las migraciones (y opcionalmente, seeders)**
    Esto creará las tablas necesarias en tu base de datos.

    ```bash
    php artisan migrate
    ```

    Para poblar la base de datos con datos de prueba (si los seeders están configurados):

    ```bash
    # php artisan db:seed
    ```

7.  **Compilar assets de frontend**
    Para producción:

    ```bash
    npm run build
    ```

    Para desarrollo (con hot-reloading):

    ```bash
    npm run dev
    ```

8.  **Iniciar el servidor de desarrollo Laravel**

    ```bash
    php artisan serve
    ```

    La aplicación estará disponible en `http://127.0.0.1:8000` por defecto.

-----

## 📖 Uso del Sistema

Una vez instalado y en ejecución, puedes acceder al sistema a través de tu navegador web.

### Acceso Inicial

  - Dirígete a la URL de tu aplicación (ej. `http://127.0.0.1:8000`).
  - Utiliza las credenciales proporcionadas o creadas durante la configuración inicial (puede requerir un usuario administrador inicial creado mediante seeders o manualmente).

### Navegación

  - El panel lateral (sidebar) proporciona acceso a los diferentes módulos:
      - **Gestión Académica:** Para administrar carreras, asignaturas, secciones, etc.
      - **Gestión de Personal:** Para administrar docentes y coordinadores.
      - **Gestión de Horarios:** El módulo central para crear, ver y modificar horarios.
      - **Administración del Sistema:** Para gestionar respaldos, ver la bitácora, etc.

### Flujos Comunes

  - **Creación de un nuevo horario:**
    1.  Navega a la sección de "Horarios".
    2.  Selecciona "Crear Nuevo Horario".
    3.  Elige la carrera, semestre, turno y período académico.
    4.  Utiliza la interfaz de calendario o tabla para arrastrar o seleccionar asignaturas y docentes en los bloques horarios disponibles.
    5.  Define el tipo de hora (teórica, práctica, laboratorio) para cada asignación.
    6.  Guarda el horario.
  - **Administración de una entidad (ej. Carrera):**
    1.  Navega a "Gestión Académica" -\> "Carreras".
    2.  Visualiza la lista de carreras existentes.
    3.  Utiliza los botones para "Crear Nueva", "Editar" o "Eliminar" carreras.

### Seguridad y Perfil

  - Si olvidas tu contraseña, utiliza la opción de "Recuperar Contraseña" en la pantalla de login, que te guiará a través de tus preguntas de seguridad.
  - Puedes gestionar tu perfil y cambiar tus preguntas de seguridad una vez autenticado.

-----

## 🤝 Contribución

¡Las contribuciones son el corazón del código abierto\! Cualquier contribución que hagas será **muy apreciada**.

1.  Haz un Fork del Proyecto.
2.  Sigue el [Flujo de Trabajo con Git](https://www.google.com/search?q=%23%EF%B8%8F-flujo-de-trabajo-con-git) descrito anteriormente, especialmente creando tu rama de característica (`git checkout -b feature/AmazingFeature develop`).
3.  Realiza tus cambios y haz Commit (`git commit -m 'Add some AmazingFeature'`).
4.  Haz Push a tu rama (`git push origin feature/AmazingFeature`).
5.  Abre un Pull Request hacia la rama `develop` del repositorio original.

Asegúrate de que tu código siga los estándares del proyecto, esté bien documentado y, si es posible, incluya pruebas.

-----

## 📝 Licencia

Este proyecto está bajo la Licencia MIT. Consulta el archivo [LICENSE](https://www.google.com/search?q=LICENSE) para más detalles.

-----

## 👨‍💻 Autores

  - **Diego Rodriguez** - [D13G0ARJ](https://github.com/D13G0ARJ)
  - **Alexander Azocar** - [AlexanderAzocar](https://github.com/AlexanderAzocar)
  - **Cristhian Blanco** - [NoSoyCrisman](https://github.com/NoSoyCrisman)

-----

## 🆘 Soporte

Si encuentras algún problema, tienes alguna pregunta o sugerencia para mejorar el sistema:

1.  Revisa la sección de [Issues](https://github.com/D13G0ARJ/horario-universidad-/issues) para ver si ya ha sido reportado o discutido.
2.  Si no existe un issue similar, por favor [crea uno nuevo](https://www.google.com/search?q=https://github.com/D13G0ARJ/horario-universidad-/issues/new/choose), proporcionando la mayor cantidad de detalles posible:
      - Pasos para reproducir el error.
      - Comportamiento esperado vs. comportamiento actual.
      - Capturas de pantalla (si aplica).
      - Versión del sistema, navegador, etc.

-----

⭐ ¡No olvides dar una estrella al proyecto si te ha sido útil o te parece interesante\! ⭐

```
```
