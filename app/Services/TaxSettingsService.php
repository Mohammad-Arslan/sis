<?php

namespace App\Services;

use App\Models\Tax;
use App\Models\TaxType;
use App\Models\State;
use App\Traits\GeneratesActionButtons;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TaxSettingsService
{
    use GeneratesActionButtons;

    /**
     * Get all tax types with caching
     */
    public function getTaxTypes(): Collection
    {
        return Cache::remember('tax.tax-types', 3600, fn() => TaxType::all());
    }

    /**
     * Get all states with caching (for tax dropdowns)
     */
    public function getStates(): Collection
    {
        return Cache::remember('tax.states', 3600, fn() => State::all());
    }

    /**
     * Create a tax type
     */
    public function createTaxType(array $data): TaxType
    {
        return DB::transaction(function () use ($data) {
            $taxType = TaxType::create($data);
            Cache::forget('tax.tax-types');
            return $taxType;
        });
    }

    /**
     * Update a tax type
     */
    public function updateTaxType(TaxType $taxType, array $data): TaxType
    {
        return DB::transaction(function () use ($taxType, $data) {
            $taxType->update($data);
            Cache::forget('tax.tax-types');
            return $taxType->fresh();
        });
    }

    /**
     * Delete a tax type
     */
    #[\NoDiscard]
    public function deleteTaxType(TaxType $taxType): bool
    {
        try {
            return DB::transaction(function () use ($taxType) {
                $deleted = $taxType->delete();
                Cache::forget('tax.tax-types');
                return $deleted;
            });
        } catch (\Exception $e) {
            Log::error('Failed to delete tax type', [
                'tax_type_id' => $taxType->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Create a tax
     */
    public function createTax(array $data): Tax
    {
        return DB::transaction(function () use ($data) {
            $tax = Tax::create($data);
            Cache::forget('tax.taxes');
            return $tax->load(['tax_type', 'state']);
        });
    }

    /**
     * Update a tax
     */
    public function updateTax(Tax $tax, array $data): Tax
    {
        return DB::transaction(function () use ($tax, $data) {
            $tax->update($data);
            Cache::forget('tax.taxes');
            return $tax->fresh()->load(['tax_type', 'state']);
        });
    }

    /**
     * Delete a tax
     */
    #[\NoDiscard]
    public function deleteTax(Tax $tax): bool
    {
        try {
            return DB::transaction(function () use ($tax) {
                $deleted = $tax->delete();
                Cache::forget('tax.taxes');
                return $deleted;
            });
        } catch (\Exception $e) {
            Log::error('Failed to delete tax', [
                'tax_id' => $tax->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
