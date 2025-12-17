<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFranchiseApplicationQaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_application_qa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('franchise_application_id')->nullable();
            $table->foreign('franchise_application_id')->references('id')->on('franchise_applications');
            $table->unsignedBigInteger('sales_rep_id')->nullable();
            $table->foreign('sales_rep_id')->references('id')->on('employees');
            $table->unsignedBigInteger('qa_rep_id')->nullable();
            $table->foreign('qa_rep_id')->references('id')->on('employees');
            $table->unsignedBigInteger('regional_head_id')->nullable();
            $table->foreign('regional_head_id')->references('id')->on('employees');
            $table->date('regional_head_sec_date')->nullable();
            $table->string('head_status', 15)->nullable();
            $table->string('regional_head_sec_remarks')->nullable();
            $table->string('proposed_location')->nullable();
            $table->date('visit_date')->nullable();
            $table->string('client_name', 50)->nullable();
            $table->string('client_contact', 15)->nullable();
            $table->string('visit_purpose', 150)->nullable();
            $table->unsignedBigInteger('school_type')->nullable();
            $table->foreign('school_type')->references('id')->on('class_groups');
            $table->string('qa_status', 15)->nullable();
            $table->string('qa_remarks', 150)->nullable();
            $table->string('plot_size_required', 15)->nullable();
            $table->string('plot_size_actual', 15)->nullable();
            $table->string('plot_size_remarks', 150)->nullable();
            $table->boolean('site_location')->nullable();
            $table->string('site_location_remarks', 150)->nullable();
            $table->string('type_of_building', 20)->nullable();
            $table->string('type_of_building_remarks', 150)->nullable();
            $table->string('type_of_construction', 20)->nullable();
            $table->string('type_of_construction_remarks', 150)->nullable();
            $table->string('building_covered_area_required', 15)->nullable();
            $table->string('building_covered_area_actual', 15)->nullable();
            $table->string('building_covered_area_remarks', 150)->nullable();
            $table->string('total_open_area_01', 15)->nullable();
            $table->string('total_open_area_02', 15)->nullable();
            $table->string('total_open_area_remarks', 150)->nullable();
            $table->string('no_of_classrooms_required', 15)->nullable();
            $table->string('no_of_classrooms_actual', 15)->nullable();
            $table->string('no_of_classrooms_remarks', 150)->nullable();
            $table->string('total_lab_room_library_required', 15)->nullable();
            $table->string('total_lab_room_library_actual', 15)->nullable();
            $table->string('total_lab_room_library_remarks', 150)->nullable();
            $table->string('classroom_size_required', 15)->nullable();
            $table->string('classroom_size_actual', 15)->nullable();
            $table->string('classroom_size_remarks', 150)->nullable();
            $table->boolean('provision_of_extension')->nullable();
            $table->string('provision_of_extension_remarks', 150)->nullable();
            $table->boolean('admin_block')->nullable();
            $table->string('admin_block_remarks', 150)->nullable();
            $table->string('area_population_radius_required', 15)->nullable();
            $table->string('area_population_radius_actual', 15)->nullable();
            $table->string('area_population_radius_remarks', 150)->nullable();
            $table->string('school_in_vicinity_01', 15)->nullable();
            $table->string('school_in_vicinity_02', 15)->nullable();
            $table->string('school_in_vicinity_remarks', 150)->nullable();
            $table->string('type_of_locality', 20)->nullable();
            $table->string('type_of_locality_remarks', 150)->nullable();
            $table->boolean('is_centrally_located')->nullable();
            $table->string('is_centrally_located_remarks', 150)->nullable();
            $table->boolean('is_market_nearby')->nullable();
            $table->string('is_market_nearby_remarks', 150)->nullable();
            $table->boolean('road_access')->nullable();
            $table->string('road_access_remarks', 150)->nullable();
            $table->string('road_access_type_01', 15)->nullable();
            $table->string('road_access_type_02', 15)->nullable();
            $table->string('road_access_type_remarks', 150)->nullable();
            $table->string('facing_road_width', 15)->nullable();
            $table->string('facing_road_size', 15)->nullable();
            $table->string('facing_road_remarks', 150)->nullable();
            $table->boolean('is_public_transport_nearby')->nullable();
            $table->string('is_public_transport_nearby_remarks', 150)->nullable();
            $table->boolean('noise_pollution')->nullable();
            $table->string('noise_pollution_remarks', 150)->nullable();
            $table->boolean('is_hospital_nearby')->nullable();
            $table->string('is_hospital_nearby_remarks', 150)->nullable();
            $table->boolean('is_rescue_nearby')->nullable();
            $table->string('is_rescue_nearby_remarks', 150)->nullable();
            $table->boolean('is_police_nearby')->nullable();
            $table->string('is_police_nearby_remarks', 150)->nullable();
            $table->boolean('is_river_nearby')->nullable();
            $table->string('is_river_nearby_remarks', 150)->nullable();
            $table->boolean('is_flood_protected')->nullable();
            $table->string('is_flood_protected_remarks', 150)->nullable();
            $table->boolean('seperate_visitor_parking')->nullable();
            $table->string('seperate_visitor_parking_remarks', 150)->nullable();
            $table->boolean('traffic_signs')->nullable();
            $table->string('traffic_signs_remarks', 150)->nullable();
            $table->boolean('dead_end_street')->nullable();
            $table->string('dead_end_street_remarks', 150)->nullable();
            $table->boolean('playground_available')->nullable();
            $table->string('playground_available_remarks', 150)->nullable();
            $table->boolean('minimum_playground_35')->nullable();
            $table->string('minimum_playground_35_remarks', 150)->nullable();
            $table->boolean('boundary_wall_plaster')->nullable();
            $table->string('boundary_wall_plaster_remarks', 150)->nullable();
            $table->boolean('building_plaster')->nullable();
            $table->string('building_plaster_remarks', 150)->nullable();
            $table->boolean('boundary_wall_paintwork')->nullable();
            $table->string('boundary_wall_paintwork_remarks', 150)->nullable();
            $table->boolean('building_paintwork')->nullable();
            $table->string('building_paintwork_remarks', 150)->nullable();
            $table->boolean('window_door_condition')->nullable();
            $table->string('window_door_condition_remarks', 150)->nullable();
            $table->boolean('window_door_paintwork')->nullable();
            $table->string('window_door_paintwork_remarks', 150)->nullable();
            $table->boolean('roof_condition')->nullable();
            $table->string('roof_condition_remarks', 150)->nullable();
            $table->boolean('wall_condition')->nullable();
            $table->string('wall_condition_remarks', 150)->nullable();
            $table->boolean('floor_condition')->nullable();
            $table->string('floor_condition_remarks', 150)->nullable();
            $table->boolean('slipping_hazard')->nullable();
            $table->string('slipping_hazard_remarks', 150)->nullable();
            $table->boolean('electrical_observation')->nullable();
            $table->string('electrical_observation_remarks', 150)->nullable();
            $table->boolean('boundary_wall_height')->nullable();
            $table->string('boundary_wall_height_remarks', 150)->nullable();
            $table->boolean('main_along_wicked_gate')->nullable();
            $table->string('main_along_wicked_gate_remarks', 150)->nullable();
            $table->boolean('guardroom')->nullable();
            $table->string('guardroom_remarks', 150)->nullable();
            $table->boolean('seperate_toilet_visitor')->nullable();
            $table->string('seperate_toilet_visitor_remarks', 150)->nullable();
            $table->boolean('toilet_away_from_class')->nullable();
            $table->string('toilet_away_from_class_remarks', 150)->nullable();
            $table->boolean('student_washroom')->nullable();
            $table->string('student_washroom_remarks', 150)->nullable();
            $table->boolean('classroom_lighting')->nullable();
            $table->string('classroom_lighting_remarks', 150)->nullable();
            $table->boolean('overall_lighting')->nullable();
            $table->string('overall_lighting_remarks', 150)->nullable();
            $table->boolean('classroom_ventilation')->nullable();
            $table->string('classroom_ventilation_remarks', 150)->nullable();
            $table->boolean('science_lab_lighting')->nullable();
            $table->string('science_lab_lighting_remarks', 150)->nullable();
            $table->boolean('science_lab_ventilation')->nullable();
            $table->string('science_lab_ventilation_remarks', 150)->nullable();
            $table->boolean('toilet_ventilation')->nullable();
            $table->string('toilet_ventilation_remarks', 150)->nullable();
            $table->boolean('overall_ventilation')->nullable();
            $table->string('overall_ventilation_remarks', 150)->nullable();
            $table->boolean('guardroom_inside_gate')->nullable();
            $table->string('guardroom_inside_gate_remarks', 150)->nullable();
            $table->string('distance_to_ucs_campus_required', 15)->nullable();
            $table->string('distance_to_ucs_campus_actual', 15)->nullable();
            $table->string('distance_to_ucs_campus_remarks', 150)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('franchise_application_qa');
    }
}
