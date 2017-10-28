# laravel-request-relationships

Laravel-request-relationships provides a model trait for laravel that automatically loads model's
relationships when they are defined in the request. The loaded relationships are defined in the URL
in the same format as loading Eloquent relationships, e.g. 
`http://www.example.com/api/article/1?with=author,comments,comments.author`

## Requirements

This package requires PHP >= 7.0 and has been Laravel >= 5.0. It will probably work with any 
Eloquent implementation, but it's not officially supported (at least yet)

## Installation

Laravel-request-relationships is distributed as a composer package. You can install it by adding
it to your `composer.json` file:

```json
"require": {
  "aciddose/laravel-request-relationships": "*"
}
```

If you want to customize the URL query parameter name, add the service provider to the providers
array in your `config/app.php` config file:

```php
Aciddose\RequestRelationships\ServiceProvider::class,
``` 

Them run the following command to create a configuration file:

```
php artisan vendor:publish --provider=Aciddose\\RequestRelationships\\ServiceProvider
```

Then open `config/requestrelationships.php` and change the `default_parameter_name` to whatever you
want.


## Usage

The package provides a trait that you need to add to any model you want the autoloading to work
with.

```php
use \Illuminate\Database\Eloquent\Model;
use \Aciddose\RequestRelationships\Traits\RequestRelationships;

class Article extends Model {
    use RequestRelationships;
    
    /**
     * Parameter name can be defined optionally for each model. If it's not defined, the default
     * parameter name from configuration is used (or `with` if no configuration is defined)
     */
    $requestRelationsParamName = 'with';

    public function author() {
        return $this->belongsTo('App\Users', 'user_id', 'id');
    }
}
```

After defining the trait (and assuming you have a working route), you can just define the 
relationships in your request:
 
`http://www.example.com/api/article/1?with=author,comments,comments.author`
