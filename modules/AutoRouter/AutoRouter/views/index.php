<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>Page Title</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
    </head>
    <body>
        <h1> You are viewing <?= Router::current_module() ?>/<?= Router::current_driver() ?> <?= Router::current_payload() ?> </h1>
    </body>
</html>
