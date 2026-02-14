# Tarea9 - Pokédex API REST

Aplicación web desarrollada con arquitectura **MVC en PHP 8.4** que integra la **PokeAPI** como servicio web externo. Este proyecto demuestra patrones modernos de desarrollo web, consumo de APIs REST, responsividad total y buenas prácticas de documentación con PHPDoc.

**Estado:** ✅ Completado y Verificado | **Versión:** 1.1.0 | **Fecha:** 13 de Febrero de 2025

## 📋 Tabla de Contenidos

- [Características](#características)
- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Funcionalidades](#funcionalidades)
- [API Integrada](#api-integrada)
- [Documentación PHPDoc](#documentación-phpdoc)
- [Pruebas](#pruebas)
- [Tecnologías Utilizadas](#tecnologías-utilizadas)

## ✨ Características

- **Arquitectura MVC**: Separación clara entre Modelos, Vistas y Controladores
- **Integración PokeAPI**: Consumo de la API PokeAPI v2 en tiempo real
- **Sistema de Enrutamiento**: Router personalizado para manejar rutas HTTP
- **Gestión de Datos**: Sistema de módulos (Libros, Autores, Pokémon)
- **Diseño Responsivo**: Interfaz moderna con Tailwind CSS
- **Documentación Automática**: PHPDoc generada automáticamente
- **Servicios HTTP**: Clase HttpClient para consumo de APIs externas

## 🔧 Requisitos

- PHP 8.4 o superior
- cURL habilitado en PHP
- Composer
- Docker (opcional, para usar docker-compose)

## 📦 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/usuario/tarea-pokeApi.git
cd tarea-pokeApi
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar permisos (Linux/Mac)

```bash
chmod -R 755 src/ public/
```

### 4. Configurar variables de entorno

```bash
cp .env.example .env
```

### 5. Iniciar el servidor (desarrollo)

```bash
docker-compose up -d
```

### 6. Acceder a la aplicación

Abre tu navegador en: `http://localhost:8080`

## 📁 Estructura del Proyecto

```
Docker-mvc-template/
├── Core/                          # Núcleo de la aplicación
│   ├── HttpClient.php             # Cliente HTTP para consumo de APIs
│   ├── Router.php                 # Sistema de enrutamiento
│   ├── Database.php               # Conexión a BD
│   ├── Session.php                # Gestión de sesiones
│   ├── Validator.php              # Validación de datos
│   ├── Response.php               # Manejo de respuestas
│   ├── functions.php              # Funciones globales
│   └── Middleware/                # Middleware personalizado
├── src/
│   ├── Controllers/               # Controladores
│   │   ├── HomeController.php
│   │   ├── LibroController.php
│   │   ├── AutorController.php
│   │   ├── PokemonController.php  # ← Nuevo
│   │   └── ApiTestsController.php # ← Nuevo
│   ├── Models/
│   │   ├── Entity/                # Entidades
│   │   │   ├── Libro.php
│   │   │   ├── Autor.php
│   │   │   └── Pokemon.php        # ← Nuevo
│   │   ├── Repository/            # Repositorios
│   │   └── Service/               # Servicios
│   │       ├── LibroService.php
│   │       ├── AutorService.php
│   │       └── PokemonService.php # ← Nuevo
│   └── views/                     # Vistas
│       ├── layouts/               # Plantillas base
│       ├── components/            # Componentes reutilizables
│       │   └── pokemon-card.php   # ← Nuevo
│       └── pages/
│           ├── home.php
│           ├── libros/
│           ├── autores/
│           ├── pokemons/          # ← Nuevo
│           │   ├── index.php
│           │   ├── show.php
│           │   ├── search.php
│           │   └── filter-by-type.php
│           └── api-tests/         # ← Nuevo
│               └── index.php
├── public/                        # Archivos públicos
│   ├── index.php                  # Punto de entrada
│   └── assets/
├── doc/                           # Documentación generada
├── vendor/                        # Dependencias
├── routes.php                     # Definición de rutas
├── composer.json                  # Dependencias del proyecto
└── README.md

```

## 🚀 Funcionalidades

### 1. Pokédex (PokeAPI) ⭐
- **Listar Pokémon**: Visualizar lista paginada de Pokémon
- **Ver Detalles**: Información completa de cada Pokémon
- **Buscar por Nombre**: Búsqueda interactiva por nombre
- **Filtrar por Tipo**: Agrupar Pokémon por tipo (fuego, agua, etc.)

### 2. Pruebas API 🧪
- Página interactiva con 5 formularios de prueba
- Endpoints JSON disponibles para consumo externo
- Documentación de cada endpoint

## 🔌 API Integrada

### PokeAPI v2

**URL Base**: `https://pokeapi.co/api/v2/`

**Documentación**: https://pokeapi.co/docs/v2

#### Endpoints Utilizados

| Endpoint | Descripción | Método |
|----------|-------------|--------|
| `/pokemon/{id}` | Obtener Pokémon por ID | GET |
| `/pokemon/{name}` | Buscar Pokémon por nombre | GET |
| `/pokemon?limit=20&offset=0` | Listar Pokémon paginado | GET |
| `/type/{type}` | Obtener Pokémon de un tipo | GET |
| `/type?limit=100` | Listar todos los tipos | GET |

#### Ejemplo de Uso (cURL)

```bash
# Obtener Pokémon por ID
curl https://pokeapi.co/api/v2/pokemon/25

# Buscar por nombre
curl https://pokeapi.co/api/v2/pokemon/pikachu

# Obtener Pokémon de tipo fuego
curl https://pokeapi.co/api/v2/type/fire
```

## 📚 Documentación PHPDoc

### Generar Documentación

```bash
# Usar PHPDocumentor (instalado vía composer)
php vendor/bin/phpdoc -d src/ -t doc/

# O manualmente
php phpDocumentor.phar -d c:\ruta\proyecto -t c:\ruta\documentacion
```

### Ver Documentación

1. Abre `doc/index.html` en tu navegador
2. Navega por las clases, funciones y parámetros documentados

### Estándares PHPDoc Utilizados

Todos los archivos incluyen comentarios estructurados:

```php
/**
 * Descripción breve de la función
 *
 * Descripción detallada (opcional) que explica
 * qué hace la función, cómo se utiliza, etc.
 *
 * @param type $parameter Descripción del parámetro
 * @param type $parameter2 Descripción del parámetro 2
 *
 * @return type Descripción del valor retornado
 *
 * @throws Exception Excepción que puede lanzarse
 */
```

### Clases Documentadas

- **HttpClient**: Cliente HTTP para peticiones a servicios web
- **PokemonService**: Servicio para consumir PokeAPI
- **PokemonController**: Controlador de Pokémon
- **Pokemon**: Entidad de Pokémon
- **ApiTestsController**: Controlador de pruebas

## 🧪 Pruebas

### Página Interactiva de Pruebas

Accede a `http://localhost:8000/api/tests` para probar los endpoints.

**Funcionalidades:**
- Formularios para cada endpoint
- Visualización de respuestas JSON en tiempo real
- Ejemplos predefinidos

### Endpoints de Prueba

```
GET /api/tests/get-pokemon-by-id?id=25
GET /api/tests/search-pokemon?name=pikachu
GET /api/tests/get-pokemons?limit=10&offset=0
GET /api/tests/filter-by-type?type=fire
GET /api/tests/get-all-types
```

## 💻 Tecnologías Utilizadas

| Tecnología | Versión | Uso |
|------------|---------|-----|
| PHP | 8.4+ | Lenguaje principal |
| cURL | Sistema | Peticiones HTTP |
| Composer | 2.x | Gestor de dependencias |
| PHPDocumentor | 3.9+ | Generación de documentación |
| Tailwind CSS | 3.x | Framework CSS |
| Docker | 20.x+ | Containerización (opcional) |

## 📖 Rutas Disponibles

| Ruta | Método | Descripción |
|------|--------|-------------|
| `/` | GET | Página de inicio |
| `/libros` | GET | Listar libros |
| `/libro` | GET | Ver detalles de libro |
| `/libro/crear` | GET | Formulario crear libro |
| `/libro` | POST | Guardar libro |
| `/libro` | DELETE | Eliminar libro |
| `/autores` | GET | Listar autores |
| `/autor` | GET | Ver detalles de autor |
| `/autor/crear` | GET | Formulario crear autor |
| `/autor` | POST | Guardar autor |
| `/autor` | DELETE | Eliminar autor |
| `/pokemons` | GET | Listar Pokémon |
| `/pokemon` | GET | Ver detalles de Pokémon |
| `/pokemon/search` | GET | Buscar Pokémon |
| `/pokemon/filter-by-type` | GET | Filtrar por tipo |
| `/api/tests` | GET | Página de pruebas |
| `/api/tests/get-pokemon-by-id` | GET | Obtener Pokémon por ID (JSON) |
| `/api/tests/search-pokemon` | GET | Buscar Pokémon (JSON) |
| `/api/tests/get-pokemons` | GET | Listar Pokémon paginado (JSON) |
| `/api/tests/filter-by-type` | GET | Filtrar por tipo (JSON) |
| `/api/tests/get-all-types` | GET | Obtener tipos (JSON) |

## 🔒 Características de Seguridad

- Escape de datos con `htmlspecialchars()`
- Validación de parámetros GET/POST
- Tipos estrictos en PHP 8.4
- Manejo seguro de sesiones
- Validación de errores HTTP

## 📝 Licencia

Este proyecto está bajo licencia MIT.

## 👤 Autor

Desarrollado como proyecto educativo para demostrar integración de APIs externas en aplicaciones MVC.

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor:

1. Fork el repositorio
2. Crea una rama para tu feature
3. Commit tus cambios
4. Push a la rama
5. Abre un Pull Request

## 📞 Soporte

Para reportar bugs o sugerir mejoras, abre un issue en GitHub.

---

**Última actualización**: 2025-02-13
**Versión**: 1.0.0
