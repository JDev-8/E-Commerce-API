# 🛒 API E-commerce Profesional

API robusta y escalable para una plataforma de comercio electrónico, desarrollada con **Laravel** y **PostgreSQL**. Este proyecto implementa un flujo de compra completo, desde la gestión de inventario hasta el procesamiento de pagos reales con **Stripe**.

## 🚀 Características Principales

-   **Autenticación Segura**: Sistema de Login/Registro utilizando Laravel Sanctum (Tokens JWT).

-   **Roles y Permisos**: Middleware personalizado para separar lógica de Clientes y Administradores.

-   **Gestión de Catálogo**: CRUD completo para Productos y Categorías con validación de datos.

-   **Carrito de Compras Persistente**: Lógica de negocio para manejar stock en tiempo real y persistencia en base de datos.

-   **Pasarela de Pagos (Stripe)**:

    -   Generación de intentos de pago (Payment Intents).

    -   Confirmación segura desde el backend.

    -   Manejo de transacciones atómicas (`DB::transaction`) para asegurar la integridad de datos.

-   **Historial de Órdenes**: Registro detallado de pedidos y estados (Pendiente, Pagado).

## 🛠️ Stack Tecnológico

-   **Lenguaje**: PHP 8.2

-   **Framework**: Laravel 11

-   **Base de Datos**: PostgreSQL

-   **Pagos**: Stripe SDK

-   **Herramientas**: Insomnia/Postman, Composer, Git.

## 🗄️ Modelo de Base de Datos

El sistema utiliza una arquitectura relacional normalizada.

```sql
  USUARIOS {
    bigint id
    string nombre_usuario
    string correo_electronico
    string password
    boolean is_admin
    }
    PRODUCTOS {
        bigint id
        string nombre
        integer precio
        integer stock
        string slug
    }
    ORDENES {
        bigint id
        string estado
        integer pago_total
    }
```

## ⚙️ Instalación y Configuración

Sigue estos pasos para desplegar el proyecto en tu entorno local:

### 1. Clonar el Repositorio

```
git clone [https://github.com/JDev-8/E-Commerce-API.git](https://github.com/JDev-8/E-Commerce-API.git)
cd E-Commerce-API

```

### 2. Instalar Dependencias

```
composer install

```

## 3. Configurar Entorno

Copia el archivo de ejemplo y genera la llave de aplicación.

```
cp .env.example .env
php artisan key:generate

```

## 4. Configurar Base de Datos

Abre el archivo .env y configura tus credenciales de PostgreSQL:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=api_ecommerce
DB_USERNAME=postgres
DB_PASSWORD=tu_password

```

## 5. Configurar Stripe

Agrega tus llaves de prueba de Stripe en el .env (Obtenlas en dashboard.stripe.com):

```
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
```

## 6. Migrar Base de Datos

```
php artisan migrate
```

## 7. Ejecutar Servidor

```
php artisan serve
```

La API estará disponible en http://127.0.0.1:8000.

## 📡 Documentación de Endpoints

### 🔐 Autenticación

| Método | Endpoint        | Descripción                           |
| :----- | :-------------- | :------------------------------------ |
| POST   | `/api/register` | Crear cuenta nueva                    |
| POST   | `/api/login`    | Iniciar sesión (Retorna Token Bearer) |
| POST   | `/api/logout`   | Cerrar sesión                         |

### 📦 Productos (Público)

| Método | Endpoint              | Descripción             |
| :----- | :-------------------- | :---------------------- |
| GET    | `/api/productos`      | Listar productos        |
| GET    | `/api/productos/{id}` | Ver detalle de producto |
| GET    | `/api/categorias`     | Listar categorías       |

### 🛒 Carrito de Compras (Requiere Auth)

| Método | Endpoint                | Descripción                              |
| :----- | :---------------------- | :--------------------------------------- |
| GET    | `/api/carrito`          | Ver mi carrito                           |
| POST   | `/api/carrito`          | Agregar item (`producto_id`, `cantidad`) |
| DELETE | `/api/carrito/{itemId}` | Quitar item del carrito                  |

### 💳 Checkout y Órdenes (Requiere Auth)

| Método | Endpoint                | Descripción                                              |
| :----- | :---------------------- | :------------------------------------------------------- |
| POST   | `/api/checkout`         | Iniciar pago (Retorna `clientSecret` y `IntentoPagarId`) |
| POST   | `/api/checkout/confirm` | Confirmar pago (`payment_intent_id`)                     |
| GET    | `/api/mis-ordenes`      | Ver historial de compras                                 |

### 🛡️ Administración (Requiere is_admin=true)

| Método | Endpoint              | Descripción         |
| :----- | :-------------------- | :------------------ |
| POST   | `/api/productos`      | Crear producto      |
| PUT    | `/api/productos/{id}` | Actualizar producto |
| DELETE | `/api/productos/{id}` | Eliminar producto   |
| POST   | `/api/categorias`     | Crear categoría     |

## 🧪 Testing

Para probar la API, se recomienda usar Insomnia o Postman.
Recuerda enviar el Header `Accept: application/json` en todas las peticiones.

Desarrollado con ❤️ para el portafolio de Backend Developer.
