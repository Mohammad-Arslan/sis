<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<style>
    table{
        font-size: 10px;
    }

    .table td, .table th {
        padding: 0.45rem;
    }
</style>
<body>
    <table class="table table-bordered">
        <tbody>
            <tr class="text-center">
                <th colspan="6"><h6><strong>QA VISIT REPORT</strong></h6></th>
            </tr>
            <tr>
                <td colspan="6"> <strong>Proposed Location Address: </strong> {{$franchise_application_qa->proposed_location}}</td>
            </tr>
            <tr>
                <td colspan="3"> <strong>Sales Representative Name: </strong> {{$franchise_application_qa['sales_rep']['user']['name']}}</td>
                <td colspan="3"> <strong>QA Representative Name: </strong> {{$franchise_application_qa['qa_rep']['user']['name']}}</td>
            </tr>
            <tr>
                <td colspan="3"> <strong>Client Name: </strong> {{$franchise_application_qa->client_name}}</td>
                <td colspan="3"> <strong>Forwarded Date: </strong> {{$franchise_application_qa->bd_forwarded_date($franchise_application_qa->franchise_application_id)}}</td>
            </tr>
            <tr>
                <td colspan="3"> <strong>Purpose of Visit: </strong> {{$franchise_application_qa->visit_purpose}}</td>
                <td colspan="3"> <strong>Proposed School Type: </strong> {{$franchise_application_qa->school_type ? $franchise_application_qa->class_group->name : ''}}</td>
            </tr>
            <tr>
                <td colspan="3"> <strong>Visit Date: </strong> {{\Carbon\Carbon::parse($franchise_application_qa->visit_date)->format('d-m-Y')}}</td>
                @if($franchise_application_qa->qa_status == 'Forwarded')
                    <td colspan="3"> <strong>Status: </strong> {{ 'Forwarded with Observation' }}</td>
                @else
                    <td colspan="3"> <strong>Status: </strong> {{ $franchise_application_qa->qa_status }}</td>
                @endif
            </tr>

            {{-- <tr>
                <td colspan="3"> <strong>Regional Head: </strong> {{$franchise_application_qa['regional_head']['user']['name']}}</td>
                <td colspan="3"> <strong>Date: </strong> {{$franchise_application_qa->regional_head_sec_date}}</td>
            </tr>
            <tr>
                <td colspan="3"> <strong>Regional Head Status: </strong> {{$franchise_application_qa->head_status}}</td>
                <td colspan="3"> <strong>Regional Head Remarks: </strong> {{$franchise_application_qa->regional_head_sec_remarks}}</td>
            </tr> --}}
        </tbody>
    </table>

    <table class="table table-bordered">
        <tr class="text-center">
            <th colspan="5"><h6><strong>REPORT DETAILS</strong></h6></th>
        </tr>
        <thead>
            <th width="10%">Sr #</th>
            <th width="25%">Description</th>
            <th width="25%" colspan="2">Category</th>
            <th width="40%">Remarks</th>
        </thead>
        <tbody>
            <tr>
                <td><b>1</b></td>
                <td colspan="4"><b>Proposed site details:</b></td>
            </tr>
            <tr>
                <td><b>1.1</b></td>
                <td>Total plot size</td>
                <td><b>Required = </b> {{$franchise_application_qa->plot_size_required.' '.ucwords(str_replace('-',' ',$franchise_application_qa->required_uom))}}</td>
                <td><b>Actual = </b> {{$franchise_application_qa->plot_size_actual.' '.ucwords(str_replace('-',' ',$franchise_application_qa->actual_uom))}}</td>
                <td>{{$franchise_application_qa->plot_size_remarks}}</td>
            </tr>
            <tr>
                <td><b>1.2</b></td>
                <td>Site location</td>
                <td colspan="2">{{$franchise_application_qa->site_location ? 'Satisfactory' : 'Unsatisfactory'}}</td>
                <td>{{$franchise_application_qa->site_location_remarks}}</td>
            </tr>
            <tr>
                <td><b>2</b></td>
                <td colspan="4"><b>Building details:</b></td>
            </tr>
            <tr>
                <td><b>2.1</b></td>
                <td>Type of building</td>
                <td colspan="2">{{ucwords(str_replace('_',' ',$franchise_application_qa->type_of_building))}}</td>
                <td>{{$franchise_application_qa->type_of_building_remarks}}</td>
            </tr>
            <tr>
                <td><b>2.2</b></td>
                <td>Type of construction</td>
                <td colspan="2">{{ucwords(str_replace('_',' ',$franchise_application_qa->type_of_construction))}}</td>
                <td>{{$franchise_application_qa->type_of_construction_remarks}}</td>
            </tr>
            <tr>
                <td><b>2.3</b></td>
                <td>Type of covererd area of building</td>
                <td><b>Required = </b> {{$franchise_application_qa->building_covered_area_required}}</td>
                <td><b>Actual = </b> {{$franchise_application_qa->building_covered_area_actual}}</td>
                <td>{{$franchise_application_qa->building_covered_area_remarks}}</td>
            </tr>
            <tr>
                <td><b>2.4</b></td>
                <td>Total open area</td>
                <td>{{$franchise_application_qa->total_open_area_01}}</td>
                <td>{{$franchise_application_qa->total_open_area_02}}</td>
                <td>{{$franchise_application_qa->total_open_area_remarks}}</td>
            </tr>
            <tr>
                <td><b>2.5</b></td>
                <td>Total nos of Classrooms</td>
                <td><b>Required = </b> {{$franchise_application_qa->no_of_classrooms_required}}</td>
                <td><b>Actual = </b> {{$franchise_application_qa->no_of_classrooms_actual}}</td>
                <td>{{$franchise_application_qa->no_of_classrooms_remarks}}</td>
            </tr>
            <tr>
                <td><b>2.6</b></td>
                <td>Total Lab Rooms & Library</td>
                <td><b>Required = </b> {{$franchise_application_qa->total_lab_room_library_required}}</td>
                <td><b>Actual = </b> {{$franchise_application_qa->total_lab_room_library_actual}}</td>
                <td>{{$franchise_application_qa->total_lab_room_library_remarks}}</td>
            </tr>
            <tr>
                <td><b>2.7</b></td>
                <td>Classroom's size</td>
                <td><b>Required = </b> {{$franchise_application_qa->classroom_size_required}}</td>
                <td><b>Actual = </b> {{$franchise_application_qa->classroom_size_actual}}</td>
                <td>{{$franchise_application_qa->classroom_size_remarks}}</td>
            </tr>
            <tr>
                <td><b>2.8</b></td>
                <td>Provision of Extension for further rooms</td>
                <td colspan="2">{{$franchise_application_qa->provision_of_extension ? 'Available' : 'Not Available'}}</td>
                <td>{{$franchise_application_qa->provision_of_extension_remarks}}</td>
            </tr>
            <tr>
                <td><b>2.9</b></td>
                <td>Admin Block</td>
                <td colspan="2">{{$franchise_application_qa->admin_block ? 'Available' : 'Not Available'}}</td>
                <td>{{$franchise_application_qa->admin_block_remarks}}</td>
            </tr>
            {{-- <tr>
                <td><b>3</b></td>
                <td colspan="4"><b>Area Population:</b></td>
            </tr>
            <tr>
                <td><b>3.1</b></td>
                <td>within .5-1-2 km radius</td>
                <td><b>Required = </b> {{$franchise_application_qa->area_population_radius_required}}</td>
                <td><b>Actual = </b> {{$franchise_application_qa->area_population_radius_actual}}</td>
                <td>{{$franchise_application_qa->area_population_radius_remarks}}</td>
            </tr> --}}
            <tr>
                <td><b>3</b></td>
                <td colspan="4"><b>Sorroundings:</b></td>
            </tr>
            <tr>
                <td><b>3.1</b></td>
                <td>School in vicinity</td>
                <td>1. <u>{{$franchise_application_qa->school_in_vicinity_01}}</u></td>
                <td>2. <u>{{$franchise_application_qa->school_in_vicinity_02}}</u></td>
                <td>{{$franchise_application_qa->school_in_vicinity_remarks}}</td>
            </tr>
            <tr>
                <td><b>3.2</b></td>
                <td>Type of locality</td>
                <td colspan="2">{{ucwords(str_replace('_',' ',$franchise_application_qa->type_of_locality))}}</td>
                <td>{{$franchise_application_qa->type_of_locality_remarks}}</td>
            </tr>
            <tr>
                <td><b>3.3</b></td>
                <td>Is the School site centrally located within the locality</td>
                <td colspan="2">{{$franchise_application_qa->is_centrally_located ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->is_centrally_located_remarks}}</td>
            </tr>
            <tr>
                <td><b>3.4</b></td>
                <td>Is a market / book shop near-by</td>
                <td colspan="2">{{$franchise_application_qa->is_market_nearby ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->is_market_nearby_remarks}}</td>
            </tr>
            <tr>
                <td><b>3.5</b></td>
                <td>Road access & condition</td>
                <td colspan="2">{{$franchise_application_qa->road_access ? 'Satisfactory' : 'Unsatisfactory'}}</td>
                <td>{{$franchise_application_qa->road_access_remarks}}</td>
            </tr>
            <tr>
                <td><b>3.6</b></td>
                <td>Road access type</td>
                <td>{{$franchise_application_qa->road_access_type_01}}</u></td>
                <td>{{$franchise_application_qa->road_access_type_02}}</u></td>
                <td>{{$franchise_application_qa->road_access_type_remarks}}</td>
            </tr>
            <tr>
                <td><b>3.7</b></td>
                <td>Facing road width/size</td>
                <td colspan=2><b>Width = </b> {{$franchise_application_qa->facing_road_width}}</u></td>
                {{-- <td><b>Size = </b> {{$franchise_application_qa->facing_road_size}}</u></td> --}}
                <td>{{$franchise_application_qa->facing_road_remarks}}</td>
            </tr>
            <tr>
                <td><b>3.8</b></td>
                <td>Is Public transport facility available near-by</td>
                <td colspan="2">{{$franchise_application_qa->is_public_transport_nearby ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->is_public_transport_nearby_remarks}}</td>
            </tr>
            <tr>
                <td><b>3.9</b></td>
                <td>Noise pollution</td>
                <td colspan="2">{{ $franchise_application_qa->noise_pollution ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->noise_pollution_remarks}}</td>
            </tr>
            <tr>
                <td><b>3.10</b></td>
                <td>Is a Hospital / Clinic available within easy reach</td>
                <td colspan="2">{{$franchise_application_qa->is_hospital_nearby ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->is_hospital_nearby_remarks}}</td>
            </tr>
            <tr>
                <td><b>3.11</b></td>
                <td>Is a fire brigade available near-by</td>
                <td colspan="2">{{$franchise_application_qa->is_rescue_nearby ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->is_rescue_nearby_remarks}}</td>
            </tr>
            <tr>
                <td><b>3.12</b></td>
                <td>Is Police station available near-by</td>
                <td colspan="2">{{$franchise_application_qa->is_police_nearby ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->is_police_nearby_remarks}}</td>
            </tr>
            <tr>
                <td><b>3.13</b></td>
                <td>Is the School site located near a river / canal</td>
                <td colspan="2">{{$franchise_application_qa->is_river_nearby ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->is_river_nearby_remarks}}</td>
            </tr>
            <tr>
                <td><b>3.14</b></td>
                <td>Is the School site protected against flooding</td>
                <td colspan="2">{{$franchise_application_qa->is_flood_protected ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->is_flood_protected_remarks}}</td>
            </tr>
            <tr>
                <td><b>4</b></td>
                <td colspan="4"><b>Traffic Control:</b></td>
            </tr>
            <tr>
                <td><b>4.1</b></td>
                <td>Seperate visitor parking</td>
                <td colspan="2">{{$franchise_application_qa->seperate_visitor_parking ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->seperate_visitor_parking_remarks}}</td>
            </tr>
            <tr>
                <td><b>4.2</b></td>
                <td>Traffic signs</td>
                <td colspan="2">{{$franchise_application_qa->traffic_signs ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->traffic_signs_remarks}}</td>
            </tr>
            <tr>
                <td><b>4.3</b></td>
                <td>Street having dead end</td>
                <td colspan="2">{{$franchise_application_qa->dead_end_street ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->dead_end_street_remarks}}</td>
            </tr>
            <tr>
                <td><b>5</b></td>
                <td colspan="4"><b>Play Ground:</b></td>
            </tr>
            <tr>
                <td><b>5.1</b></td>
                <td>Available</td>
                <td colspan="2">{{$franchise_application_qa->playground_available ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->playground_available_remarks}}</td>
            </tr>
            <tr>
                <td><b>5.2</b></td>
                <td>Available minimun play ground @ 35% of total plot area</td>
                <td colspan="2">{{$franchise_application_qa->minimum_playground_35 ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->minimum_playground_35_remarks}}</td>
            </tr>
            <tr>
                <td><b>6</b></td>
                <td colspan="4"><b>Appearance of building:</b></td>
            </tr>
            <tr>
                <td><b>6.1</b></td>
                <td>Boundary wall plaster</td>
                <td colspan="2">{{$franchise_application_qa->boundary_wall_plaster ? 'Satisfactory' : 'Unsatisfactory'}}</td>
                <td>{{$franchise_application_qa->boundary_wall_plaster_remarks}}</td>
            </tr>
            <tr>
                <td><b>6.2</b></td>
                <td>Over all building plaster</td>
                <td colspan="2">{{$franchise_application_qa->building_plaster ? 'Satisfactory' : 'Unsatisfactory'}}</td>
                <td>{{$franchise_application_qa->building_plaster_remarks}}</td>
            </tr>
            <tr>
                <td><b>6.3</b></td>
                <td>Boundary wall paintwork</td>
                <td colspan="2">{{$franchise_application_qa->boundary_wall_paintwork ? 'Satisfactory' : 'Unsatisfactory'}}</td>
                <td>{{$franchise_application_qa->boundary_wall_paintwork_remarks}}</td>
            </tr>
            <tr>
                <td><b>6.4</b></td>
                <td>Over all building paintwork Seepage</td>
                <td colspan="2">{{$franchise_application_qa->building_paintwork ? 'Satisfactory' : 'Unsatisfactory'}}</td>
                <td>{{$franchise_application_qa->building_paintwork_remarks}}</td>
            </tr>
            <tr>
                <td><b>6.5</b></td>
                <td>Windows & Doors condition</td>
                <td colspan="2">{{$franchise_application_qa->window_door_condition ? 'Satisfactory' : 'Unsatisfactory'}}</td>
                <td>{{$franchise_application_qa->window_door_condition_remarks}}</td>
            </tr>
            <tr>
                <td><b>6.6</b></td>
                <td>Windows & Doors paintwork</td>
                <td colspan="2">{{$franchise_application_qa->window_door_paintwork ? 'Satisfactory' : 'Unsatisfactory'}}</td>
                <td>{{$franchise_application_qa->window_door_paintwork_remarks}}</td>
            </tr>
            <tr>
                <td><b>6.7</b></td>
                <td>Roof condition</td>
                <td colspan="2">{{$franchise_application_qa->roof_condition ? 'Satisfactory' : 'Unsatisfactory'}}</td>
                <td>{{$franchise_application_qa->roof_condition_remarks}}</td>
            </tr>
            <tr>
                <td><b>6.8</b></td>
                <td>Wall condition</td>
                <td colspan="2">{{$franchise_application_qa->wall_condition ? 'Satisfactory' : 'Unsatisfactory'}}</td>
                <td>{{$franchise_application_qa->wall_condition_remarks}}</td>
            </tr>
            <tr>
                <td><b>6.9</b></td>
                <td>Floor condition</td>
                <td colspan="2">{{$franchise_application_qa->floor_condition ? 'Satisfactory' : 'Unsatisfactory'}}</td>
                <td>{{$franchise_application_qa->floor_condition_remarks}}</td>
            </tr>
            <tr>
                <td><b>6.10</b></td>
                <td>Tripping and Shipping Hazards</td>
                <td colspan="2">{{$franchise_application_qa->slipping_hazard ? 'Satisfactory' : 'Unsatisfactory'}}</td>
                <td>{{$franchise_application_qa->slipping_hazard_remarks}}</td>
            </tr>
            <tr>
                <td><b>7</b></td>
                <td colspan="4"><b>Electrical:</b></td>
            </tr>
            <tr>
                <td><b>7.1</b></td>
                <td>Electrical observation</td>
                <td colspan="2">{{$franchise_application_qa->electrical_observation ? 'Satisfactory' : 'Unsatisfactory'}}</td>
                <td>{{$franchise_application_qa->electrical_observation_remarks}}</td>
            </tr>
            <tr>
                <td><b>8</b></td>
                <td colspan="4"><b>Boundary Wall:</b></td>
            </tr>
            <tr>
                <td><b>8.1</b></td>
                <td>Boundary wall should be minimum 8' in height from road level +2' razor wire</td>
                <td colspan="2">{{$franchise_application_qa->boundary_wall_height ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->boundary_wall_height_remarks}}</td>
            </tr>
            <tr>
                <td><b>9</b></td>
                <td colspan="4"><b>Main Gate:</b></td>
            </tr>
            <tr>
                <td><b>9.0</b></td>
                <td>Main gate along with wicked gate</td>
                <td colspan="2">{{$franchise_application_qa->main_along_wicked_gate ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->main_along_wicked_gate_remarks}}</td>
            </tr>
            <tr>
                <td><b>9.1</b></td>
                <td>Guardroom</td>
                <td colspan="2">{{$franchise_application_qa->guardroom ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->guardroom_remarks}}</td>
            </tr>
            <tr>
                <td><b>10</b></td>
                <td colspan="4"><b>Toilet's blocks:</b></td>
            </tr>
            <tr>
                <td><b>10.1</b></td>
                <td>Seperate toilets are provided for staff and visitors</td>
                <td colspan="2">{{$franchise_application_qa->seperate_toilet_visitor ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->seperate_toilet_visitor_remarks}}</td>
            </tr>
            <tr>
                <td><b>10.2</b></td>
                <td>Toilet block a way from class rooms</td>
                <td colspan="2">{{$franchise_application_qa->toilet_away_from_class ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->toilet_away_from_class_remarks}}</td>
            </tr>
            <tr>
                <td><b>10.3</b></td>
                <td>Washroom for Students</td>
                <td colspan="2">{{$franchise_application_qa->student_washroom ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->student_washroom_remarks}}</td>
            </tr>
            <tr>
                <td><b>11</b></td>
                <td colspan="4"><b>Lighting & Ventilation:</b></td>
            </tr>
            <tr>
                <td><b>11.1</b></td>
                <td>Class rooms lighting</td>
                <td colspan="2">{{$franchise_application_qa->classroom_lighting ? 'Satisfactory' : 'Unsatisfactory'}}</td>
                <td>{{$franchise_application_qa->classroom_lighting_remarks}}</td>
            </tr>
            <tr>
                <td><b>11.2</b></td>
                <td>Overall building lighting</td>
                <td colspan="2">{{$franchise_application_qa->overall_lighting ? 'Satisfactory' : 'Unsatisfactory'}}</td>
                <td>{{$franchise_application_qa->overall_lighting_remarks}}</td>
            </tr>
            <tr>
                <td><b>11.3</b></td>
                <td>Class rooms ventilation</td>
                <td colspan="2">{{$franchise_application_qa->classroom_ventilation ? 'Satisfactory' : 'Unsatisfactory'}}</td>
                <td>{{$franchise_application_qa->classroom_ventilation_remarks}}</td>
            </tr>
            <tr>
                <td><b>11.4</b></td>
                <td>Science lab lighting</td>
                <td colspan="2">{{$franchise_application_qa->science_lab_lighting ? 'Satisfactory' : 'Unsatisfactory'}}</td>
                <td>{{$franchise_application_qa->science_lab_lighting_remarks}}</td>
            </tr>
            <tr>
                <td><b>11.5</b></td>
                <td>Science lab ventilation</td>
                <td colspan="2">{{$franchise_application_qa->science_lab_ventilation ? 'Satisfactory' : 'Unsatisfactory'}}</td>
                <td>{{$franchise_application_qa->science_lab_ventilation_remarks}}</td>
            </tr>
            <tr>
                <td><b>11.6</b></td>
                <td>Toilet block ventilation</td>
                <td colspan="2">{{$franchise_application_qa->toilet_ventilation ? 'Satisfactory' : 'Unsatisfactory'}}</td>
                <td>{{$franchise_application_qa->toilet_ventilation_remarks}}</td>
            </tr>
            <tr>
                <td><b>11.7</b></td>
                <td>Overall building ventilation</td>
                <td colspan="2">{{$franchise_application_qa->overall_ventilation ? 'Satisfactory' : 'Unsatisfactory'}}</td>
                <td>{{$franchise_application_qa->overall_ventilation_remarks}}</td>
            </tr>
            <tr>
                <td><b>12</b></td>
                <td colspan="4"><b>Guard Room:</b></td>
            </tr>
            <tr>
                <td><b>12.1</b></td>
                <td>Proper guard room available inside the gate</td>
                <td colspan="2">{{$franchise_application_qa->guardroom_inside_gate ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->guardroom_inside_gate_remarks}}</td>
            </tr>
            <tr>
                <td><b>13</b></td>
                <td colspan="4"><b>Campus to campus distance:</b></td>
            </tr>
            <tr>
                <td><b>13.1</b></td>
                <td>Distance b/w nearest UCS campus</td>
                <td><b>Required = </b> {{$franchise_application_qa->distance_to_ucs_campus_required ? 'Yes' : 'No'}}</td>
                <td><b>Actual = </b> {{$franchise_application_qa->distance_to_ucs_campus_actual ? 'Yes' : 'No'}}</td>
                <td>{{$franchise_application_qa->distance_to_ucs_campus_remarks}}</td>
            </tr>
            <tr>
                <td colspan="5"> <strong>QA Remarks: </strong> {{$franchise_application_qa->qa_remarks}}</td>
            </tr>
            @if(isset($franchise_application_qa->BD_Remarks->observation))
                <tr>
                    <td colspan="5"> <strong>BD Remarks: </strong> {{$franchise_application_qa->BD_Remarks->observation}}</td>
                </tr>
            @endif
            @if(isset($franchise_application_qa->DD_Remarks->observation))
                <tr>
                    <td colspan="5"> <strong>DD Remarks: </strong> {{$franchise_application_qa->DD_Remarks->observation}}</td>
                </tr>
            @endif

        </tbody>
    </table>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>

