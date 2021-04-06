<?php


namespace Milebits\Eloquent\Filters\Concerns;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use function Milebits\Helpers\Helpers\constVal;

/**
 * Trait PriceField
 * @package Milebits\Eloquent\Filters\Concerns
 * @mixin Model
 */
trait PriceField
{
    public function initializePriceField(): void
    {
        $this->mergeFillable([$this->getPriceColumn()]);
    }

    /**
     * @return string
     */
    public function getPriceColumn(): string
    {
        return constVal($this, "PRICE_COLUMN", "price");
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeWherePriceEmpty(Builder $builder): Builder
    {
        return $builder->where(function (Builder $builder) {
            return $builder->whereNull($this->decidePriceColumn($builder))->orWhere($this->decidePriceColumn($builder), '=', 0);
        });
    }

    /**
     * @param Builder $builder
     * @return string
     */
    public function decidePriceColumn(Builder $builder): string
    {
        return count((array)(property_exists($builder, 'joins') ? $builder->joins : [])) > 0
            ? $this->getQualifiedPriceColumn()
            : $this->getPriceColumn();
    }

    /**
     * @return string
     */
    public function getQualifiedPriceColumn(): string
    {
        return $this->qualifyColumn($this->getPriceColumn());
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeWherePriceNotEmpty(Builder $builder): Builder
    {
        return $builder->where(function (Builder $builder) {
            return $builder->whereNotNull($this->decidePriceColumn($builder))->orWhere($this->decidePriceColumn($builder), '!=', 0);
        });
    }

    /**
     * @param Builder $builder
     * @param float $price
     * @param string $operator
     * @return Builder
     */
    public function scopeWherePriceIs(Builder $builder, float $price, string $operator = '='): Builder
    {
        return $builder->where(function (Builder $builder) use ($operator, $price) {
            return $builder->where($this->decidePriceColumn($builder), $operator, $price);
        });
    }

    /**
     * @param Builder $builder
     * @param float $price
     * @param string $operator
     * @return Builder
     */
    public function scopeWherePriceIsNot(Builder $builder, float $price, string $operator = '!='): Builder
    {
        return $builder->where(function (Builder $builder) use ($operator, $price) {
            return $builder->where($this->decidePriceColumn($builder), $operator, $price);
        });
    }

    /**
     * @param Builder $builder
     * @param float $start
     * @param float $end
     * @param string $boolean
     * @param bool $not
     * @return Builder
     */
    public function scopeWherePriceRangeOf(Builder $builder, float $start, float $end, string $boolean = 'and', bool $not = false): Builder
    {
        return $builder->where(function (Builder $builder) use ($not, $boolean, $end, $start) {
            return $builder->whereBetween($this->decidePriceColumn($builder), [$start, $end], $boolean, $not);
        });
    }

    /**
     * @param Builder $builder
     * @param float $start
     * @param float $end
     * @param string $boolean
     * @param bool $not
     * @return Builder
     */
    public function scopeWherePriceRangeIsNotOf(Builder $builder, float $start, float $end, string $boolean = 'and', bool $not = true): Builder
    {
        return $builder->where(function (Builder $builder) use ($not, $boolean, $end, $start) {
            return $builder->whereBetween($this->decidePriceColumn($builder), [$start, $end], $boolean, $not);
        });
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeOrWherePriceEmpty(Builder $builder): Builder
    {
        return $builder->orWhere(function (Builder $builder) {
            return $builder->whereNull($this->decidePriceColumn($builder))->orWhere($this->decidePriceColumn($builder), '=', 0);
        });
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeOrWherePriceNotEmpty(Builder $builder): Builder
    {
        return $builder->orWhere(function (Builder $builder) {
            return $builder->whereNotNull($this->decidePriceColumn($builder))->orWhere($this->decidePriceColumn($builder), '!=', 0);
        });
    }

    /**
     * @param Builder $builder
     * @param float $price
     * @param string $operator
     * @return Builder
     */
    public function scopeOrWherePriceIs(Builder $builder, float $price, string $operator = '='): Builder
    {
        return $builder->orWhere(function (Builder $builder) use ($operator, $price) {
            return $builder->where($this->decidePriceColumn($builder), $operator, $price);
        });
    }

    /**
     * @param Builder $builder
     * @param float $price
     * @param string $operator
     * @return Builder
     */
    public function scopeOrWherePriceIsNot(Builder $builder, float $price, string $operator = '!='): Builder
    {
        return $builder->orWhere(function (Builder $builder) use ($operator, $price) {
            return $builder->where($this->decidePriceColumn($builder), $operator, $price);
        });
    }

    /**
     * @param Builder $builder
     * @param float $start
     * @param float $end
     * @param string $boolean
     * @param bool $not
     * @return Builder
     */
    public function scopeOrWherePriceRangeOf(Builder $builder, float $start, float $end, string $boolean = 'and', bool $not = false): Builder
    {
        return $builder->orWhere(function (Builder $builder) use ($not, $boolean, $end, $start) {
            return $builder->whereBetween($this->decidePriceColumn($builder), [$start, $end], $boolean, $not);
        });
    }

    /**
     * @param Builder $builder
     * @param float $start
     * @param float $end
     * @param string $boolean
     * @param bool $not
     * @return Builder
     */
    public function scopeOrWherePriceRangeIsNotOf(Builder $builder, float $start, float $end, string $boolean = 'and', bool $not = true): Builder
    {
        return $builder->orWhere(function (Builder $builder) use ($not, $boolean, $end, $start) {
            return $builder->whereBetween($this->decidePriceColumn($builder), [$start, $end], $boolean, $not);
        });
    }
}