# API de Horarios

Esta API proporciona endpoints para gestionar los horarios escolares.

## Autenticación

Todas las peticiones requieren una API Key que debe ser enviada en el header `X-API-Key`.

## Endpoints

### Obtener Horarios

- **GET** `/horari`
- **GET** `/horari?curs=DAW-Primer`

#### Parámetros
- `curs` (opcional): Filtrar por curso (ejemplo: "DAW-Primer")

#### Respuesta
```json
{
    "success": true,
    "data": [
        {
            "id_horari": 1,
            "hora_inici": "08:00",
            "hora_fi": "09:00",
            "dia": "Lunes",
            "professor": "Nombre Profesor",
            "assignatura": "Nombre Asignatura",
            "aula": "Aula 1"
        }
    ]
}
```

### Crear Horario

- **POST** `/horari`

#### Body
```json
{
    "id_assignatura": 1,
    "id_professor": 1,
    "id_aula": 1,
    "id_curs": 1,
    "dia": "Dilluns",
    "hora_inici": "15:00",
    "hora_fi": "16:00"
}
```

#### Respuesta
```json
{
    "success": true,
    "message": "Horario creado",
    "id": 1
}
```

### Actualizar Horario

- **PUT** `/horari?id=1`

#### Parámetros
- `id`: ID del horario a actualizar

#### Body
```json
{
    "id_assignatura": 1,
    "id_professor": 1,
    "id_aula": 1,
    "id_curs": 1,
    "dia": "Dimarts",
    "hora_inici": "16:00",
    "hora_fi": "17:00"
}
```

#### Respuesta
```json
{
    "success": true,
    "message": "Horario actualizado"
}
```

### Eliminar Horario

- **DELETE** `/horari?id=1`

#### Parámetros
- `id`: ID del horario a eliminar

#### Respuesta
```json
{
    "success": true,
    "message": "Horario eliminado"
}
```

## Códigos de Estado

- 200: Éxito
- 201: Creado
- 400: Solicitud inválida
- 401: No autorizado (API Key inválida)
- 404: No encontrado
- 405: Método no permitido
- 500: Error del servidor

## Ejemplos de Uso con cURL

### Obtener todos los horarios
```bash
curl -H "X-API-Key: tu_api_key" http://localhost/M12.1/API/public/
```

### Obtener horarios de un curso específico
```bash
curl -H "X-API-Key: tu_api_key" http://localhost/M12.1/API/public/?curs=DAW-Primer
```

### Crear un nuevo horario
```bash
curl -X POST \
  -H "X-API-Key: tu_api_key" \
  -H "Content-Type: application/json" \
  -d '{"id_assignatura":1,"id_professor":1,"id_aula":1,"id_curs":1,"dia":"Dilluns","hora_inici":"15:00","hora_fi":"16:00"}' \
  http://localhost/M12.1/API/public/
```

### Actualizar un horario
```bash
curl -X PUT \
  -H "X-API-Key: tu_api_key" \
  -H "Content-Type: application/json" \
  -d '{"id_assignatura":1,"id_professor":1,"id_aula":1,"id_curs":1,"dia":"Dimarts","hora_inici":"16:00","hora_fi":"17:00"}' \
  http://localhost/M12.1/API/public/?id=1
```

### Eliminar un horario
```bash
curl -X DELETE \
  -H "X-API-Key: tu_api_key" \
  http://localhost/M12.1/API/public/?id=1
```