<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\Town;
use App\Models\Region;
use App\Models\BuildingType;
use App\Services\GeographicSettingsService;
use App\Http\Requests\GeographicSettings\StoreCountryRequest;
use App\Http\Requests\GeographicSettings\UpdateCountryRequest;
use App\Http\Requests\GeographicSettings\StoreStateRequest;
use App\Http\Requests\GeographicSettings\UpdateStateRequest;
use App\Http\Requests\GeographicSettings\StoreCityRequest;
use App\Http\Requests\GeographicSettings\UpdateCityRequest;
use App\Http\Requests\GeographicSettings\StoreTownRequest;
use App\Http\Requests\GeographicSettings\UpdateTownRequest;
use App\Http\Requests\GeographicSettings\StoreRegionRequest;
use App\Http\Requests\GeographicSettings\UpdateRegionRequest;
use App\Http\Requests\GeographicSettings\StoreBuildingTypeRequest;
use App\Http\Requests\GeographicSettings\UpdateBuildingTypeRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class GeographicSettingsController extends Controller
{
    public function __construct(
        private readonly GeographicSettingsService $service
    ) {
    }

    /**
     * Display the unified geographic settings page
     */
    public function index(): View
    {
        $countries = $this->service->getCountries();
        $regions = $this->service->getRegions();
        $buildingTypes = $this->service->getBuildingTypes();

        return view('settings.geographic.index', compact('countries', 'regions', 'buildingTypes'));
    }

    /**
     * Get countries data for DataTable
     */
    public function getCountries(Request $request): JsonResponse
    {
        if (! $request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $data = Country::query();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', fn($row) => $this->service->generateModalActionButtons($row->id, 'Country'))
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Get states data for DataTable
     */
    public function getStates(Request $request): JsonResponse
    {
        if (! $request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $data = State::with('countries');

        if ($request->country_id && $request->country_id > 0) {
            $data = $data->where('country_id', $request->country_id);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('country_name', fn($row) => $row->countries?->country_name ?? 'N/A')
            ->addColumn('action', fn($row) => $this->service->generateModalActionButtons($row->id, 'State'))
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Get cities data for DataTable
     */
    public function getCities(Request $request): JsonResponse
    {
        if (! $request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $data = City::with('states.countries');

        if ($request->state_id && $request->state_id > 0) {
            $data = $data->where('state_id', $request->state_id);
        }

        if ($request->country_id && $request->country_id > 0) {
            $data = $data->whereHas('states.countries', fn($query) => $query->where('countries.id', $request->country_id));
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('state_name', fn($row) => $row->states?->state_name ?? 'N/A')
            ->addColumn('country_name', fn($row) => $row->states?->countries?->country_name ?? 'N/A')
            ->addColumn('action', fn($row) => $this->service->generateModalActionButtons($row->id, 'City'))
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Get towns data for DataTable
     */
    public function getTowns(Request $request): JsonResponse
    {
        if (! $request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $data = Town::with(['cities.states.countries']);

        if ($request->city_id && $request->city_id > 0) {
            $data = $data->where('city_id', $request->city_id);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('city_name', fn($row) => $row->cities?->city_name ?? 'N/A')
            ->addColumn('state_name', fn($row) => $row->cities?->states?->state_name ?? 'N/A')
            ->addColumn('country_name', fn($row) => $row->cities?->states?->countries?->country_name ?? 'N/A')
            ->addColumn('action', fn($row) => $this->service->generateModalActionButtons($row->id, 'Town'))
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Get regions data for DataTable
     */
    public function getRegions(Request $request): JsonResponse
    {
        if (! $request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $data = Region::query();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', fn($row) => $this->service->generateModalActionButtons($row->id, 'Region'))
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Get building types data for DataTable
     */
    public function getBuildingTypes(Request $request): JsonResponse
    {
        if (! $request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $data = BuildingType::query();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', fn($row) => $this->service->generateModalActionButtons($row->id, 'BuildingType'))
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store a new country
     */
    public function storeCountry(StoreCountryRequest $request): JsonResponse
    {
        try {
            $country = $this->service->createCountry($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Country created successfully.',
                'data' => $country
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create country', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create country. Please try again.'
            ], 500);
        }
    }

    /**
     * Update a country
     */
    public function updateCountry(UpdateCountryRequest $request, Country $country): JsonResponse
    {
        try {
            $country = $this->service->updateCountry($country, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Country updated successfully.',
                'data' => $country
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update country', [
                'country_id' => $country->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update country. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete a country
     */
    public function destroyCountry(Country $country): JsonResponse
    {
        try {
            $deleted = $this->service->deleteCountry($country);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete country.'
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Country deleted successfully.'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete country. It may be in use.'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to delete country', [
                'country_id' => $country->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete country. Please try again.'
            ], 500);
        }
    }

    /**
     * Store a new state
     */
    public function storeState(StoreStateRequest $request): JsonResponse
    {
        try {
            $state = $this->service->createState($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'State created successfully.',
                'data' => $state
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create state', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create state. Please try again.'
            ], 500);
        }
    }

    /**
     * Update a state
     */
    public function updateState(UpdateStateRequest $request, State $state): JsonResponse
    {
        try {
            $state = $this->service->updateState($state, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'State updated successfully.',
                'data' => $state
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update state', [
                'state_id' => $state->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update state. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete a state
     */
    public function destroyState(State $state): JsonResponse
    {
        try {
            $deleted = $this->service->deleteState($state);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete state.'
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'State deleted successfully.'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete state. It may be in use.'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to delete state', [
                'state_id' => $state->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete state. Please try again.'
            ], 500);
        }
    }

    /**
     * Store a new city
     */
    public function storeCity(StoreCityRequest $request): JsonResponse
    {
        try {
            $city = $this->service->createCity($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'City created successfully.',
                'data' => $city
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create city', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create city. Please try again.'
            ], 500);
        }
    }

    /**
     * Update a city
     */
    public function updateCity(UpdateCityRequest $request, City $city): JsonResponse
    {
        try {
            $city = $this->service->updateCity($city, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'City updated successfully.',
                'data' => $city
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update city', [
                'city_id' => $city->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update city. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete a city
     */
    public function destroyCity(City $city): JsonResponse
    {
        try {
            $deleted = $this->service->deleteCity($city);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete city.'
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'City deleted successfully.'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete city. It may be in use.'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to delete city', [
                'city_id' => $city->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete city. Please try again.'
            ], 500);
        }
    }

    /**
     * Store a new town
     */
    public function storeTown(StoreTownRequest $request): JsonResponse
    {
        try {
            $town = $this->service->createTown($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Town created successfully.',
                'data' => $town
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create town', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create town. Please try again.'
            ], 500);
        }
    }

    /**
     * Update a town
     */
    public function updateTown(UpdateTownRequest $request, Town $town): JsonResponse
    {
        try {
            $town = $this->service->updateTown($town, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Town updated successfully.',
                'data' => $town
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update town', [
                'town_id' => $town->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update town. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete a town
     */
    public function destroyTown(Town $town): JsonResponse
    {
        try {
            $deleted = $this->service->deleteTown($town);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete town.'
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Town deleted successfully.'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete town. It may be in use.'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to delete town', [
                'town_id' => $town->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete town. Please try again.'
            ], 500);
        }
    }

    /**
     * Store a new region
     */
    public function storeRegion(StoreRegionRequest $request): JsonResponse
    {
        try {
            $region = $this->service->createRegion($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Region created successfully.',
                'data' => $region
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create region', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create region. Please try again.'
            ], 500);
        }
    }

    /**
     * Update a region
     */
    public function updateRegion(UpdateRegionRequest $request, Region $region): JsonResponse
    {
        try {
            $region = $this->service->updateRegion($region, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Region updated successfully.',
                'data' => $region
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update region', [
                'region_id' => $region->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update region. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete a region
     */
    public function destroyRegion(Region $region): JsonResponse
    {
        try {
            $deleted = $this->service->deleteRegion($region);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete region.'
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Region deleted successfully.'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete region. It may be in use.'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to delete region', [
                'region_id' => $region->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete region. Please try again.'
            ], 500);
        }
    }

    /**
     * Store a new building type
     */
    public function storeBuildingType(StoreBuildingTypeRequest $request): JsonResponse
    {
        try {
            $buildingType = $this->service->createBuildingType($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Building type created successfully.',
                'data' => $buildingType
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create building type', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create building type. Please try again.'
            ], 500);
        }
    }

    /**
     * Update a building type
     */
    public function updateBuildingType(UpdateBuildingTypeRequest $request, BuildingType $buildingType): JsonResponse
    {
        try {
            $buildingType = $this->service->updateBuildingType($buildingType, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Building type updated successfully.',
                'data' => $buildingType
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update building type', [
                'building_type_id' => $buildingType->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update building type. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete a building type
     */
    public function destroyBuildingType(BuildingType $buildingType): JsonResponse
    {
        try {
            $deleted = $this->service->deleteBuildingType($buildingType);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete building type.'
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Building type deleted successfully.'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete building type. It may be in use.'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to delete building type', [
                'building_type_id' => $buildingType->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete building type. Please try again.'
            ], 500);
        }
    }

    /**
     * Get states by country (for dropdowns)
     */
    public function getStatesByCountry(Request $request): JsonResponse
    {
        $request->validate(['country_id' => 'required|exists:countries,id']);

        $states = $this->service->getStatesByCountry($request->country_id);
        return response()->json($states);
    }

    /**
     * Get cities by state (for dropdowns)
     */
    public function getCitiesByState(Request $request): JsonResponse
    {
        $request->validate(['state_id' => 'required|exists:states,id']);

        $cities = $this->service->getCitiesByState($request->state_id);
        return response()->json($cities);
    }

    /**
     * Get single country for editing
     */
    public function getCountry(Country $country): JsonResponse
    {
        return response()->json($country);
    }

    /**
     * Get single state for editing
     */
    public function getState(State $state): JsonResponse
    {
        return response()->json($state->load('countries'));
    }

    /**
     * Get single city for editing
     */
    public function getCity(City $city): JsonResponse
    {
        return response()->json($city->load('states'));
    }

    /**
     * Get single town for editing
     */
    public function getTown(Town $town): JsonResponse
    {
        return response()->json($town->load('cities'));
    }

    /**
     * Get single region for editing
     */
    public function getRegion(Region $region): JsonResponse
    {
        return response()->json($region);
    }

    /**
     * Get single building type for editing
     */
    public function getBuildingType(BuildingType $buildingType): JsonResponse
    {
        return response()->json($buildingType);
    }
}
