# sasha-bo/shortener

Shortens plain text or html to the $length, preventing braking words and tags.

## Shortening plain text

To cut plain text string to the $length use this static method:

`Shortener::shortenText(string $source, int $length, string $add = '...')`

The shortener will never break a word, excepting the case if the first word is
longer than $length. The shortener uses mb_strlen, not strlen, so it
counts UTF-8 symbols correctly. It does not count spaces.

For example, if the source is "Lorem ipsum dolor sit amet.", here are lengths
and results:

22: Lorem ipsum dolor sit amet. (full string)

20: Lorem ipsum dolor sit...

17: Lorem ipsum dolor...

5: Lorem...

3: Lor...