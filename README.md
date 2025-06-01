


# 📅 Sistema de Gestión de Horarios Universitarios

Un sistema completo de gestión de horarios académicos desarrollado con Laravel, diseñado para instituciones educativas que necesitan organizar y administrar horarios de clases de manera eficiente.

## 🚀 Características Principales

### 📊 Gestión Integral de Entidades Académicas
- **Gestión de Carreras**: Administración completa de carreras universitarias [1](#0-0) 
- **Gestión de Asignaturas**: Control de materias con carga horaria diferenciada [2](#0-1) 
- **Gestión de Docentes**: Registro y administración de profesores con dedicaciones [3](#0-2) 
- **Gestión de Secciones**: Organización por secciones académicas [4](#0-3) 
- **Gestión de Semestres**: Control de períodos académicos por carrera
- **Gestión de Turnos**: Administración de turnos (mañana, tarde, noche)

### 🗓️ Sistema de Horarios Avanzado
- **Horarios Flexibles**: Soporte para horarios recurrentes y fechas específicas [5](#0-4) 
- **Gestión por Bloques**: Sistema de bloques de 45 minutos para mayor flexibilidad [6](#0-5) 
- **Tipos de Horas**: Diferenciación entre horas teóricas, prácticas y de laboratorio [7](#0-6) 
- **Calendario Interactivo**: Visualización intuitiva de horarios
- **Soft Deletes**: Eliminación lógica para mantener historial [8](#0-7) 

### 👥 Sistema de Usuarios y Permisos
- **Autenticación Segura**: Sistema de login con recuperación de contraseña
- **Preguntas de Seguridad**: Recuperación de contraseña mediante preguntas personalizadas [9](#0-8) 
- **Gestión de Coordinadores**: Administración de coordinadores por carrera [10](#0-9) 

### 📈 Funcionalidades Administrativas
- **Períodos Académicos**: Gestión de períodos con fechas de inicio y fin [11](#0-10) 
- **Sistema de Respaldos**: Backup y restauración de datos [12](#0-11) 
- **Bitácora de Actividades**: Registro completo de acciones del sistema [13](#0-12) 
- **Filtros Avanzados**: Búsqueda y filtrado por múltiples criterios

### 🔗 Relaciones Complejas
- **Asignatura-Docente**: Relación muchos a muchos para asignación flexible [14](#0-13) 
- **Asignatura-Sección**: Configuración por carrera, semestre y turno [15](#0-14) 
- **Carga Horaria**: Control detallado de horas académicas por tipo [16](#0-15) 

## 🛠️ Tecnologías Utilizadas

- **Framework**: Laravel 10.x [17](#0-16) 
- **PHP**: ^8.1 [18](#0-17) 
- **Base de Datos**: MySQL/PostgreSQL
- **Frontend**: Blade Templates + Bootstrap
- **Autenticación**: Laravel UI [19](#0-18) 
- **Assets**: Vite para compilación [20](#0-19) 

## 📋 Requisitos del Sistema

- PHP 8.1 o superior
- Composer
- MySQL 5.7+ o PostgreSQL 10+
- Node.js y NPM (para assets)
- Extensiones PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

## 🚀 Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/D13G0ARJ/horario-universidad-.git
cd horario-universidad-
```

2. **Instalar dependencias de PHP**
```bash
composer install
```

3. **Instalar dependencias de Node.js**
```bash
npm install
```

4. **Configurar el archivo de entorno**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configurar la base de datos**
Editar el archivo `.env` con las credenciales de tu base de datos:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=horario_universidad
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

6. **Ejecutar las migraciones**
```bash
php artisan migrate
```

7. **Compilar assets**
```bash
npm run build
```

8. **Iniciar el servidor**
```bash
php artisan serve
```

## 📖 Uso del Sistema

### Gestión de Horarios
1. Accede al módulo de horarios [21](#0-20) 
2. Selecciona la carrera, semestre y turno
3. Asigna asignaturas, docentes y bloques horarios
4. Define el tipo de horas (teórica, práctica, laboratorio)
5. Establece la duración en bloques de 45 minutos

### Administración de Entidades
- **Carreras**: Crear y gestionar carreras universitarias
- **Asignaturas**: Definir materias con su carga horaria
- **Docentes**: Registrar profesores con sus dedicaciones
- **Secciones**: Organizar grupos de estudiantes
- **Períodos**: Configurar períodos académicos

### Respaldos y Seguridad
- Generar respaldos automáticos de la base de datos
- Restaurar datos desde respaldos previos
- Consultar la bitácora de actividades del sistema

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📝 Licencia

Este proyecto está bajo la Licencia MIT [22](#0-21)  - ver el archivo LICENSE para más detalles.

## 👨‍💻 Autor

**Diego Rodriguez** - [GitHub](https://github.com/D13G0ARJ)

**Alexander Azocar** - [Github](https://github.com/AlexanderAzocar)

**Cristhian Blanco** - [Github](https://github.com/NoSoyCrisman)

## 🆘 Soporte

Si encuentras algún problema o tienes sugerencias, por favor:

1. Revisa los [Issues](https://github.com/D13G0ARJ/horario-universidad-/issues) existentes
2. Crea un nuevo Issue si no existe uno similar
3. Proporciona información detallada sobre el problema

---

⭐ ¡No olvides dar una estrella al proyecto si te fue útil!

## Notes

Este README se ha creado basándose en el análisis completo del código fuente del proyecto. El sistema incluye una arquitectura robusta con modelos Eloquent bien estructurados, controladores organizados y un sistema de rutas completo. Las características identificadas incluyen gestión avanzada de horarios con bloques flexibles, sistema de autenticación con recuperación por preguntas de seguridad, y funcionalidades administrativas como respaldos y bitácora. El proyecto utiliza Laravel 10 con PHP 8.1+ y está configurado para un entorno de producción profesional.
