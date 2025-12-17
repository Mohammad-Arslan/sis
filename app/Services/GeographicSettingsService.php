<?php

namespace App\Services;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\Town;
use App\Models\Region;
use App\Models\BuildingType;
use App\Traits\GeneratesActionButtons;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GeographicSettingsService
{
    use GeneratesActionButtons;
    /**
     * Get all countries with caching
     */
    public function getCountries(): Collection
    {
        return Cache::remember('geographic.countries', 3600, fn() => Country::all());
    }

    /**
     * Get all regions with caching
     */
    public function getRegions(): Collection
    {
        return Cache::remember('geographic.regions', 3600, fn() => Region::all());
    }

    /**
     * Get all building types with caching
     */
    public function getBuildingTypes(): Collection
    {
        return Cache::remember('geographic.building-types', 3600, fn() => BuildingType::all());
    }

    /**
     * Create a country
     */
    public function createCountry(array $data): Country
    {
        return DB::transaction(function () use ($data) {
            $country = Country::create($data);
            Cache::forget('geographic.countries');
            return $country;
        });
    }

    /**
     * Update a country
     */
    public function updateCountry(Country $country, array $data): Country
    {
        return DB::transaction(function () use ($country, $data) {
            $country->update($data);
            Cache::forget('geographic.countries');
            return $country->fresh();
        });
    }

    /**
     * Delete a country
     */
    #[\NoDiscard]
    public function deleteCountry(Country $country): bool
    {
        try {
            return DB::transaction(function () use ($country) {
                $deleted = $country->delete();
                Cache::forget('geographic.countries');
                return $deleted;
            });
        } catch (\Exception $e) {
            Log::error('Failed to delete country', [
                'country_id' => $country->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Create a state
     */
    public function createState(array $data): State
    {
        return DB::transaction(function () use ($data) {
            $state = State::create($data);
            Cache::forget('geographic.states');
            return $state->load('countries');
        });
    }

    /**
     * Update a state
     */
    public function updateState(State $state, array $data): State
    {
        return DB::transaction(function () use ($state, $data) {
            $state->update($data);
            Cache::forget('geographic.states');
            return $state->fresh()->load('countries');
        });
    }

    /**
     * Delete a state
     */
    #[\NoDiscard]
    public function deleteState(State $state): bool
    {
        try {
            return DB::transaction(function () use ($state) {
                $deleted = $state->delete();
                Cache::forget('geographic.states');
                return $deleted;
            });
        } catch (\Exception $e) {
            Log::error('Failed to delete state', [
                'state_id' => $state->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Create a city
     */
    public function createCity(array $data): City
    {
        return DB::transaction(function () use ($data) {
            $city = City::create($data);
            Cache::forget('geographic.cities');
            return $city->load('states');
        });
    }

    /**
     * Update a city
     */
    public function updateCity(City $city, array $data): City
    {
        return DB::transaction(function () use ($city, $data) {
            $city->update($data);
            Cache::forget('geographic.cities');
            return $city->fresh()->load('states');
        });
    }

    /**
     * Delete a city
     */
    #[\NoDiscard]
    public function deleteCity(City $city): bool
    {
        try {
            return DB::transaction(function () use ($city) {
                $deleted = $city->delete();
                Cache::forget('geographic.cities');
                return $deleted;
            });
        } catch (\Exception $e) {
            Log::error('Failed to delete city', [
                'city_id' => $city->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Create a town
     */
    public function createTown(array $data): Town
    {
        return DB::transaction(function () use ($data) {
            $town = Town::create($data);
            Cache::forget('geographic.towns');
            return $town->load('cities');
        });
    }

    /**
     * Update a town
     */
    public function updateTown(Town $town, array $data): Town
    {
        return DB::transaction(function () use ($town, $data) {
            $town->update($data);
            Cache::forget('geographic.towns');
            return $town->fresh()->load('cities');
        });
    }

    /**
     * Delete a town
     */
    #[\NoDiscard]
    public function deleteTown(Town $town): bool
    {
        try {
            return DB::transaction(function () use ($town) {
                $deleted = $town->delete();
                Cache::forget('geographic.towns');
                return $deleted;
            });
        } catch (\Exception $e) {
            Log::error('Failed to delete town', [
                'town_id' => $town->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Create a region
     */
    public function createRegion(array $data): Region
    {
        return DB::transaction(function () use ($data) {
            $region = Region::create($data);
            Cache::forget('geographic.regions');
            return $region;
        });
    }

    /**
     * Update a region
     */
    public function updateRegion(Region $region, array $data): Region
    {
        return DB::transaction(function () use ($region, $data) {
            $region->update($data);
            Cache::forget('geographic.regions');
            return $region->fresh();
        });
    }

    /**
     * Delete a region
     */
    #[\NoDiscard]
    public function deleteRegion(Region $region): bool
    {
        try {
            return DB::transaction(function () use ($region) {
                $deleted = $region->delete();
                Cache::forget('geographic.regions');
                return $deleted;
            });
        } catch (\Exception $e) {
            Log::error('Failed to delete region', [
                'region_id' => $region->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Create a building type
     */
    public function createBuildingType(array $data): BuildingType
    {
        return DB::transaction(function () use ($data) {
            $buildingType = BuildingType::create($data);
            Cache::forget('geographic.building-types');
            return $buildingType;
        });
    }

    /**
     * Update a building type
     */
    public function updateBuildingType(BuildingType $buildingType, array $data): BuildingType
    {
        return DB::transaction(function () use ($buildingType, $data) {
            $buildingType->update($data);
            Cache::forget('geographic.building-types');
            return $buildingType->fresh();
        });
    }

    /**
     * Delete a building type
     */
    #[\NoDiscard]
    public function deleteBuildingType(BuildingType $buildingType): bool
    {
        try {
            return DB::transaction(function () use ($buildingType) {
                $deleted = $buildingType->delete();
                Cache::forget('geographic.building-types');
                return $deleted;
            });
        } catch (\Exception $e) {
            Log::error('Failed to delete building type', [
                'building_type_id' => $buildingType->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get states by country
     */
    public function getStatesByCountry(int $countryId): Collection
    {
        return State::where('country_id', $countryId)->get();
    }

    /**
     * Get cities by state
     */
    public function getCitiesByState(int $stateId): Collection
    {
        return City::where('state_id', $stateId)->get();
    }

}

