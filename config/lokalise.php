<?php
return [

   /*
    |--------------------------------------------------------------------------
    | Lokalise api token
    |--------------------------------------------------------------------------
    | 
    | The api token can be generated under your Personal profile - API Tokens.
    | Here is the url: https://app.lokalise.com/profile#apitokens
    |
    */
   'api_token' => env('LOKALISE_API_TOKEN', ''),

   /*
    |--------------------------------------------------------------------------
    | Lokalise project id
    |--------------------------------------------------------------------------
    |
    | The project id can be get from lokalise project setting
    |
    */
   'project_id' => env('LOKALISE_PROJECT_ID', ''),

   /*
    |--------------------------------------------------------------------------
    | Language folder
    |--------------------------------------------------------------------------
    |
    | The language folder is refer where is your language folder path.
    | Default laravel language folder is resources/lang and will be use when
    | download or upload the language.
    |
    */
   'language_folder' => resource_path('lang'),


   /*
    |--------------------------------------------------------------------------
    | Upload language
    |--------------------------------------------------------------------------
    |
    | Upload language from your laravel project to lokalise.com.
    | All the parameters you can refer from: 
    | https://app.lokalise.com/api2docs/curl/#transition-upload-a-file-post
    |
    | replace_modified
    |    - Enable to replace translations in lokalise.com. Default is "false".
    |
    | languages
    |    - Default only english will upload to lokalise. You can add more by
    |      add new array follow as format below into the langauges array.
    |
    |     [
    |        'local_iso' => 'en',
    |        'remote_iso' => 'en'
    |     ]
    |
    | local_iso
    |    - refer to your language iso folder in your laravel project
    |      example: resources/lang/en (en is your local_iso)
    |
    | remote_iso 
    |    - refer to language iso in lokalise.
    |      can refer from: https://docs.lokalise.com/en/articles/1400544-language-settings
    |
    */

   'upload' => [

      'replace_modified' => false,

      'languages' => [
         [
            'local_iso' => 'en',
            'remote_iso' => 'en'
         ],
      ],
   ],

   /*
    |--------------------------------------------------------------------------
    | Download language
    |--------------------------------------------------------------------------
    |
    | Download language from lokalise.com to your laravel project.
    | All the parameters you can refer from: 
    | https://app.lokalise.com/api2docs/curl/#transition-download-files-post
    |
    | skip_en
    |    - It's will skip download english language from lokalise.com into your
    |      laravel base language. Default is "true".
    |
    | format
    |    - The language file extension in your laravel project. Default is "php".
    |
    | directory_prefix
    |    - Directory prefix in the bundle from lokalise.com.
    |
    | filter_langs
    |    - Default is empty array. All languages will download from lokalise.com. 
    |      You can set which language you want to download by put the lokalise 
    |      language iso into this language parameter in array format.
    |
    | original_language_iso 
    |    - refer to lokalise language iso.
    |      can refer from: https://docs.lokalise.com/en/articles/1400544-language-settings
    |
    | custom_language_iso
    |    - refer to your language iso folder in your laravel project
    |      example: resources/lang/en (en is your local_iso)
    |
    */
   'download' => [

      'skip_en' => env('LOKALISE_DOWNLOAD_SKIP_EN', true),

      'format' => env('LOKALISE_DOWNLOAD_FORMAT', 'php'),

      'original_filenames' => true,

      'directory_prefix' => '/%LANG_ISO%/',

      'bundle_structure' => '%LANG_ISO%.%FORMAT%',

      'filter_langs' => [],

      'langs_mapping' => [
         [
            'original_language_iso' => 'en',
            'custom_language_iso' => 'en'
         ],
      ],
   ],
];
