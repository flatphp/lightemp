# Lightemp
Lightemp is a light template engine.

# Usage
```php
$view = new \Lightemp\View('/project/view', array(
    'cache' => true,
    'vars' => array(
        'static' => 'http://static.some.com',
    ),
));

$name = 'Benjemin';
include $view->template('index.php', ['title' => 'Title of the document']);
```

# Sample Template
head.php
```html
<head>
  <meta charset="UTF-8">
  <title>{$title}</title>
  <link href="{$static}/css/common.css" rel="stylesheet">
</head>
```

index.php
```html
<!DOCTYPE html>
<html>
{include test/head.php}
<body>
  hello <?=$name?>
</body>
</html>
```

# Cache
```html
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Title of the document</title>
  <link href="http://static.some.com/css/common.css" rel="stylesheet">
</head>
<body>
  hello <?=$name?>
</body>
</html>
```

# Labels
* {$var}
* {include template}
* {content template}