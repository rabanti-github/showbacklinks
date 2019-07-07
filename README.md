# ShowBackLinks

---

This is a simple MediaWiki extension to display "What Links Here" (backlinks) in the footer of every page.

The extension was forked from https://github.com/benparsons/showbacklinks and updated to newer MediaWiki versions (1.25+).

## Installation

1. Place all files in the root folder of this project, as well as the "i18n" and "resources" folder and all its content into a folder called "ShowBackLinks" in the "extensions" directory of your MediaWiki installation (README.md and .gitignore are not necessary)
2. Add the following to your LocalSettings.php file:

```php
wfLoadExtension( 'ShowBackLinks' );
```

## Known Issues

- Currently, every page shows its backlinks, also special pages and the home page
- The backlink section uses a non-native collapse function (will be changed to MW-native)
- There are no options at the moment

## Support

Issues can be filed on Github: https://github.com/rabanti-github/showbacklinks/issues
