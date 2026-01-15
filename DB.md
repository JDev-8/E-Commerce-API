# Api E-commerce

## Entidades

### usuarios

-   id **(PK)**
-   nombres
-   apellidos
-   cedula **(UQ)**
-   correo_electronico **(UQ)**
-   nombre_usuario **(UQ)**
-   telefono **(UQ)**
-   contrasenia
-   is_admin

### productos

-   id **(PK)**
-   nombre
-   stock
-   precio
-   slug
-   categoria_id **(FK)**

### categorias

-   id **(PK)**
-   categoria

### carritos

-   id **(PK)**
-   usuario_id **(FK)**

### carrito_items

-   id **(PK)**
-   carrito_id **(FK)**
-   producto_id **(FK)**
-   cantidad

### ordenes

-   id **(PK)**
-   estado
-   pago_total
-   usuario_id **(FK)**

### ordenes_items

-   id **(PK)**
-   orden_id **(FK)**
-   producto_id **(FK)**
-   cantidad
-   pago_momento

### pagos

-   id **(PK)**
-   orden_id **(FK)**
-   stripe_transaction_id
