<?php

namespace Milebits\Eloquent\Filters\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use function Milebits\Eloquent\Filters\Helpers\constant_value;

/**
 * Trait HasSlug
 * @package App\Concerns
 *
 * @mixin Model
 * @mixin Builder
 *
 * @method static Builder slug(string $slug, string $column = null)
 * @method static Builder notSlug(string $slug, string $column = null)
 * @method static Builder slugLike(string $slug, string $column = null)
 * @method static Builder slugNotLike(string $slug, string $column = null)
 * @method static Builder slugContains(string $slug, string $column = null)
 * @method static Builder slugDoesntContain(string $slug, string $column = null)
 */
trait Sluggable
{
    /**
     * Boots the HasSlug trait.
     *
     * @return void
     */
    public static function bootHasSlug(): void
    {
        self::addGlobalScope(new SlugScope());
        if (constant_value(static::class, 'AUTO_SLUG', false))
            static::creating(function (self $model) {
                $available = false;
                $slug = Str::random(constant_value($model, 'AUTO_SLUG_LENGTH', 16));
                while (!$available) {
                    if (!static::query()->where($model->getSlugColumn(), '=', $slug)->exists())
                        $available = true;
                    $slug = Str::random(constant_value($model, 'AUTO_SLUG_LENGTH', 16));
                }
                $model->setAttribute($model->getSlugColumn(), $slug);
            });
    }

    /**
     * Gets the Slug column slug.
     *
     * @return string
     */
    public function getSlugColumn(): string
    {
        return constant_value($this, 'SLUG_COLUMN', 'slug');
    }

    /**
     * Initializes the HasSlug trait.
     *
     * @return void
     */
    public function initializeHasSlug(): void
    {
        $this->mergeFillable([$this->getSlugColumn()]);
    }

    /**
     * Qualifies the Slug column slug.
     *
     * @return string
     */
    public function getQualifiedSlugColumn(): string
    {
        return $this->qualifyColumn($this->getSlugColumn());
    }
}
