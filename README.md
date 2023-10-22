# sasha-bo/shortener

Shortens plain text or html to the $length, preventing braking words and tags.

## Shortening plain text

To cut plain text string to the $length use this static method:

```php
Shortener::shortenText(string $source, int $length = 50, string $add = '...', bool $multiSpace = false): string
```

The shortener will never break a word, excepting the case if the first word is
longer than $length. The shortener uses mb_strlen, not strlen, so it
counts UTF-8 symbols correctly. If $multiSpace is true, the shortener counts
each space (including \n, etc) as one symbol, otherwise a group of spaces
is counted as 1 symbol.

For example, if the source is "Lorem ipsum dolor sit amet", here are lengths
and results:

26: Lorem ipsum dolor sit amet (full string)

25: Lorem ipsum dolor sit...

10: Lorem...

6: Lor...

Those ... are counted too. So, the result string will have the required
length including the length of $add

If you need to shorten one string few times, use this way for better 
performance:

```php
$shortener = new TextShortener('Lorem ipsum dolor');
echo $shortener->shorten(10, '...');
```

So the source string will be parsed only once.

## Shortening HTML

To cut HTML string to the $length use this static method:

```php 
Shortener::shortenHtml(string $source, int $length = 50, string $add = '...'): string
```

or:

```php 
$shortener = new HtmlShortener('<u>Lorem <i>ipsum</i> dolor sit amet</u>');
echo $shortener->shorten(10, '...');
```

Results:

26: &lt;u&gt;Lorem &lt;i&gt;ipsum&lt;/i&gt; dolor sit amet&lt;/u&gt; (full string)

25: &lt;u&gt;Lorem &lt;i&gt;ipsum&lt;/i&gt; dolor sit...&lt;/u&gt;

10: &lt;u&gt;Lorem...&lt;/u&gt;

6: &lt;u&gt;Lor...&lt;/u&gt;

As you see, no closing tags are lost. If your source string is valid HTML,
you will get valid HTML too.

HtmlShortener understands &amp;xxx; symbols and count them as 1.

### Symfony bundle

If you use Symfony 6+, try 
[sashabo/shortener-bundle](https://packagist.org/packages/sashabo/shortener-bundle), 
it provides shorten_text and shorten_html twig filters.