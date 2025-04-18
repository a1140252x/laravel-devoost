# laravel-devoost
Proyecto de Prueba Laravel


## Requisitos

- PHP >= 8.1
- Composer
- MySQL o PostgreSQL
- Node.js y NPM (opcional, si usas frontend como Vite)

---

## Instalación

1. **Clonar el repositorio**

```bash
git clone https://github.com/a1140252x/laravel-devoost.git
cd laravel-devoost
```


## Instalación

1. **Dependencias de PHP**
```bash
composer install
```

2. **Copiar el archivo .env y generar la clave de la app**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Editar configuración del archivo .env (opcional)**
Opcionalmnete se pueden configurar los parametros para cambiar el gestor de base de datos. Originalmente está configurado para sqlite, adocional se provee un servicio de correo electronico para la prueba de reestablecer contraseña
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_database
DB_USERNAME=db_username
DB_PASSWORD=db_password
```

4. **Ejecutar migraciones**
```bash
php artisan migrate
```

5. **Dependencias de npm**
```bash
npm install
npm run build
```

6. **Iniciar servidor local**
```bash
php artisan serve
```

