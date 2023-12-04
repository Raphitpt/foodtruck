<?php


/**
 * Indique si la méthode de travail est GET.
 *
 * @return bool
 */
function is_method_get(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

/**
 * Affiche le head du HTML.
 *
 * @param string $title  le titre de la page.
 * @return void
 */
function head(string $title = ''): string
{
    return  <<<HTML_HEAD
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
    html { font-size: 22px }

    .w-10 {width: 10%!important}
    .w-20 {width: 20%!important}
    .w-30 {width: 30%!important}
    .w-40 {width: 40%!important}
    .w-50 {width: 50%!important}
    .w-60 {width: 60%!important}
    .w-70 {width: 70%!important}
    .w-80 {width: 80%!important}
    .w-90 {width: 90%!important}
    .w-100 {width: 100%!important}
  </style>
  <title>$title</title>
</head>
HTML_HEAD;
}

/**
 * Affiche le footer du HTML.
 *
 * @return void
 */
function footer(): string
{
    return  <<<HTML_FOOTER
<footer></footer>
</body>
</html>
HTML_FOOTER;
}