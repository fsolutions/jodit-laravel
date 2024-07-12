An easy way to use the Jodit editor with your Laravel Api service.

## Installation

The package can be installed via composer:
``` bash
composer require attract/laravel-jodit
```

## Configuration

To configure the package you need to publish settings first:

```
php artisan vendor:publish --provider="Do6po\LaravelJodit\Providers\JoditServiceProvider" --tag=config
```

See comments inside the config:  `config/jodit.php`.

## S3 configuration

* Change S3 url in config/filesystems.php to `env('APP_URL') . '/storage'`,
```php
's3' => [
  'driver'     => 's3',
  'key'        => env('AWS_ACCESS_KEY_ID'),
  'secret'     => env('AWS_SECRET_ACCESS_KEY'),
  'region'     => env('AWS_DEFAULT_REGION'),
  'bucket'     => env('AWS_BUCKET'),
  'url'        => env('APP_URL') . '/storage',
  'endpoint'   => env('AWS_ENDPOINT'),
  'visibility' => 'public',
],
```
* Create a folder named `filebrowser` at the root of the bucket.
* Add permissions to the bucket policy:
```json
{
  "Version": "2012-10-17",
  "Id": "Policy1540386659860",
  "Statement": [
    {
      "Sid": "Stmt1540386655810",
      "Effect": "Allow",
      "Principal": "*",
      "Action": "s3:GetObject",
      "Resource": "arn:aws:s3:::bucket-name/filebrowser/*"
    }
  ]
}
```
* Add permissions to the IAM user policy:
```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Action": "s3:ListBucket",
      "Resource": "arn:aws:s3:::bucket-name",
      "Condition": {
        "StringLike": {
          "s3:prefix": "filebrowser/*"
        }
      }
    },
    {
      "Effect": "Allow",
      "Action": [
        "s3:GetObject",
        "s3:PutObject",
        "s3:DeleteObject",
        "s3:PutObjectAcl"
      ],
      "Resource": "arn:aws:s3:::bucket-name/filebrowser/*"
    }
  ]
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
