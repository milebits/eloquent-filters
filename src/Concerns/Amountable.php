<?php


namespace Milebits\Eloquent\Filters\Concerns;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use function Milebits\Helpers\Helpers\constVal;

/**
 * Trait Amountable
 * @package Milebits\Eloquent\Filters\Concerns
 * @mixin Model
 */
trait Amountable
{
    public function initializeAmountable(): void
    {
        $this->mergeFillable([$this->getAmountColumn()]);
    }

    /**
     * @return string
     */
    public function getAmountColumn(): string
    {
        return constVal($this, "AMOUNT_COLUMN", "amount");
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeWhereAmountEmpty(Builder $builder): Builder
    {
        return $builder->where(function (Builder $builder) {
            return $builder->whereNull($this->decideAmountColumn($builder))->orWhere($this->decideAmountColumn($builder), '=', 0);
        });
    }

    /**
     * @param Builder $builder
     * @return string
     */
    public function decideAmountColumn(Builder $builder): string
    {
        return count((array)(property_exists($builder, 'joins') ? $builder->joins : [])) > 0
            ? $this->getQualifiedAmountColumn()
            : $this->getAmountColumn();
    }

    /**
     * @return string
     */
    public function getQualifiedAmountColumn(): string
    {
        return $this->qualifyColumn($this->getAmountColumn());
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeWhereAmountNotEmpty(Builder $builder): Builder
    {
        return $builder->where(function (Builder $builder) {
            return $builder->whereNotNull($this->decideAmountColumn($builder))->orWhere($this->decideAmountColumn($builder), '!=', 0);
        });
    }

    /**
     * @param Builder $builder
     * @param float $amount
     * @param string $operator
     * @return Builder
     */
    public function scopeWhereAmountIs(Builder $builder, float $amount, string $operator = '='): Builder
    {
        return $builder->where(function (Builder $builder) use ($operator, $amount) {
            return $builder->where($this->decideAmountColumn($builder), $operator, $amount);
        });
    }

    /**
     * @param Builder $builder
     * @param float $amount
     * @param string $operator
     * @return Builder
     */
    public function scopeWhereAmountIsNot(Builder $builder, float $amount, string $operator = '!='): Builder
    {
        return $builder->where(function (Builder $builder) use ($operator, $amount) {
            return $builder->where($this->decideAmountColumn($builder), $operator, $amount);
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
    public function scopeWhereAmountRangeOf(Builder $builder, float $start, float $end, string $boolean = 'and', bool $not = false): Builder
    {
        return $builder->where(function (Builder $builder) use ($not, $boolean, $end, $start) {
            return $builder->whereBetween($this->decideAmountColumn($builder), [$start, $end], $boolean, $not);
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
    public function scopeWhereAmountRangeIsNotOf(Builder $builder, float $start, float $end, string $boolean = 'and', bool $not = true): Builder
    {
        return $builder->where(function (Builder $builder) use ($not, $boolean, $end, $start) {
            return $builder->whereBetween($this->decideAmountColumn($builder), [$start, $end], $boolean, $not);
        });
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeOrWhereAmountEmpty(Builder $builder): Builder
    {
        return $builder->orWhere(function (Builder $builder) {
            return $builder->whereNull($this->decideAmountColumn($builder))->orWhere($this->decideAmountColumn($builder), '=', 0);
        });
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeOrWhereAmountNotEmpty(Builder $builder): Builder
    {
        return $builder->orWhere(function (Builder $builder) {
            return $builder->whereNotNull($this->decideAmountColumn($builder))->orWhere($this->decideAmountColumn($builder), '!=', 0);
        });
    }

    /**
     * @param Builder $builder
     * @param float $amount
     * @param string $operator
     * @return Builder
     */
    public function scopeOrWhereAmountIs(Builder $builder, float $amount, string $operator = '='): Builder
    {
        return $builder->orWhere(function (Builder $builder) use ($operator, $amount) {
            return $builder->where($this->decideAmountColumn($builder), $operator, $amount);
        });
    }

    /**
     * @param Builder $builder
     * @param float $amount
     * @param string $operator
     * @return Builder
     */
    public function scopeOrWhereAmountIsNot(Builder $builder, float $amount, string $operator = '!='): Builder
    {
        return $builder->orWhere(function (Builder $builder) use ($operator, $amount) {
            return $builder->where($this->decideAmountColumn($builder), $operator, $amount);
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
    public function scopeOrWhereAmountRangeOf(Builder $builder, float $start, float $end, string $boolean = 'and', bool $not = false): Builder
    {
        return $builder->orWhere(function (Builder $builder) use ($not, $boolean, $end, $start) {
            return $builder->whereBetween($this->decideAmountColumn($builder), [$start, $end], $boolean, $not);
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
    public function scopeOrWhereAmountRangeIsNotOf(Builder $builder, float $start, float $end, string $boolean = 'and', bool $not = true): Builder
    {
        return $builder->orWhere(function (Builder $builder) use ($not, $boolean, $end, $start) {
            return $builder->whereBetween($this->decideAmountColumn($builder), [$start, $end], $boolean, $not);
        });
    }
}