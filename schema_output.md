# Esquema de la Base de Datos (`ddspweb`)

## Tabla: `cache`

| Columna | Tipo | Nulo | Clave | Por defecto | Extra |
|---|---|---|---|---|---|
| `key` | `varchar(255)` | No | PRI | NULL |  |
| `value` | `mediumtext` | No |  | NULL |  |
| `expiration` | `int(11)` | No | MUL | NULL |  |

## Tabla: `cache_locks`

| Columna | Tipo | Nulo | Clave | Por defecto | Extra |
|---|---|---|---|---|---|
| `key` | `varchar(255)` | No | PRI | NULL |  |
| `owner` | `varchar(255)` | No |  | NULL |  |
| `expiration` | `int(11)` | No | MUL | NULL |  |

## Tabla: `contact_messages`

| Columna | Tipo | Nulo | Clave | Por defecto | Extra |
|---|---|---|---|---|---|
| `id` | `bigint(20) unsigned` | No | PRI | NULL | auto_increment |
| `name` | `varchar(255)` | No |  | NULL |  |
| `email` | `varchar(255)` | No |  | NULL |  |
| `phone` | `varchar(255)` | Sí |  | NULL |  |
| `message` | `text` | No |  | NULL |  |
| `status` | `enum('No Leído','Leído','Contactado')` | No |  | No Leído |  |
| `created_at` | `timestamp` | Sí |  | NULL |  |
| `updated_at` | `timestamp` | Sí |  | NULL |  |

## Tabla: `failed_jobs`

| Columna | Tipo | Nulo | Clave | Por defecto | Extra |
|---|---|---|---|---|---|
| `id` | `bigint(20) unsigned` | No | PRI | NULL | auto_increment |
| `uuid` | `varchar(255)` | No | UNI | NULL |  |
| `connection` | `text` | No |  | NULL |  |
| `queue` | `text` | No |  | NULL |  |
| `payload` | `longtext` | No |  | NULL |  |
| `exception` | `longtext` | No |  | NULL |  |
| `failed_at` | `timestamp` | No |  | current_timestamp() |  |

## Tabla: `job_batches`

| Columna | Tipo | Nulo | Clave | Por defecto | Extra |
|---|---|---|---|---|---|
| `id` | `varchar(255)` | No | PRI | NULL |  |
| `name` | `varchar(255)` | No |  | NULL |  |
| `total_jobs` | `int(11)` | No |  | NULL |  |
| `pending_jobs` | `int(11)` | No |  | NULL |  |
| `failed_jobs` | `int(11)` | No |  | NULL |  |
| `failed_job_ids` | `longtext` | No |  | NULL |  |
| `options` | `mediumtext` | Sí |  | NULL |  |
| `cancelled_at` | `int(11)` | Sí |  | NULL |  |
| `created_at` | `int(11)` | No |  | NULL |  |
| `finished_at` | `int(11)` | Sí |  | NULL |  |

## Tabla: `jobs`

| Columna | Tipo | Nulo | Clave | Por defecto | Extra |
|---|---|---|---|---|---|
| `id` | `bigint(20) unsigned` | No | PRI | NULL | auto_increment |
| `queue` | `varchar(255)` | No | MUL | NULL |  |
| `payload` | `longtext` | No |  | NULL |  |
| `attempts` | `tinyint(3) unsigned` | No |  | NULL |  |
| `reserved_at` | `int(10) unsigned` | Sí |  | NULL |  |
| `available_at` | `int(10) unsigned` | No |  | NULL |  |
| `created_at` | `int(10) unsigned` | No |  | NULL |  |

## Tabla: `laboratories`

| Columna | Tipo | Nulo | Clave | Por defecto | Extra |
|---|---|---|---|---|---|
| `id` | `bigint(20) unsigned` | No | PRI | NULL | auto_increment |
| `codigo` | `varchar(255)` | No | UNI | NULL |  |
| `descripcion` | `varchar(255)` | No |  | NULL |  |
| `is_top` | `tinyint(1)` | No |  | 0 |  |
| `created_at` | `timestamp` | Sí |  | NULL |  |
| `updated_at` | `timestamp` | Sí |  | NULL |  |

## Tabla: `migrations`

| Columna | Tipo | Nulo | Clave | Por defecto | Extra |
|---|---|---|---|---|---|
| `id` | `int(10) unsigned` | No | PRI | NULL | auto_increment |
| `migration` | `varchar(255)` | No |  | NULL |  |
| `batch` | `int(11)` | No |  | NULL |  |

## Tabla: `news`

| Columna | Tipo | Nulo | Clave | Por defecto | Extra |
|---|---|---|---|---|---|
| `id` | `bigint(20) unsigned` | No | PRI | NULL | auto_increment |
| `title` | `varchar(255)` | No |  | NULL |  |
| `slug` | `varchar(255)` | No | UNI | NULL |  |
| `content` | `longtext` | No |  | NULL |  |
| `image` | `varchar(255)` | Sí |  | NULL |  |
| `published_at` | `date` | Sí |  | NULL |  |
| `type` | `varchar(255)` | No |  | Noticia |  |
| `created_at` | `timestamp` | Sí |  | NULL |  |
| `updated_at` | `timestamp` | Sí |  | NULL |  |

## Tabla: `password_reset_tokens`

| Columna | Tipo | Nulo | Clave | Por defecto | Extra |
|---|---|---|---|---|---|
| `email` | `varchar(255)` | No | PRI | NULL |  |
| `token` | `varchar(255)` | No |  | NULL |  |
| `created_at` | `timestamp` | Sí |  | NULL |  |

## Tabla: `pharmacies`

| Columna | Tipo | Nulo | Clave | Por defecto | Extra |
|---|---|---|---|---|---|
| `id` | `bigint(20) unsigned` | No | PRI | NULL | auto_increment |
| `nombre` | `varchar(255)` | No |  | NULL |  |
| `ubicacion` | `varchar(255)` | No |  | NULL |  |
| `latitud` | `decimal(10,8)` | Sí |  | NULL |  |
| `longitud` | `decimal(11,8)` | Sí |  | NULL |  |
| `created_at` | `timestamp` | Sí |  | NULL |  |
| `updated_at` | `timestamp` | Sí |  | NULL |  |

## Tabla: `products`

| Columna | Tipo | Nulo | Clave | Por defecto | Extra |
|---|---|---|---|---|---|
| `id` | `bigint(20) unsigned` | No | PRI | NULL | auto_increment |
| `nombre` | `varchar(255)` | No |  | NULL |  |
| `laboratory_id` | `bigint(20) unsigned` | No | MUL | NULL |  |
| `precio` | `decimal(10,2)` | No |  | NULL |  |
| `um` | `varchar(255)` | No |  | NULL |  |
| `codigo` | `varchar(255)` | No | UNI | NULL |  |
| `estado` | `tinyint(1)` | No |  | 1 |  |
| `imagen` | `varchar(255)` | Sí |  | NULL |  |
| `usuario_origen` | `bigint(20) unsigned` | Sí | MUL | NULL |  |
| `usuario_actualizo` | `bigint(20) unsigned` | Sí | MUL | NULL |  |
| `created_at` | `timestamp` | Sí |  | NULL |  |
| `updated_at` | `timestamp` | Sí |  | NULL |  |

## Tabla: `representatives`

| Columna | Tipo | Nulo | Clave | Por defecto | Extra |
|---|---|---|---|---|---|
| `id` | `bigint(20) unsigned` | No | PRI | NULL | auto_increment |
| `nombre` | `varchar(255)` | No |  | NULL |  |
| `ubicacion` | `varchar(255)` | No |  | NULL |  |
| `latitud` | `decimal(10,8)` | Sí |  | NULL |  |
| `longitud` | `decimal(11,8)` | Sí |  | NULL |  |
| `created_at` | `timestamp` | Sí |  | NULL |  |
| `updated_at` | `timestamp` | Sí |  | NULL |  |

## Tabla: `sessions`

| Columna | Tipo | Nulo | Clave | Por defecto | Extra |
|---|---|---|---|---|---|
| `id` | `varchar(255)` | No | PRI | NULL |  |
| `user_id` | `bigint(20) unsigned` | Sí | MUL | NULL |  |
| `ip_address` | `varchar(45)` | Sí |  | NULL |  |
| `user_agent` | `text` | Sí |  | NULL |  |
| `payload` | `longtext` | No |  | NULL |  |
| `last_activity` | `int(11)` | No | MUL | NULL |  |

## Tabla: `users`

| Columna | Tipo | Nulo | Clave | Por defecto | Extra |
|---|---|---|---|---|---|
| `id` | `bigint(20) unsigned` | No | PRI | NULL | auto_increment |
| `name` | `varchar(255)` | No |  | NULL |  |
| `email` | `varchar(255)` | No | UNI | NULL |  |
| `role` | `varchar(255)` | No |  | supervisor |  |
| `email_verified_at` | `timestamp` | Sí |  | NULL |  |
| `password` | `varchar(255)` | No |  | NULL |  |
| `remember_token` | `varchar(100)` | Sí |  | NULL |  |
| `created_at` | `timestamp` | Sí |  | NULL |  |
| `updated_at` | `timestamp` | Sí |  | NULL |  |

