# laravel-devoost
Proyecto de Prueba Laravel


## Requisitos

- PHP >= 8.2
- Composer
- Node.js y NPM

---

# Nombre del Proyecto

Este proyecto fue desarrollado con:

- [Laravel 12](https://laravel.com/)
- [Inertia.js](https://inertiajs.com/)
- [Vue 3](https://vuejs.org/)
- [Tailwind CSS](https://tailwindcss.com/)


## Instalación

1. **Clonar el repositorio**

```bash
git clone https://github.com/a1140252x/laravel-devoost.git
cd laravel-devoost
```

2. **Dependencias de PHP**
```bash
composer install
```

3. **Copiar el archivo .env y generar la clave de la app**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Editar configuración del archivo .env (opcional)**
Opcionalmnete se pueden configurar los parametros para cambiar el gestor de base de datos. Originalmente está configurado para sqlite, adocional se provee un servicio de correo electronico para la prueba de reestablecer contraseña
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_database
DB_USERNAME=db_username
DB_PASSWORD=db_password
```

5. **Ejecutar migraciones**
```bash
php artisan migrate
```

6. **Dependencias de npm**
```bash
npm install
npm run build
```

7. **Iniciar servidor local**
```bash
php artisan serve
```

