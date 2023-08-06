# Code Field Changelog

All notable changes to this project will be documented in this file

## 3.0.10 - UNRELEASED
### Changed
* Clean up `IEditorOptionsSchema.json` to handle `<Record>` types and refactor `GoToLocationValues` to `$def`
* Automate release generation via GitHub action

### Fixed
* Handle the case where an existing field is being converted over to a Code Field, and it contains JSON data ([#11](https://github.com/nystudio107/craft-code-field/issues/11))

## 3.0.9 - 2023.04.16
### Changed
* Refactor the `IEditorOptionsSchema.json` to use [`$defs`](https://json-schema.org/understanding-json-schema/structuring.html#defs) to make the JSON schema more structured/reusable

## 3.0.8 - 2023.04.13
### Added
* Allow you to choose `text` or `mediumtext` for the content column storage for Code Editor fields (under Advanced Settings) ([#6](https://github.com/nystudio107/craft-code-field/issues/6))
* Added full autocomplete of the JSON blob of Monaco [EditorOptions](https://microsoft.github.io/monaco-editor/typedoc/interfaces/editor.IEditorOptions.html) in the Code Field's Advanced Settings
* Added a **Default Value** setting for the Code Field ([#7](https://github.com/nystudio107/craft-code-field/issues/7))

## 3.0.7 - 2023.02.15
### Changed
* Refactored the docs buildchain to use a dynamic docker container setup

### Fixed
* Fixed an issue that would cause the editor to display outside of the field's display area ([#4](https://github.com/nystudio107/craft-code-field/issues/4))

## 3.0.6 - 2022.12.13
### Changed
* Added `__toString()` method to the `CodeData` class so that `{{ entry.codeField }}` will work without needing to add `.value` ([#3](https://github.com/nystudio107/craft-code-field/issues/3))

## 3.0.5 - 2022.12.07
### Changed
* Cleaned up the formatting of the Code Field field title

## 3.0.4 - 2022.11.30
### Changed
* Wrap the fields in `<fieldset>` tags if the language selector is visible
* Remove the odd Craft `modifiedAttributes` styling when a field value is changed ([#12403](https://github.com/craftcms/cms/issues/12403))

## 3.0.3 - 2022.11.16
### Added
* Added GraphQL support ([#2](https://github.com/nystudio107/craft-code-field/issues/2))

## 3.0.2 - 2022.11.04
### Added
* Added **Auto** as the default theme setting, which automatically sets the theme to match the browser's dark mode setting

## 3.0.1 - 2022-11-03
### Fixed
* Fixed an issue where changes to the Language selector were not saved with the Code Field

## 3.0.0 - 2022-11-03
### Added
- Initial release
