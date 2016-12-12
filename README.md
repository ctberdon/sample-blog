# Sample Simple Blog

A demo blog for client with markdown text support

## Install

### Requirements

- [PHP][1] version 5.5.x or newer
- [MySQL][2] version 5.0 or newer

### Installing via Composer

- `composer create-project "ctberdon/sample-blog:dev-master" <installation_directory>`

## Configuration

- If you are to install the project locally, it is advisable to run the project under localhost domain name ending with `.com`, `.org` ... e.g. `sample-blog.local.com` or any domain extension as long as Google API redirect validates it.
- Import Database structure from `sample-blog.sql` file.
- Set database connection in `application/config/database.php`.
- Make `application/sessions` directory writable. This is by default where CodeIgniter stores session files. You can modify this setting in `appplication/config/config.php`.
- Obtain a Google API Credentials from Google Developers Console – [https://console.developers.google.com/][3]. If you don't know how to create one, please see this [post][4]. Under `Credentials`, click the Project you have just created and download your API Key file by pressing `Download JSON` button. Place the file in `application/libraries/google-api-php-client/` and named it as `oauth-credentials.json`. Replace the one provided.

## Author

- Carmencito Berdon <me@carmencitoberdon.com>

[1]: http://php.net
[2]: https://www.mysql.com/
[3]: https://console.developers.google.com/
[4]: https://developers.google.com/+/web/samples/php
