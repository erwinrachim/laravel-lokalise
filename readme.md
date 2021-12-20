
# laravel-lokalise

[![Latest Stable Version](http://poser.pugx.org/najibismail/laravel-lokalise/v)](https://packagist.org/packages/najibismail/laravel-lokalise)  [![Total Downloads](http://poser.pugx.org/najibismail/laravel-lokalise/downloads)](https://packagist.org/packages/najibismail/laravel-lokalise)  [![Latest Unstable Version](http://poser.pugx.org/najibismail/laravel-lokalise/v/unstable)](https://packagist.org/packages/najibismail/laravel-lokalise)  [![License](http://poser.pugx.org/najibismail/laravel-lokalise/license)](https://packagist.org/packages/najibismail/laravel-lokalise)  [![PHP Version Require](http://poser.pugx.org/najibismail/laravel-lokalise/require/php)](https://packagist.org/packages/najibismail/laravel-lokalise)

  

Upload or Download your laravel language to or from https://lokalise.com automatically by using laravel commands.

  

## Installation

  

To install through composer by using the following command:

```

composer require najibismail/laravel-lokalise

```

  

## Installation

  

#### Publish Config File

```

php artisan lokalise:publish

```

#### .env

```

LOKALISE_API_TOKEN=""

LOKALISE_PROJECT_ID=""

```

  

## Usage

### ***Upload:***

```

php artisan lokalise:upload

```

This will upload your base language file up to lokalise.com replacing what is currently there and adding additional key.

  
  

All the parameters you can refer from: https://app.lokalise.com/api2docs/curl/#transition-upload-a-file-post

  

***`replace_modified`***

- Enable to replace translations in lokalise.com. Default is "false".

  

***`languages`***

Default only english will upload to lokalise. You can add more by add new array follow as format below into the language array.

```

[

'local_iso' => 'en',

'remote_iso' => 'en'

]

```

  

***`local_iso`***

- Refer to your language iso folder in your laravel project

example: resources/lang/en (en is your local_iso)

  

***`remote_iso`***

- Refer to language iso in lokalise.

can refer from: https://docs.lokalise.com/en/articles/1400544-language-settings

<hr>

### ***Download:***

```

php artisan lokalise:download

```

Download language from lokalise.com to your laravel project. All the parameters you can refer from:

https://app.lokalise.com/api2docs/curl/#transition-download-files-post

  

***`skip_en`***

- It's will skip download english language from lokalise.com into your laravel base language. Default is "true".

  

***`format`***

- The language file extension in your laravel project. Default is "php".

  

***`directory_prefix`***

- Directory prefix in the bundle from lokalise.com.

  

***`filter_langs`***

- Default is empty array. All languages will download from lokalise.com. You can set which language you want to download by put the lokalise language iso into this language parameter in array format.

  

***`original_language_iso`***

- refer to lokalise language iso. can refer from: https://docs.lokalise.com/en/articles/1400544-language-settings

  

***`custom_language_iso`***

- refer to your language iso folder in your laravel project. example: resources/lang/en (en is your local_iso).