<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\Town;
use App\Models\Region;
use App\Models\BuildingType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class GeographicSettingsController extends Controller
{
    /**
     * Display the unified geographic settings page
     */
    public function index(): \Illuminate\View\View
    {
        $countries = Country::all();
        $regions = Region::all();
        $buildingTypes = BuildingType::all();
        
        return view('settings.geographic.index', compact('countries', 'regions', 'buildingTypes'));
    }

    /**
     * Get countries data for DataTable
     */
    public function getCountries(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            $data = Country::query();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="d-flex gap-2">';
                    $html .= '<button onclick="openCountryModal(' . $row->id . ')" class="btn btn-sm btn-primary" title="Edit"><i class="ri-edit-line"></i></button>';
                    $html .= '<button onclick="deleteCountry(' . $row->id . ')" class="btn btn-sm btn-danger" title="Delete"><i class="ri-delete-bin-line"></i></button>';
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return response()->json(['error' => 'Invalid request'], 400);
    }

    /**
     * Get states data for DataTable
     */
    public function getStates(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            $data = State::with('countries');
            
            if ($request->country_id && $request->country_id > 0) {
                $data = $data->where('country_id', $request->country_id);
            }
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('country_name', function ($row) {
                    return $row->countries?->country_name ?? 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="d-flex gap-2">';
                    $html .= '<button onclick="openStateModal(' . $row->id . ')" class="btn btn-sm btn-primary" title="Edit"><i class="ri-edit-line"></i></button>';
                    $html .= '<button onclick="deleteState(' . $row->id . ')" class="btn btn-sm btn-danger" title="Delete"><i class="ri-delete-bin-line"></i></button>';
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return response()->json(['error' => 'Invalid request'], 400);
    }

    /**
     * Get cities data for DataTable
     */
    public function getCities(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            $data = City::with('states.countries');
            
            if ($request->state_id && $request->state_id > 0) {
                $data = $data->where('state_id', $request->state_id);
            }
            
            if ($request->country_id && $request->country_id > 0) {
                $data = $data->whereHas('states.countries', function ($query) use ($request) {
                    $query->where('countries.id', $request->country_id);
                });
            }
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('state_name', function ($row) {
                    return $row->states?->state_name ?? 'N/A';
                })
                ->addColumn('country_name', function ($row) {
                    return $row->states?->countries?->country_name ?? 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="d-flex gap-2">';
                    $html .= '<button onclick="openCityModal(' . $row->id . ')" class="btn btn-sm btn-primary" title="Edit"><i class="ri-edit-line"></i></button>';
                    $html .= '<button onclick="deleteCity(' . $row->id . ')" class="btn btn-sm btn-danger" title="Delete"><i class="ri-delete-bin-line"></i></button>';
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return response()->json(['error' => 'Invalid request'], 400);
    }

    /**
     * Get towns data for DataTable
     */
    public function getTowns(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            $data = Town::with(['cities.states.countries']);
            
            if ($request->city_id && $request->city_id > 0) {
                $data = $data->where('city_id', $request->city_id);
            }
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('city_name', function ($row) {
                    return $row->cities?->city_name ?? 'N/A';
                })
                ->addColumn('state_name', function ($row) {
                    return $row->cities?->states?->state_name ?? 'N/A';
                })
                ->addColumn('country_name', function ($row) {
                    return $row->cities?->states?->countries?->country_name ?? 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="d-flex gap-2">';
                    $html .= '<button onclick="openTownModal(' . $row->id . ')" class="btn btn-sm btn-primary" title="Edit"><i class="ri-edit-line"></i></button>';
                    $html .= '<button onclick="deleteTown(' . $row->id . ')" class="btn btn-sm btn-danger" title="Delete"><i class="ri-delete-bin-line"></i></button>';
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return response()->json(['error' => 'Invalid request'], 400);
    }

    /**
     * Get regions data for DataTable
     */
    public function getRegions(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            $data = Region::query();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="d-flex gap-2">';
                    $html .= '<button onclick="openRegionModal(' . $row->id . ')" class="btn btn-sm btn-primary" title="Edit"><i class="ri-edit-line"></i></button>';
                    $html .= '<button onclick="deleteRegion(' . $row->id . ')" class="btn btn-sm btn-danger" title="Delete"><i class="ri-delete-bin-line"></i></button>';
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return response()->json(['error' => 'Invalid request'], 400);
    }

    /**
     * Get building types data for DataTable
     */
    public function getBuildingTypes(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            $data = BuildingType::query();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="d-flex gap-2">';
                    $html .= '<button onclick="openBuildingTypeModal(' . $row->id . ')" class="btn btn-sm btn-primary" title="Edit"><i class="ri-edit-line"></i></button>';
                    $html .= '<button onclick="deleteBuildingType(' . $row->id . ')" class="btn btn-sm btn-danger" title="Delete"><i class="ri-delete-bin-line"></i></button>';
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return response()->json(['error' => 'Invalid request'], 400);
    }

    /**
     * Store a new country
     */
    public function storeCountry(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'country_name' => 'required|unique:countries,country_name',
            'abbreviation' => 'required',
            'country_code' => 'required',
        ]);

        $country = Country::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Country created successfully.',
            'data' => $country
        ]);
    }

    /**
     * Update a country
     */
    public function updateCountry(Request $request, Country $country): JsonResponse
    {
        $validated = $request->validate([
            'country_name' => 'required|unique:countries,country_name,' . $country->id,
            'abbreviation' => 'required',
            'country_code' => 'required',
        ]);

        $country->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Country updated successfully.',
            'data' => $country->fresh()
        ]);
    }

    /**
     * Delete a country
     */
    public function destroyCountry(Country $country): JsonResponse
    {
        try {
            $country->delete();
            return response()->json([
                'success' => true,
                'message' => 'Country deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete country. It may be in use.'
            ], 422);
        }
    }

    /**
     * Store a new state
     */
    public function storeState(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'state_name' => 'required|unique:states,state_name',
            'country_id' => 'required|exists:countries,id',
        ]);

        $state = State::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'State created successfully.',
            'data' => $state->load('countries')
        ]);
    }

    /**
     * Update a state
     */
    public function updateState(Request $request, State $state): JsonResponse
    {
        $validated = $request->validate([
            'state_name' => 'required|unique:states,state_name,' . $state->id,
            'country_id' => 'required|exists:countries,id',
        ]);

        $state->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'State updated successfully.',
            'data' => $state->fresh()->load('countries')
        ]);
    }

    /**
     * Delete a state
     */
    public function destroyState(State $state): JsonResponse
    {
        try {
            $state->delete();
            return response()->json([
                'success' => true,
                'message' => 'State deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete state. It may be in use.'
            ], 422);
        }
    }

    /**
     * Store a new city
     */
    public function storeCity(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'city_name' => 'required|unique:cities,city_name',
            'abbreviation' => 'required',
            'state_id' => 'required|exists:states,id',
        ]);

        $city = City::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'City created successfully.',
            'data' => $city->load('states')
        ]);
    }

    /**
     * Update a city
     */
    public function updateCity(Request $request, City $city): JsonResponse
    {
        $validated = $request->validate([
            'city_name' => 'required|unique:cities,city_name,' . $city->id,
            'abbreviation' => 'required',
            'state_id' => 'required|exists:states,id',
        ]);

        $city->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'City updated successfully.',
            'data' => $city->fresh()->load('states')
        ]);
    }

    /**
     * Delete a city
     */
    public function destroyCity(City $city): JsonResponse
    {
        try {
            $city->delete();
            return response()->json([
                'success' => true,
                'message' => 'City deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete city. It may be in use.'
            ], 422);
        }
    }

    /**
     * Store a new town
     */
    public function storeTown(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'town_name' => 'required',
            'city_id' => 'required|exists:cities,id',
        ]);

        $town = Town::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Town created successfully.',
            'data' => $town->load('cities')
        ]);
    }

    /**
     * Update a town
     */
    public function updateTown(Request $request, Town $town): JsonResponse
    {
        $validated = $request->validate([
            'town_name' => 'required|unique:towns,town_name,' . $town->id,
            'city_id' => 'required|exists:cities,id',
        ]);

        $town->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Town updated successfully.',
            'data' => $town->fresh()->load('cities')
        ]);
    }

    /**
     * Delete a town
     */
    public function destroyTown(Town $town): JsonResponse
    {
        try {
            $town->delete();
            return response()->json([
                'success' => true,
                'message' => 'Town deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete town. It may be in use.'
            ], 422);
        }
    }

    /**
     * Store a new region
     */
    public function storeRegion(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'region_name' => 'required|unique:regions,region_name',
            'abbreviation' => 'required',
        ]);

        $region = Region::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Region created successfully.',
            'data' => $region
        ]);
    }

    /**
     * Update a region
     */
    public function updateRegion(Request $request, Region $region): JsonResponse
    {
        $validated = $request->validate([
            'region_name' => 'required|unique:regions,region_name,' . $region->id,
            'abbreviation' => 'required',
        ]);

        $region->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Region updated successfully.',
            'data' => $region->fresh()
        ]);
    }

    /**
     * Delete a region
     */
    public function destroyRegion(Region $region): JsonResponse
    {
        try {
            $region->delete();
            return response()->json([
                'success' => true,
                'message' => 'Region deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete region. It may be in use.'
            ], 422);
        }
    }

    /**
     * Store a new building type
     */
    public function storeBuildingType(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type_name' => 'required',
        ]);

        $buildingType = BuildingType::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Building type created successfully.',
            'data' => $buildingType
        ]);
    }

    /**
     * Update a building type
     */
    public function updateBuildingType(Request $request, BuildingType $buildingType): JsonResponse
    {
        $validated = $request->validate([
            'type_name' => 'required',
        ]);

        $buildingType->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Building type updated successfully.',
            'data' => $buildingType->fresh()
        ]);
    }

    /**
     * Delete a building type
     */
    public function destroyBuildingType(BuildingType $buildingType): JsonResponse
    {
        try {
            $buildingType->delete();
            return response()->json([
                'success' => true,
                'message' => 'Building type deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete building type. It may be in use.'
            ], 422);
        }
    }

    /**
     * Get states by country (for dropdowns)
     */
    public function getStatesByCountry(Request $request): JsonResponse
    {
        $states = State::where('country_id', $request->country_id)->get();
        return response()->json($states);
    }

    /**
     * Get cities by state (for dropdowns)
     */
    public function getCitiesByState(Request $request): JsonResponse
    {
        $cities = City::where('state_id', $request->state_id)->get();
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

