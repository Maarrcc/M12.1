<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentació de l'API - EduPlanner</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto py-6 md:py-10 px-4 sm:px-6 lg:px-8 max-w-7xl">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Documentació de l'API - EduPlanner</h1>
        <p class="text-lg md:text-xl text-gray-600 mb-6 md:mb-8">API per a la gestió d'horaris escolars</p>

        <div class="bg-blue-100 border-l-4 border-blue-500 p-4 sm:p-6 mb-6 md:mb-8 rounded-lg">
            <h4 class="text-base sm:text-lg font-semibold text-blue-800">Autenticació Requerida</h4>
            <p class="text-blue-700 text-sm sm:text-base">Totes les peticions a l'API requereixen una clau API vàlida a
                la capçalera <code class="bg-blue-200 px-1 rounded">X-API-Key</code>.</p>
            <hr class="my-2">
            <p class="text-blue-700 text-sm sm:text-base mb-0">Exemple de clau API: <code
                    class="bg-blue-200 px-1 rounded">ApiProva</code></p>
        </div>

        <h2 class="text-xl md:text-2xl font-bold mt-8 md:mt-10 mb-4">EndPoints Disponibles</h2>

        <!-- GET /horari -->
        <div class="mb-6 md:mb-8">
            <h3 class="text-lg md:text-xl font-semibold flex items-center gap-2">
                <span class="font-bold px-2 py-1 rounded text-green-700 bg-green-200 text-sm sm:text-base">GET</span>
                <span>/horari</span>
            </h3>
            <p class="text-gray-700 text-sm sm:text-base">Obté els horaris del sistema.</p>

            <h4 class="text-base sm:text-lg font-semibold mt-4">Paràmetres de consulta:</h4>
            <ul class="list-disc pl-5 text-gray-700 text-sm sm:text-base">
                <li><code class="bg-gray-200 px-1 rounded">curs</code> (opcional) - Filtrar per curs (ex: "DAW-Primer")
                </li>
                <li><code class="bg-gray-200 px-1 rounded">dia</code> (opcional) - Filtrar per dia (ex: "Dilluns")</li>
            </ul>

            <div class="mt-4">
                <h5 class="text-sm sm:text-md font-semibold">Exemple de resposta:</h5>
                <pre class="bg-gray-50 p-4 rounded-lg text-gray-800 text-xs sm:text-sm overflow-x-auto">
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

        <!-- Exemples pràctics -->
        <h2 class="text-xl md:text-2xl font-bold mt-8 md:mt-10 mb-4">Exemples d'Ús</h2>

        <h4 class="text-base sm:text-lg font-semibold">Amb cURL:</h4>
        <pre class="bg-gray-50 p-4 rounded-lg text-gray-800 text-xs sm:text-sm overflow-x-auto">
curl -H "X-API-Key: ApiProva" http://localhost/M12.1/API/public/horari?curs=DAW-Primer</pre>

        <h4 class="text-base sm:text-lg font-semibold mt-4">Amb Postman:</h4>
        <pre class="bg-gray-50 p-4 rounded-lg text-gray-800 text-xs sm:text-sm overflow-x-auto">
GET http://localhost/M12.1/API/public/horari?curs=DAW-Primer
Headers:
    X-API-Key: ApiProva</pre>

        <h4 class="text-base sm:text-lg font-semibold mt-4">Amb JavaScript (Fetch):</h4>
        <pre class="bg-gray-50 p-4 rounded-lg text-gray-800 text-xs sm:text-sm overflow-x-auto">
fetch('http://localhost/M12.1/API/public/horari?curs=DAW-Primer', {
    headers: {
        'X-API-Key': 'ApiProva'
    }
})
.then(response => response.json())
.then(data => console.log(data));</pre>
    </div>
</body>

</html>