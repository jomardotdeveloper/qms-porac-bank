<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>@yield("title")</title>
        <style>
            .tb { border-collapse: collapse; }
            .tb th, .tb td { padding: 5px; border: solid 1px #777; }
            .tb th { background-color: lightblue; }
            body {
                font-family: Arial, sans-serif;
            }
            table{
                font-family: Arial, sans-serif;
            }
        </style>
        @yield("custom-styles")
    </head>
    <body>
        <h1>
            @yield("title")
        </h1>
        <hr style="margin-top: -1rem;"/>
        @yield("content")
    </body>
</html>