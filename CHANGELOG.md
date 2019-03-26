# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog][keepachangelog] and this project adheres to [Semantic Versioning][semver].

## v2.0.6

### Fix
- All bool extra-params will convert to int and next to string 

## v2.0.5

### Changed
- Add to FirebaseException Arrayable interface 

## v2.0.4

### Changed
- Add to FirebaseException new features: request (`Psr\Http\Message\RequestInterface`) and response (`Psr\Http\Message\ResponseInterface`) 

## v2.0.3

### Add
- Add key `mutable-content` to Apple FCM Message

## v2.0.2

### Fixed
- Convert extra-params to string-map

## v2.0.0

### Changed

- upgrade to PHP 7.3
- upgrade to Laravel ~5.8
- upgrade to PHPUnit 8

## v1.0.1

### Fixed
- Fixed config structure

## v1.0.0

### Added
- Basic code

[keepachangelog]:https://keepachangelog.com/en/1.0.0/
[semver]:https://semver.org/spec/v2.0.0.html
