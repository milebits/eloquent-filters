# Milebits Eloquent Filters
A Laravel PHP library that allows the user to filter the result of Eloquent Model query builders.

## How to install
### 1 - Install via composer
```
composer require milebits/eloquent-filters
```
### 2 - Add the trait to the filterable models
Inside the model you want to make it filterable you might want to add the `` Milebits\Eloquent\Filters\Concerns\Filterable `` trait to your model so.
Should be looking like this:

```
use Milebits\Eloquent\Filters\Concerns\Filterable;

class Post extends Model
{
    ...
    use Filterable;
    ...
}

```

## How to use
### Creating new filters
To create new filters you need to use the make from artisan using

``
php artisan make:filter MyFiler
``

Which will generate a new filter file inside the `app/Filters` folder, or you can use this which will do the same

``
php artisan make:filter My
``

Or if you would like to make this file fresh, you will have to use it like this

```

<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Milebits\Eloquent\Filters\Filters\ModelFilter;

/**
 * Class FilterName
 * @package App\Filters
 */
class FilterName extends ModelFilter
{
    /**
     * The filter to apply to the model builder from the pipeline
     *
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        return $builder->where($this->key(), '=', $this->keyValue());
    }
}

```

### How to filter data

#### Use the filter
In order to filter data you can use the `filtered` function that will be inside you model

```
function index(){
    return App\Models\Post::filtered()->paginate();
}
```

#### Adding filters to the filter
##### By adding them to the function
```
function index(){
    return App\Models\Post::filtered([NameFilter::class, EnableFilter::class])->paginate();
}
```
##### By using default filter array
Inside your model you can add an array of filters named ``$filters`` and this property must be public.

```

use App\Filters\NameFilter;
use App\Filters\EnableFilter;

class Post extends Model{
    use Filterable;

    public array $filters = [ NameFilter::class, EnableFilter::class, ];
}
```

And by doing this you can now use the `filtered` function without any parameters just like before.
```
function index(){
    return App\Models\Post::filtered()->paginate();
}
```
## The Scopes

This package give you access to a great deal of scopes with useful common fields, in order not to waist time coding these scopes over and over.

- Scope `` Activatable `` gives you access to: `` activated `` column.
- Scope `` Amountable `` gives you access to: `` amount `` column.
- Scope `` Enableable `` gives you access to: `` enabled `` column.
- Scope `` HasActivationCode `` gives you access to: `` activation_code `` column.
- Scope `` Nameable `` gives you access to: `` name `` column.
- Scope `` Priceable `` gives you access to: `` price `` column.
- Scope `` Sluggable `` gives you access to: `` slug `` column.

These scopes are rich with eloquent scopes methods, I cannot possibly document them all, but you can easily find them in the scopes themselves.