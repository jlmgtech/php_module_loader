<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link rel="icon" href="../../../../favicon.ico" />
    </head>
    <body>
        <div id="app">
            <h1>This is <?= Router::current_module() ?> -&gt; <?= Router::current_driver() ?></h1>
            <div> <a href="<?= AutoRouter::get("Core", "index") ?>">Back to Core</a> </div>
            <div> <a href="<?= AutoRouter::get("AppMenu", "index") ?>">Back to App Menu</a> </div>
        </div>
    </body>
</html>
