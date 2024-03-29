# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased](https://github.com/HubSpot/hubspot-php/compare/v4.0.1...HEAD)

## [4.0.1]

### Changed
- Fix composer json

## [4.0.0]

### Changed
- Update Guzzle version (^7.3)
- Update Php version (>=7.3)
- Update Php cs fixer (^3.4)
- Update Phpunit (^9.5)
- Update Phpspec (^7.1)

## [3.2.1]

### Changed
- Update type hinting
- Update Readme 
- Fix Products (rename parameter for createBatch) 

## [3.2.0]

### Changed
- companies->getById($id) => companies->getById($id, array $params = [])
- Remove unneeded defaults for files api

## [3.1.0]

### Added
- contacts->addSecondaryEmail()
- contacts->deleteSecondaryEmail()

## [3.0.1]

### Changed
- Files::upload
- Guzzle version 6 or 7

## [3.0.0]

### Changed
- Comments to BlogComments
- Deal create + update change params 
- BlogPosts::clonePost => BlogPosts::clone
- BlogTopics::create remove name
- contactsProperties getGroups => getAllGroups
- CrmPipelines move object type to __construct
- Up Guzzle version to 7
- Up php version to 7.2

## [2.0.5]

### Changed
- Added ability to remove list contacts by email address
- minor changes

## [2.0.3]

### Added
- Line Items Resource
- Products Resource
- Object Properties Resource

### Changed
- Up php version to 7.0
- Update Ecommerce Bridge to v2
- Update Hub DB to v2
- Update Form(only submit) to v3
- Update Workflows to v3
- Rename Email to EmailSubscriptions
- Update many resources (Method's Visibility, Type Hinting etc)
- Repair majority of tests
- SingleEmail => TransactionEmail

## [1.0.0-rc.1]

### Added
- [CHANGELOG](http://keepachangelog.com/) @ryanwinchester
- Two new Exception classes @ryanwinchester

### Changed
- Namespace renamed from `Fungku\HubSpot` to `SevenShores\Hubspot` @ryanwinchester
- `Api` folder and namespace renamed to `Resources` @ryanwinchester
- The factory `HubSpotService` renamed to `Factory` @ryanwinchester
- The named static constructors in `Factory` were changed to `create()` and `createWithToken()` @ryanwinchester
- The `Factory` constructor was also made public @ryanwinchester
- Moved functionality of `Api` into `Client` and replaced `Api` with `Resource` @ryanwinchester
- `Client` is now constructed with a configuration array @ryanwinchester
- Removed `$base_url` in favour of putting the whole endpoint url in the resource methods. @ryanwinchester
- Optional `HUBSPOT_API_KEY` environment variable changed to `HUBSPOT_SECRET` @ryanwinchester
- Made `$data` property of `Response` public. Because why not? @ryanwinchester

### Fixed
- Trying to return a response with`RequestException` in the `Client`. It now re-throws a new `BadRequest` Exception. [#48](https://github.com/ryanwinchester/hubspot-php/issues/48) @ryanwinchester

[Unreleased]: https://github.com/ryanwinchester/hubspot-php/compare/v1.0.0-rc.1...HEAD
[1.0.0-rc.1]: https://github.com/ryanwinchester/hubspot-php/compare/v0.9.11...v1.0.0-rc.1
