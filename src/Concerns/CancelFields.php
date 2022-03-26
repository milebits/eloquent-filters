<?php

namespace Milebits\Eloquent\Filters\Concerns;

use function constVal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Milebits\Eloquent\Filters\UserNullException;
use function now;

/**
 * Trait CancelFields.
 *
 * @mixin Model
 */
trait CancelFields
{
    /**
     * @return void
     */
    public function initializeCancelFields(): void
    {
        $this->mergeFillable([
            $this->getCanceledAtColumn(),
            $this->getCancelReasonColumn(),
            $this->getCancellerMorphColumn().'_id',
            $this->getCancellerMorphColumn().'_type',
        ]);

        $this->mergeCasts([
            $this->getCanceledAtColumn() => 'datetime',
        ]);
    }

    /**
     * @return string
     */
    public function getCanceledAtColumn(): string
    {
        return constVal($this, 'CANCELED_AT_COLUMN', 'canceled_at');
    }

    /**
     * @return string
     */
    public function getCancelReasonColumn(): string
    {
        return constVal($this, 'CANCEL_REASON_COLUMN', 'cancel_reason');
    }

    /**
     * @return string
     */
    public function getCancellerMorphColumn(): string
    {
        return constVal($this, 'CANCELLER_MORPH_COLUMN', 'canceller');
    }

    /**
     * @return string
     */
    public function getQualifiedCanceledAtColumn(): string
    {
        return $this->qualifyColumn($this->getCanceledAtColumn());
    }

    /**
     * Set or get the canceller of this model.
     *
     * @param Model|null  $canceller
     * @param bool        $cancel
     * @param string|null $reason
     *
     * @throws UserNullException
     *
     * @return false|Model|MorphTo
     */
    public function canceller(Model $canceller = null, bool $cancel = false, string $reason = null): Model|MorphTo|bool
    {
        if (is_null($canceller)) {
            return $this->morphTo($this->getCancellerMorphColumn());
        }

        return $this->cancel($reason, $canceller, $cancel) ? $canceller : false;
    }

    /**
     * @throws UserNullException
     */
    public function cancel(string $reason = null, Model $canceller = null, bool $cancel = true): bool
    {
        $canceller = $canceller ?: request()->user();
        if (!$canceller) {
            throw new UserNullException();
        }
        $this->forceFill([
            $this->getCancellerMorphColumn().'_id'   => $canceller->getKey(),
            $this->getCancellerMorphColumn().'_type' => $canceller->getMorphClass(),
        ]);
        if ($cancel) {
            $this->forceFill([
                $this->getCanceledAtColumn()   => now(),
                $this->getCancelReasonColumn() => $reason,
            ]);
        }

        return $this->save();
    }

    /**
     * @return bool
     */
    public function unCancel(): bool
    {
        $this->forceFill([
            $this->getCancellerMorphColumn().'_id'   => null,
            $this->getCancellerMorphColumn().'_type' => null,
            $this->getCanceledAtColumn()             => null,
            $this->getCancelReasonColumn()           => null,
        ]);

        return $this->save();
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function scopeNotCancelled(Builder $builder): Builder
    {
        return $this->scopeCancelled($builder, false);
    }

    /**
     * @param Builder $builder
     * @param bool    $cancelled
     *
     * @return Builder
     */
    public function scopeCancelled(Builder $builder, bool $cancelled = true): Builder
    {
        if ($cancelled) {
            return $builder->whereNotNull($this->getCanceledAtColumn());
        }

        return $builder->whereNull($this->getCanceledAtColumn());
    }

    /**
     * @param Builder $builder
     * @param string  $reason
     *
     * @return Builder
     */
    public function scopeCancelledForSomethingSimilarTo(Builder $builder, string $reason): Builder
    {
        return $this->scopeCancelledFor($builder, "%$reason%", 'like');
    }

    /**
     * @param Builder $builder
     * @param string  $reason
     * @param string  $operator
     *
     * @return Builder
     */
    public function scopeCancelledFor(Builder $builder, string $reason, string $operator = '='): Builder
    {
        return $builder->where($this->getCancelReasonColumn(), $operator, $reason);
    }

    /**
     * @param Builder $builder
     * @param string  $reason
     *
     * @return Builder
     */
    public function scopeCancelledForSomethingNotSimilarTo(Builder $builder, string $reason): Builder
    {
        return $this->scopeCancelledFor($builder, "%$reason", 'notlike');
    }
}
