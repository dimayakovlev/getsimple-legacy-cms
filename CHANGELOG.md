# Change Log

All notable changes of GetSimple Legacy CMS will be documented in this file.

## [Unreleased]

### Added

- Support WEBP image format. PHP 5.4 or higher required.
- Automatic transliteration of names when uploading files and creating directories.
- Use CodeMirror to highlight and improve components code editing.
- Show real name of the user in the welcome message.
- Don't cache the administration panel stylesheet file when Debug Mode is enabled.
- New constant GSURL to replace the $site_link_back_url global variable.

### Changed

- Don't display the non-Apache web server check error message on the public admin pages.
- Use line wrapping in CodeMirror to improve usability when working with long strings of code.
- The Connect section in the sidebar of the Innovation theme template is only displayed if one or more fields in the Innovation Theme Settings plugin are filled in.
- Show table header on Page Backups if no backups are available.
- Show table header on Plugin Management if no plugins are installed.
- Function get_site_lang() return the ISO language code, not the value of the global variable $LANG.
- Function get_site_credits() will print current GetSimple Legacy CMS if the second parameter is set to true.

### Fixed

- Prevent implicit conversion from float to int while create images thumbnails on PHP 8.1 and later.

### Removed

- Remove support for the no longer available service addthis.com from the Innovation theme template.
- Remove functions get_api_details(), debug_api_details() and global variable $api_url.

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
