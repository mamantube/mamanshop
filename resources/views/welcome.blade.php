<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
           <link rel="icon" type="image/svg+xml" href="../../public/assets/img/mamanshop.png" />

        <title>Laravel</title>
        @vite("resources/js/app.js")
    </head>
    <body class="  font-sans antialiased dark:bg-black dark:text-white/50">
        <div  id="app">
            <Dashboard class="container-fluid" ></Dashboard>
        </div>
    </body>
</html>
