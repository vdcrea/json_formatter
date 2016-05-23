# Json Formatter

This extension saves input fields and markdown textareas to a Json encoded string (escaping invalid characters: single quote, quotes, backslash, and control characters like `\n` or `\t`), to make easier the output of a Json page.

Three text formatters are available:

- Json String for input fields (string formatter)
- Json Title for input fields (string formatter)
- Json CommonMark for textareas (markup formatter)

**Dependencies** to use all formatters and output a Json page

- [Text Box Field][4] to apply string text formatters to an input field
- [Content Type Mapping][5] to set up a `json` page type with the right header


## Installation & Updating

This extension uses [Composer][3] for autoloading and dependency management.

Make sure to include required dependencies and autoload instructions from its `composer.json` in your project's main `composer.json` and run `composer update` before installing this extension.

Information about [installing and updating extensions](http://getsymphony.com/learn/tasks/view/install-an-extension/) can be found in the Symphony documentation at <http://getsymphony.com/learn/>.


## Output a Json Page from Symphony CMS

Make sure to install the Content Type Mapping extension to create a new page with a `json` type, and set your xsl template as a pure text output:

```
<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output
    method="xml"
    omit-xml-declaration="yes"
    encoding="UTF-8"
    indent="yes" />

<xsl:template match="data">{
    "yourdata" : <xsl:value-of select="xpath/to/your/Json/formatted/field/value"/>
}</xsl:template>
```

[1]: http://commonmark.org/
[2]: http://getsymphony.com/
[3]: http://getcomposer.org/
[4]: https://github.com/symphonists/textboxfield/
[5]: https://github.com/symphonists/content_type_mappings/
