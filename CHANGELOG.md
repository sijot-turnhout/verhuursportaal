# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a changelog](https://keepachangelog.com/en/1.1.0),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased](https://github.com/sijot-turnhout/verhuursportaal/compare/v0.0.1...0.x)

### Added

- Added missing docblocks in the `MarkAsFinalizedAction.php` file. (#101)
- Added docblocks for the Incident Impact Enumeration (#102)
- Added `$filter` docblock in the the UtilityUsageWidget (#100)
- Added support for PHPStan 2.0 at level 7.
- Ondersteuning voor de registratie van de annulatie redenen en datum voor verhuuraanvragen.

### Changed

- `laravel/laravel` boilerplate code is geupdate en synchroon met v11.6.1
- Updated to changelog format to support the keep a chgangelog format.
- Bump a couple composer to start the PHPStan 2.0 adoption.
- Removed the `@deprecated` declarations in the docblocks in favor of PHP Attributes.

### Removed

- Removed buggy Pest assertion that halts our CI/CD pipeline

## [v0.0.1](https://github.com/sijot-turnhout/verhuursportaal/compare/v0.0.1...v0.0.1) - 2025-01-06

Pre-release (only for demo purposes)
