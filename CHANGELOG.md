# Change Log

All notable changes of GetSimple Legacy CMS will be documented in this file.

## [2025.2.1] - 2025.08.10

### Fixed

- A typo in the *setup.php* file causes an error during installation.

## [2025.2.0] - 2025.08.08

### Added

- Display log file as text on the page `/admin/log.php`. Add GET parameter `view=text` to URL.
- Display content of the PHP errors log file. Use URL `/admin/log.php?log=errorlog.txt&view=text`.
- New *Website Description* field to store the site description on the *Website Settings* page.
- New *Website Featured Image* field to store the URL of the site featured image on the *Website Settings* page.
- New *Website Tagline* field to store the site tagline (or slogan) on the *Website Settings* page.
- New *Website Subtitle* field to store the site subtitle on the *Website Settings* page.
- New *Website Keywords* field to store the site keywords on the *Website Settings* page.
- Fill in the *Website URL* field on the *Website Settings* page with the suggested website URL by clicking on it.
- Control the use of the code editor via *User Settings*. The `GSNOHIGHLIGHT` constant is deprecated.
- New *Page Subtitle* field to store subtitle or long title of the page.
- New theme functions `get_page_subtitle()` and `get_page_clean_subtitle()` to echo or return the page subtitle or the page subtitle stipped of HTML tags.
- New *Page Summary* field to store summary of the page.
- New theme functions `get_page_summary()` and `get_page_clean_summary()` to echo or return the page summary or the page summary stripped of HTML tags.
- New *Page Featured Image* field to store URL of featured image of the page.
- New theme function `get_page_featured_image()` to echo or return the URL of the page featured image.
- New theme function `get_component_title()` to echo or return title of a component.
- New theme function `get_component_description()` to echo or return description of a component.
- New theme function `get_page_parent()` to echo or return the slug of the parent page of a current page. This function replace deprecated theme function `get_parent()`.
- New theme function `get_page_creation_date()` to echo or return page creation date.
- New theme function `get_site_title()` to echo or return the title of the site. It is an alternative to the function `get_site_name()`.
- New theme function `get_site_subtile()` to echo or return the subtitle of the site.
- New theme function `get_site_tagline()` to echo or return the tagline of the site.
- New theme function `get_site_keywords()` to echo or return the keywords of the site as string.
- New theme function `get_site_keywords_array()` to return the keywords of the site as array. There is an option for a unique array.
- New theme function `get_site_description()` to echo or return the description of the site.
- New theme function `get_site_featured_image()` to echo or return the URL of the site featured image.
- Saving of the page creation date. The date is stored in the `creDate` page field.
- New function `isAlpha()` to check if release is an alpha.

### Changed

- The theme function `get_parent()` is deprecated. Use the function `get_page_parent()` instead.
- Function `isBeta()` use new logic to detect if release is a beta. The version naming must follows the [SemVer](https://semver.org/) standard.
- The *Debug Console* displays human-readable information about an objects.

### Fixed

- The output in the *Debug Console* escaped to prevent XSS attacks and others issues.

## [2025.1.1] - 2025.01.28

### Added

- New filter `get_transliteration`. Filter is executed on call of function `getTransliteration()` and allows to change transliteration table array.

### Changed

- Extend default transliteration table.

### Fixed

- Disable Apache option `MultiViews` to prevent index page sub-pages from being replaced by the index page when using fancy URLs.
- Change signature of function `getXML()`. The function takes the second parameter `$cdata` of boolean type. If it is `true` (by default) the function preserve CDATA in the returned `SimpleXMLExtended` object.
- Show confirmation message before clearing failed logins log file.

## [2025.1] - 2025.01.22

### Added

- Use PHP Intl extension to improve transliteration.
- Use new constant `GSTRANSLITERATIONMODE` to set transliteration mode with Intl usage. Define the constant in the gsconfig.php file with value: `1` - transliterate to ASCII with a simple Transliterator rule `:: Any-Latin ; :: Latin-ASCII ;`; `2` - transliterate to ASCII with a custom Transliterator rule started with the rule based on the transliteration table defined in a loaded language file. If the constant is not defined or defined with a different value, the legacy transliteration method is used.

### Changed

- The default transliteration table is case-sensitive and extended with symbols from the Belarusian, Ukrainian and Macedonian alphabets.
- Transliteration table for the Russian language is based on the ГОСТ 7.79—2000 with removed grave accent symbols.

### Fixed

- Change type of the return value of the function `isBeta()` to boolean.

## [2024.3] - 2024.12.21

### Added

- New interface for editing components. Added new data fields for components.
- Component slug can be edited manually.
- Component can have description. For example, description can be used to explain and give help to user to understand component purpose.
- Component slug can be edited manually independently from component title.
- New theme function component_exists() checks if component exists. Function returns boolean true if component exists or false if component does not exist.
- New theme function component_enabled() checks if component is not disabled. Function returns boolean true if component is enabled, false if component is disabled or null if the component is not found.
- Use new load_components() function to load components data from the components.xml file into the $components global variable.
- The breadcrumbs navigation on the Image Control Panel (image.php) page makes easier to navigate through the directories within uploads.
- The "Original image with Caption" option is available from dropdown list on the "Image Control Panel" page. It displays a code snippet for the <figure> HTML element that contains the original image and an empty <figcaption> element.
- Code snippets for images use relative URLs by default.
- The "Image Control Panel" allows the user to reset existed image thumbnail. New thumbnail will be created using default parameters.

### Changed

- Improve creating image thumbnail user interface localization.
- The naming scheme for uploaded copies has changed: add the postfix `-copy` to the name of the first copy, and add a number starting with 1 for the next copies.

### Fixed

- Fixed inability to create thumbnail when cropping region started from left edge of image.
- Item counter shows correct number of items for uploaded files with filtering enabled.
- Fixed handling of error notifications in ajax responses when non-Apache notification is displayed.

## [2024.2.1] - 2024-12-03

### Added

- Each component can be disabled.

### Changed

- Function get_theme_component() accepts second parameter $force to force component eval. Don't normalize the value of the $id parameter.

### Fixed

- Function is_logged_in() always returns a boolean value.

## [2024.2] - 2024-12-01

### Added

- Support WEBP image format. PHP 5.4 or higher required.
- Automatic transliteration of names when uploading files and creating directories.
- Use CodeMirror to highlight and improve components code editing.
- Automatically create missing titles for components.
- Use transliteration to create components slugs. Avoid components with empty slugs.
- Show real name of the user in the welcome message.
- Don't cache the administration panel stylesheet file when Debug Mode is enabled.
- Use constants instead of variables: GSNAME instead of $site_full_name, GSVERSION instead of $site_version_no, GSURL instead of $site_link_back_url. These variables are deprecated.

### Changed

- Support PHP version 8.4.1.
- Minor changes and fixes to graphical user interface elements.
- Don't display the non-Apache web server check error message on the public admin pages.
- Use line wrapping in CodeMirror to improve usability when working with long strings of code.
- The Connect section in the sidebar of the Innovation theme template is only displayed if one or more fields in the Innovation Theme Settings plugin are filled in.
- Show table header on Page Backups if no backups are available.
- Show table header on Plugin Management if no plugins are installed.
- Function get_site_lang() return the ISO language code, not the value of the global variable $LANG.
- Function get_site_credits() will print current GetSimple Legacy CMS if the second parameter is set to true.
- One-time inclusion of the configuration.php file. Don't include it in your plugins files and inside functions.
- Innovation Plugin is not automatically enabled during installation.
- Improve components user interface localization.
- Components slugs may begin and end with any number of underscores. Repeated underscores are not replaced by single underscores.
- Update JQuery to version 1.7.2. This is the last version of JQuery 1.7.
- Function is_frontend() uses constant GSFRONTEND instead of global variable $base. Using the $base variable to determine if it's frontend or backend is deprecated and cannot guarantee a correct result.

### Fixed

- Prevent implicit conversion from float to int while create images thumbnails on PHP 8.1 and later.
- Components are no longer saved automatically when the Enter key is pressed during title entry.

### Removed

- Remove support for the no longer available service addthis.com from the Innovation theme template.
- Remove functions get_api_details(), debug_api_details() and global variable $api_url.
- Variables $name_url_clean and $ver_no_clean are not available.
- Remove live update PHP code snippets for components usage. If component title has been edited, components must be saved to generate slugs and update PHP code snippets.

## [2024.1.1] - 2024-11-23

### Fixed

- Remove call of deprecated function utf8_encode() from the template function get_link_menu_array(). Use normal spaces instead \xA0 to indent menu items.

## [2024.1] - 2024-11-11

### Added

- Initial release

### Changed

- Support PHP version 8.3.13.

### Removed

- Remove calls to the GetSimple CMS API to check the CMS version and plugins updates.
- Remove links to the GetSimple CMS website wiki and help pages.
- Remove plugin anonymous_data.php.
