<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>Document</title>
        <link rel='stylesheet' type='text/css' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css' />
        <style><?= file_get_contents(__DIR__ . "/styles.css") ?></style>
    </head>
    <body>
<?php Actions::trigger("menu"); // dispatch registration hook ?>
        <div class='menu'>
            <br />
            <h1>Apps</h1>
            <br />
            <div>
                <ul>
                <?php $i = 0; foreach (AppMenu::$menu as $name => $entry) { ?>
                    <li>
                        <a href="<?= $entry["url"] ?>">
                            <div class="icon"><i class="fa fa-<?= $entry["icon"] ?>"></i></div>
                            <div class="name"><?= $name ?></div>
                        </a>
                    </li>
                <?php $i++; } ?>
                </ul>
            </div>
        </div>
    </body>
</html>
