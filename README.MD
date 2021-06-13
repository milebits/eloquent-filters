# Milebits Eloquent Filters
A Laravel PHP library that allows the user to filter the result of Eloquent Model query builders.

## How to install
### 1 - Install via composer
```bash
composer require milebits/eloquent-filters
```
### 2 - Add the trait to the filterable models
Inside the model you want to make it filterable you might want to add the `` Milebits\Eloquent\Filters\Concerns\Filterable `` trait to your model so.
Should be looking like this:

```php
use Illuminate\Database\Eloquent\Model;
use Milebits\Eloquent\Filters\Concerns\Filterable;

class Post extends Model
{
    use Filterable;
}

```

## How to use
### Creating new filters
To create new filters you need to use the make from artisan using

``bash
php artisan make:filter MyFiler
``

Which will generate a new filter file inside the `app/Filters` folder, or you can use this which will do the same

``bash
php artisan make:filter My
``

Or if you would like to make this file fresh, you will have to use it like this

```php

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

```php
function index(){
    return App\Models\Post::filtered()->paginate();
}
```

#### Adding filters to the filter
##### By adding them to the function
```php
function index(){
    return App\Models\Post::filtered([NameFilter::class, EnableFilter::class])->paginate();
}
```
##### By using default filter array
Inside your model you can add an array of filters named ``$filters`` and this property must be public.

```php

use Milebits\Eloquent\Filters\Filters\NameFilter;
use Milebits\Eloquent\Filters\Filters\EnableFilter;
use Illuminate\Database\Eloquent\Model;
use Milebits\Eloquent\Filters\Concerns\Filterable;

class Post extends Model{
    use Filterable;

    public static array $filters = [ NameFilter::class, EnableFilter::class, ];
}
```

By doing this you can now use the `filtered` function without any parameters just like before.
```php
function index(){
    return App\Models\Post::filtered()->paginate();
}
```
## The Scopes

This package give you access to a great deal of scopes with useful common fields, in order not to waist time coding these scopes over and over.

- Scope `` Milebits\ELoquent\Filters\ActivationCodeField `` gives you access to: `` activation_code `` column.
- Scope `` Milebits\ELoquent\Filters\ActiveField `` gives you access to: `` active `` column.
- Scope `` Milebits\ELoquent\Filters\AmountField `` gives you access to: `` amount `` column.
- Scope `` Milebits\ELoquent\Filters\ContentField `` gives you access to: `` content `` column.
- Scope `` Milebits\ELoquent\Filters\DescriptionField `` gives you access to: `` description `` column.
- Scope `` Milebits\ELoquent\Filters\EmailField `` gives you access to: `` email `` column.
- Scope `` Milebits\ELoquent\Filters\EnableField `` gives you access to: `` enabled `` column.
- Scope `` Milebits\ELoquent\Filters\NameField `` gives you access to: `` name `` column.
- Scope `` Milebits\ELoquent\Filters\PasswordField `` gives you access to: `` password `` column.
- Scope `` Milebits\ELoquent\Filters\PhoneField `` gives you access to: `` phone `` column and also
  to `` phone_verified_at ``.
- Scope `` Milebits\ELoquent\Filters\PriceField `` gives you access to: `` price `` column.
- Scope `` Milebits\ELoquent\Filters\SlugField `` gives you access to: `` slug `` column.
- Scope `` Milebits\ELoquent\Filters\TitleField `` gives you access to: `` title `` column.
- Scope `` Milebits\ELoquent\Filters\UsernameField `` gives you access to: `` username `` column.

## The Filters

This package gives you access to a great deal of filters which you can use with their respective field scopes.

- Filter `` Milebits\Eloquent\Filters\Filters\ActiveFilter `` gives you access to: `` active `` column filter.
- Filter `` Milebits\Eloquent\Filters\Filters\AmountFilter `` gives you access to: `` amount `` column filter.
- Filter `` Milebits\Eloquent\Filters\Filters\EnableFilter `` gives you access to: `` enable `` column filter.
- Filter `` Milebits\Eloquent\Filters\Filters\NameFilter `` gives you access to: `` name `` column filter.
- Filter `` Milebits\Eloquent\Filters\Filters\PhoneFilter `` gives you access to: `` phone `` column filter.
- Filter `` Milebits\Eloquent\Filters\Filters\PhoneVerifiedFilter `` gives you access to: `` phone_verified_at `` column
  filter.
- Filter `` Milebits\Eloquent\Filters\Filters\PriceFilter `` gives you access to: `` price `` column filter.
- Filter `` Milebits\Eloquent\Filters\Filters\SlugFilter `` gives you access to: `` slug `` column filter.
- Filter `` Milebits\Eloquent\Filters\Filters\SortFilter `` gives you access to: `` sort `` column filter.
- Filter `` Milebits\Eloquent\Filters\Filters\TitleFilter `` gives you access to: `` title `` column filter.

These scopes and filters are rich with eloquent scopes methods, and intelligent methods to use, I cannot possibly
document them all, but you can easily find them in the scopes or filters themselves.

# Contributions

If in any case while using this package, and you which to request a new functionality to it, please contact us at
suggestions@os.milebits.com and mention the package you are willing to contribute, or suggest a new functionality.

# Vulnerabilities

If in any case while using this package, you encounter security issues or security vulnerabilities, please do report
them as soon as possible by issuing an issue here in GitHub or by sending an email to security@os.milebits.com with the
mention **Vulnerability Report milebits/eloquent-filters** as your subject.
