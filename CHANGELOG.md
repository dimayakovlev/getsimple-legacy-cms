# Change Log

All notable changes of GetSimple Legacy CMS will be documented in this file.

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
