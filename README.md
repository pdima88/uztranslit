# uztranslit
Transliteration utils for uzbek language

Requirements: PHP 7.0 or newer

### Usage:

```php
use pdima88\uztranslit\UzCyrToLat;

...

echo UzCyrToLat::translit('Ўзбекистон');

```


```php

use pdima88\uztranslit\UzLatToCyr;

...

echo UzLatToCyr::translit('O‘zbekiston');
echo UzLatToCyr::translitHtml('<p class="text">O‘zbekiston Respublikasi</p>');

```