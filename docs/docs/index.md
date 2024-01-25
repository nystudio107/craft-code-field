---
title: Code Field plugin for Craft CMS
sidebar: false

description: Documentation for the Code Field plugin.  The Code Field plugin provides a Code Field that has a full-featured code editor with syntax highlighting & autocomplete
---
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nystudio107/craft-code-field/badges/quality-score.png?b=v4)](https://scrutinizer-ci.com/g/nystudio107/craft-code-field/?branch=v4) [![Code Coverage](https://scrutinizer-ci.com/g/nystudio107/craft-code-field/badges/coverage.png?b=v4)](https://scrutinizer-ci.com/g/nystudio107/craft-code-field/?branch=v4) [![Build Status](https://scrutinizer-ci.com/g/nystudio107/craft-code-field/badges/build.png?b=v4)](https://scrutinizer-ci.com/g/nystudio107/craft-code-field/build-status/v4) [![Code Intelligence Status](https://scrutinizer-ci.com/g/nystudio107/craft-code-field/badges/code-intelligence.svg?b=v4)](https://scrutinizer-ci.com/code-intelligence)

# Code Field plugin for Craft CMS

Provides a Code Field that has a full-featured code editor with syntax highlighting & autocomplete

![Screenshot](./resources/img/plugin-logo.png)

## Requirements

This plugin requires Craft CMS 4.0.0 or later

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require nystudio107/craft-code-field

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Code Field.

## Code Field Overview

Code Field is a Craft CMS field type that uses the [craft-code-editor](https://github.com/nystudio107/craft-code-editor) package to provide a modern code editor for content authors.

Code Field uses the [Monaco editor](https://microsoft.github.io/monaco-editor/) that leverages the same core code that the popular [VS Code](https://code.visualstudio.com/) editor uses.

That means it provides code highlighting, syntax checking, and autocompletion for over 80 languages.

![JavaScript](./resources/screenshots/code-field-javascript-autocomplete.png)

It also has light, dark, and high contrast themes that can be chosen on a per-field basis.

![CSS](./resources/screenshots/code-field-css-picker.png)

It also sports as a variety of editor options such as font size, line numbers, code folding, and more.

![Markdown](./resources/screenshots/code-field-markdown.png)

## Configuring Code Field

Once the Code Editor plugin is installed, it provides a **Code** field type that you can create like any other Craft CMS field.

You can configure each field with its own discrete settings:

![Code Field Settings](./resources/screenshots/code-field-settings.png)

* **Code Field Theme** - The theme to use for the Code Field editor. It can be either Auto, Visual Studio Light, Visual Studio Dark, or High Contrast Dark. **Auto** will set the light/dark theme based on browser light/dark mode. **N.B.:** The Monaco editor [allows only one theme per page](https://github.com/microsoft/monaco-editor/issues/338) so you can’t have different editor instances using different themes.
* **Code Field Default Language** - The language to use for the Code Field editor. This can optionally be changed by content authors while using the field (see below)
* **Font Size** - The font size to use for the Code Field editor
* **Single Line Code Field** - Whether the editor should behave like a Craft field that can be tabbed between, and displays as a single line, with no line breaks.
* **Line Numbers** - Whether line numbers should be displayed in the Code Field editor.
* **Code Folding** - Whether code folding controls should be displayed in the Code Field editor margins.
* **Placeholder Text** - The text that will be shown if the Code Field is empty.
* **Show Language Dropdown with Field** - Whether content authors should have a Language dropdown menu available to them under the Code Field editor when editing an entry.
* **Default Value** - The default value the Code Field will be populated with.

### Available Languages

Clicking on **Available Languages** discloses a list of over 80 languages:

![Markdown](./resources/screenshots/code-field-languages.png)

Check the languages that you want to appear in the **Language** dropdown menu that is optionally displayed under the Code Field editor when editing an entry.

This lets you limit the languages available to content authors to a subset of all of the available languages.

### Advanced

Clicking on **Advanced** discloses a **Monaco Editor Settings Override** field:

![Markdown](./resources/screenshots/code-field-options-override.png)

This allows you to override any of the Monaco [EditorOptions](https://microsoft.github.io/monaco-editor/typedoc/enums/editor.EditorOption.html) settings via a JSON blob configuration.

Using this, you can control every aspect of the Code Field editor.

This field uses a Code Editor field itself, with a JSON schema for the Monaco [`IEditorOptions`](https://microsoft.github.io/monaco-editor/typedoc/interfaces/editor.IEditorOptions.html), so you get autocomplete and hovers here, too!

## Converting Existing Fields

You can convert existing Craft CMS [Plain Text](https://craftcms.com/docs/4.x/plain-text-fields.html) fields, [Code Mirror](https://plugins.craftcms.com/code-mirror) fields, [Simple Text](https://plugins.craftcms.com/simple-text) fields, or other text/code fields to Code Field fields just by changing the field type to **Code**.

All of your existing content will be left intact after converting them over.

## Using Code Field

Use the Code Field as you would any other Craft CMS field.

To output the contents of the field on the frontend, simply do:

```twig
{{ entry.someCode.value | raw }}
```

To know the language or other settings for the Code Field editor, you can get that as well:

```twig
    {# Do whatever you need to do for this language #}
    {% if entry.someCode.language == "javascript" %}
        <p>This is JavaScript!</p>
    {% endif %}
    {# Output the code #}
    <pre><code>{{ entry.someCode.value | raw }}</code></pre>
```

This will not result in any formatting of syntax highlighting of the code on the frontend; that’s up to you to do as you see fit (using [Shiki](https://shiki.matsu.io/), [highlight.js](https://highlightjs.org/), [Prism](https://prismjs.com/), etc.).

### Rendering with Syntax Highlighting

However, you can also use the [craft-code-editor](https://github.com/nystudio107/craft-code-editor#in-frontend-templates) Twig macros directly to display the Code Field editor on the frontend.

The following `monacoOptions` allow you to make the field read-only (though the user can still interact with the code):
```json
{
    "domReadOnly": true,
    "readOnly": true
}
```

So putting it together, if your Code Field field handle is `someCode`, this would allow you to display it on the frontend with full syntax highlighting, etc.:

```twig
    {% import "codeeditor/codeEditor" as codeEditor %}

    <div class="container">
        <textarea id="myCodeEditor">{{ entry.someCode.value }}</textarea>
    </div>
    {{ codeEditor.includeJs("myCodeEditor", "Frontend Editor", {
        language: entry.someCode.language,
        theme: "auto",
        "domReadOnly": true,
        "readOnly": true
    }) }}
```

...and it will look something like this:

![Frontend Read Only](./resources/screenshots/code-field-frontend-read-only.png)

If you want Twig & Craft API autocompletes to work on the frontend, you'll need to enable the [`allowFrontendAccess`](https://github.com/nystudio107/craft-code-editor#in-frontend-templates) setting in `craft-code-editor`, but this isn't necessary if the code is just for display purposes.

### Rendering Twig Code Output

If you are storing Twig code, you can actually render that on the frontend using [renderObjectTemplate](https://craftcms.com/docs/4.x/dev/functions.html#renderobjecttemplate) if you like:

```twig
{{ renderObjectTemplate(entry.someCode.value, entry) }}
```

## Advanced Usage

### GraphQL

Code Field supports GraphQL, here’s an example query:

```GraphQL
{
  entries(section: "blog", limit:1) {
    ...on blog_blog_Entry {
      title,
      someCode {
        language,
        value
      }
    }
  }
}
```

In this example, `someCode` is the Code Field field handle. The schema supports the following parameters:

* **`language`** - The language of the Code Field
* **`value`** - The data entered into the Code Field

Here’s an example of the data returned from the above query:

```json
{
   "data": {
      "entries": [
         {
            "title": "My first blog",
            "someCode": {
               "language": "typescript",
               "value": "function createQuote(quote, callback){ \r\n  var myQuote = \"Like I always say, \" + quote;\r\n  callback(myQuote); // 2\r\n}\r\n\r\nfunction logQuote(quote: string){\r\n  console.log(quote);\r\n}\r\n\r\ncreateQuote(\"eat your vegetables!\", logQuote); // 1"
            }
         }
      ]
   }
}
```

## Code Field Roadmap

Some things to do, and ideas for potential features:

* Add a default value for the field in Field Settings
* Provide support for additional third-party themes
* Add a setting to turn off the display of the language icon

Brought to you by [nystudio107](https://nystudio107.com)
