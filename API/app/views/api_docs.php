
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - EduPlanner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .endpoint { margin-bottom: 2rem; }
        .method { font-weight: bold; padding: 0.2rem 0.5rem; border-radius: 4px; }
        .GET { background-color: #28a74520; color: #28a745; }
        .POST { background-color: #007bff20; color: #007bff; }
        .PUT { background-color: #ffc10720; color: #ffc107; }
        .DELETE { background-color: #dc354520; color: #dc3545; }
        pre { border-radius: 4px; }
        .response-example { margin-top: 1rem; }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1>API Documentation - EduPlanner</h1>
        <p class="lead">API para la gestión de horarios escolares</p>

        <div class="alert alert-info">
            <h4 class="alert-heading">Autenticación Requerida</h4>
            <p>Todas las peticiones a la API requieren una API Key válida en el header <code>X-API-Key</code>.</p>
            <hr>
            <p class="mb-0">API Key de ejemplo: <code>ApiPrueba</code></p>
        </div>

        <h2 class="mt-5">Endpoints Disponibles</h2>

        <!-- GET /horari -->
        <div class="endpoint">
            <h3><span class="method GET">GET</span> /horari</h3>
            <p>Obtiene los horarios del sistema.</p>
            
            <h4>Parámetros de consulta:</h4>
            <ul>
                <li><code>curs</code> (opcional) - Filtrar por curso (ej: "DAW-Primer")</li>
                <li><code>dia</code> (opcional) - Filtrar por día (ej: "Dilluns")</li>
            </ul>

            <div class="response-example">
                <h5>Ejemplo de respuesta:</h5>
                <pre class="bg-light p-3">
{
    "success": true,
    "data": [
        {
            "id": 1,
            "assignatura": "M12 - Projecte",
            "professor": "Joan Pere",
            "aula": "T8",
            "dia": "Dilluns",
            "hora_inici": "15:00",
            "hora_fi": "16:00"
        }
    ]
}</pre>
            </div>
        </div>

        <!-- Ejemplos prácticos -->
        <h2 class="mt-5">Ejemplos de Uso</h2>
        
        <h4>Con cURL:</h4>
        <pre class="bg-light p-3">
curl -H "X-API-Key: ApiPrueba" http://localhost/M12.1/API/public/horari?curs=DAW-Primer</pre>
        
        <h4>Con Postman:</h4>
        <pre class="bg-light p-3">
GET http://localhost/M12.1/API/public/horari?curs=DAW-Primer
Headers:
    X-API-Key: ApiPrueba</pre>

        <h4>Con JavaScript (Fetch):</h4>
        <pre class="bg-light p-3">
fetch('http://localhost/M12.1/API/public/horari?curs=DAW-Primer', {
    headers: {
        'X-API-Key': 'ApiPrueba'
    }
})
.then(response => response.json())
.then(data => console.log(data));</pre>
    </div>
</body>
</html>