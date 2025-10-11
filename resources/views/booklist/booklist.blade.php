@extends('layouts.master')

<style>
    .text-align-custom tr td {
        vertical-align: baseline
    }

    .table-overflow-cover {
        width: 100%;
        overflow: hidden;
        overflow-x: auto
    }

    .booklist-table {
        border: 1px solid black !important;
        border-collapse: collapse !important;
        vertical-align: middle !important;
        background: #fff !important;
    }

    .booklist-table th {
        border: 1px solid black !important;
        border-collapse: collapse !important;
        vertical-align: middle !important;
        background: #fff !important;
        text-transform: capitalize !important;
        background-color: #98c0e396 !important;
    }

    .booklist-table td {
        border: 1px solid black !important;
        border-collapse: collapse !important;
        vertical-align: middle !important;
        background: #fff !important;
    }
</style>

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1 text-capitalize">
                        define stationary</h4>
                    <div class="flex-shrink-0">
                        <a href="http://127.0.0.1:8000/students/create?tab=personal"
                            class="btn btn-success btn-label btn-sm">
                            <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New
                        </a>
                    </div>
                </div>
                <!-- end card header -->

                <div class="card-body">


                    <div class="live-preview">
                        <form class="row g-3 needs-validation" novalidate>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="text" class="form-control " id="Item" name=""
                                        placeholder="Please enter item" value="" required="">
                                    <label for="Item" class="form-label">Item</label>
                                    <div class="invalid-tooltip">
                                        required!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="text" class="form-control " id="Qty" name=""
                                        placeholder="Please enter item" value="" required="">
                                    <label for="Qty" class="form-label">Qty</label>
                                    <div class="invalid-tooltip">
                                        required!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select " id="" name="" aria-label="select" required="">
                                        <option value=""> Select </option>
                                        <option value="1">
                                            Pakistani</option>
                                        <option value="2">
                                            British</option>
                                        <option value="3">
                                            Chinese</option>
                                    </select>
                                    <label for="" class="form-label">UOM</label>
                                    <div class="invalid-tooltip">
                                        required!
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-label-group in-border">
                                    <textarea class="form-control" name="description" id="classGroupDescription"
                                        placeholder="Enter class description here..."></textarea>
                                    <label for="classGroupDescription" class="form-label">Description</label>
                                </div>
                            </div>
                            <div class="col-12 text-end">
                                <button class="btn btn-primary" type="submit">Save</button>
                                <button type="button"
                                    class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Stationary List</h4>
                    <div class="form-check form-switch form-switch-right form-switch-md">
                        <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                        <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                    </div>
                </div> -->
            </div><!-- end card header -->

            <div class="card-body">

                <table id="example" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>UOM</th>
                            <th>Description</th>
                            <th>Created By</th>
                            <th>Created on</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Tiger Nixon</td>
                            <td>System Architect</td>
                            <td>Edinburgh</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>$320,800</td>
                            <td>
                                <a href="" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
                                    <i class="mdi mdi-lead-pencil"></i>
                                </a>

                                <a href="" data-table="example"
                                    class="btn btn-sm btn-danger btn-icon waves-effect  delete-record">
                                    <i class="ri-delete-bin-5-line"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Garrett Winters</td>
                            <td>Accountant</td>
                            <td>Tokyo</td>
                            <td>63</td>
                            <td>2011/07/25</td>
                            <td>$170,750</td>
                            <td>
                                <a href="" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
                                    <i class="mdi mdi-lead-pencil"></i>
                                </a>

                                <a href="" data-table="example"
                                    class="btn btn-sm btn-danger btn-icon waves-effect  delete-record">
                                    <i class="ri-delete-bin-5-line"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Ashton Cox</td>
                            <td>Junior Technical Author</td>
                            <td>San Francisco</td>
                            <td>66</td>
                            <td>2009/01/12</td>
                            <td>$86,000</td>
                            <td>
                                <a href="" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
                                    <i class="mdi mdi-lead-pencil"></i>
                                </a>

                                <a href="" data-table="example"
                                    class="btn btn-sm btn-danger btn-icon waves-effect  delete-record">
                                    <i class="ri-delete-bin-5-line"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Cedric Kelly</td>
                            <td>Senior Javascript Developer</td>
                            <td>Edinburgh</td>
                            <td>22</td>
                            <td>2012/03/29</td>
                            <td>$433,060</td>
                            <td>
                                <a href="" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
                                    <i class="mdi mdi-lead-pencil"></i>
                                </a>

                                <a href="" data-table="example"
                                    class="btn btn-sm btn-danger btn-icon waves-effect  delete-record">
                                    <i class="ri-delete-bin-5-line"></i>
                                </a>
                            </td>
                        </tr>




                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>UOM</th>
                            <th>Description</th>
                            <th>Created By</th>
                            <th>Created on</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>


            </div>
        </div>
    </div>
    </div>

    <div class="bg-danger w-100 h-10" style="margin: 10px 0 30px 0; background:red; height:5px;width:100%;"></div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1 text-capitalize">
                        define Booklist
                    </h4>
                    <div class="flex-shrink-0">
                        <a href="http://127.0.0.1:8000/students/create?tab=personal"
                            class="btn btn-success btn-label btn-sm">
                            <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New
                        </a>
                    </div>
                </div>
                <!-- end card header -->

                <div class="card-body">
                    <div class="live-preview">
                        <form class="row g-3 needs-validation" novalidate>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select " id="" name="" aria-label="select" required="">
                                        <option value=""> Select </option>
                                        <option value="1">
                                            Pakistani</option>
                                        <option value="2">
                                            British</option>
                                        <option value="3">
                                            Chinese</option>
                                    </select>
                                    <label for="" class="form-label">province</label>
                                    <div class="invalid-tooltip">
                                        required!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select " id="" name="" aria-label="select" required="">
                                        <option value=""> Select </option>
                                        <option value="1">
                                            Pakistani</option>
                                        <option value="2">
                                            British</option>
                                        <option value="3">
                                            Chinese</option>
                                    </select>
                                    <label for="" class="form-label">AY</label>
                                    <div class="invalid-tooltip">
                                        required!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select " id="" name="" aria-label="select" required="">
                                        <option value=""> Select </option>
                                        <option value="1">
                                            Pakistani</option>
                                        <option value="2">
                                            British</option>
                                        <option value="3">
                                            Chinese</option>
                                    </select>
                                    <label for="" class="form-label">Class</label>
                                    <div class="invalid-tooltip">
                                        required!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select " id="" name="" aria-label="select" required="">
                                        <option value=""> Select </option>
                                        <option value="1">
                                            Pakistani</option>
                                        <option value="2">
                                            British</option>
                                        <option value="3">
                                            Chinese</option>
                                    </select>
                                    <label for="" class="form-label">Created By</label>
                                    <div class="invalid-tooltip">
                                        required!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control @if ($errors->has('test_date_time')) is-invalid @endif"
                                            data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d M, Y H:i"
                                            value="{{ old('test_date_time') }}" name="test_date_time" id="test_date_time"
                                            data-enable-time required>
                                        <label for="registrationDate" class="form-label">Created On</label>
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                        <div class="invalid-tooltip">
                                            @if ($errors->has('test_date_time'))
                                                {{ $errors->first('test_date_time') }}
                                            @else
                                                Date Time is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-label-group in-border">
                                    <textarea class="form-control" name="description" id="classGroupDescription"
                                        placeholder="Enter class description here..."></textarea>
                                    <label for="classGroupDescription" class="form-label">Description</label>
                                </div>
                            </div>
                            <div class="col-12 text-end">
                                <button class="btn btn-primary" type="submit">Save</button>
                                <button type="button"
                                    class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Booklist List</h4>
                    <!-- <div class="flex-shrink-0">
                        <div class="form-check form-switch form-switch-right form-switch-md">
                            <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                            <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                        </div>
                    </div> -->
                </div><!-- end card header -->

                <div class="card-body">

                    <table id="example" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Province</th>
                                <th>AY</th>
                                <th>Class</th>
                                <th>Created By</th>
                                <th>Created on</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>System Architect</td>
                                <td>Edinburgh</td>
                                <td>61</td>
                                <td>2011/04/25</td>
                                <td>$320,800</td>
                                <td>
                                    <a href="" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
                                        <i class="mdi mdi-lead-pencil"></i>
                                    </a>

                                    <a href="" data-table="example"
                                        class="btn btn-sm btn-danger btn-icon waves-effect  delete-record">
                                        <i class="ri-delete-bin-5-line"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Province</th>
                                <th>AY</th>
                                <th>Class</th>
                                <th>Created By</th>
                                <th>Created on</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div class="bg-danger w-100 h-10" style="margin: 10px 0 30px 0; background:red; height:5px;width:100%;"></div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1 text-capitalize">
                        define WorkBook Details
                    </h4>
                    <div class="flex-shrink-0">
                        <a href="http://127.0.0.1:8000/students/create?tab=personal"
                            class="btn btn-success btn-label btn-sm">
                            <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New
                        </a>
                    </div>
                </div>
                <!-- end card header -->

                <div class="card-body">
                    <div class="live-preview">
                        <form class="row g-3 needs-validation" novalidate>

                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select " id="" name="" aria-label="select" required="">
                                        <option value=""> Select </option>
                                        <option value="1">
                                            Pakistani</option>
                                        <option value="2">
                                            British</option>
                                        <option value="3">
                                            Chinese</option>
                                    </select>
                                    <label for="" class="form-label">Subject</label>
                                    <div class="invalid-tooltip">
                                        required!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="text" class="form-control " id="" name="" placeholder="Please enter "
                                        value="" required="">
                                    <label for="" class="form-label">Work Book Name</label>
                                    <div class="invalid-tooltip">
                                        required!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="text" class="form-control " id="" name="" placeholder="Please enter"
                                        value="" required="">
                                    <label for="" class="form-label">Distributer/Publisher</label>
                                    <div class="invalid-tooltip">
                                        required!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-label-group in-border">
                                    <textarea class="form-control" name="description" id="classGroupDescription"
                                        placeholder="Enter class description here..."></textarea>
                                    <label for="classGroupDescription" class="form-label">Description</label>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select " id="" name="" aria-label="select" required="">
                                        <option value=""> Select </option>
                                        <option value="1">
                                            Pakistani</option>
                                        <option value="2">
                                            British</option>
                                        <option value="3">
                                            Chinese</option>
                                    </select>
                                    <label for="" class="form-label">Created By</label>
                                    <div class="invalid-tooltip">
                                        required!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control @if ($errors->has('test_date_time')) is-invalid @endif"
                                            data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d M, Y H:i"
                                            value="{{ old('test_date_time') }}" name="test_date_time" id="test_date_time"
                                            data-enable-time required>
                                        <label for="registrationDate" class="form-label">Created On</label>
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                        <div class="invalid-tooltip">
                                            @if ($errors->has('test_date_time'))
                                                {{ $errors->first('test_date_time') }}
                                            @else
                                                Date Time is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 text-end">
                                <button class="btn btn-primary" type="submit">Save</button>
                                <button type="button"
                                    class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">WorkBook Detail List</h4>
                    <!-- <div class="flex-shrink-0">
                                                                                                                                                            <div class="form-check form-switch form-switch-right form-switch-md">
                                                                                                                                                                <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                                                                                                                                                                <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                                                                                                                                                            </div>
                                                                                                                                                        </div> -->
                    <div class="form-check form-switch form-switch-right form-switch-md">
                        <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                        <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                    </div>
                </div> -->
            </div><!-- end card header -->

            <div class="card-body">

                <table id="example" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>Work Book Name</th>
                            <th>Distributer / Publisher</th>
                            <th>Description</th>
                            <th>Created By</th>
                            <th>Created on</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>System Architect</td>
                            <td>Edinburgh</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>$320,800</td>
                            <td>
                                <a href="" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
                                    <i class="mdi mdi-lead-pencil"></i>
                                </a>

                                <a href="" data-table="example"
                                    class="btn btn-sm btn-danger btn-icon waves-effect  delete-record">
                                    <i class="ri-delete-bin-5-line"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Work Book Name</th>
                            <th>Distributer / Publisher</th>
                            <th>Description</th>
                            <th>Created By</th>
                            <th>Created on</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    </div>

    <div class="bg-danger w-100 h-10" style="margin: 10px 0 30px 0; background:red; height:5px;width:100%;"></div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1 text-capitalize">
                        define Book Detail
                    </h4>
                    <div class="flex-shrink-0">
                        <a href="http://127.0.0.1:8000/students/create?tab=personal"
                            class="btn btn-success btn-label btn-sm">
                            <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Add New
                        </a>
                    </div>
                </div>
                <!-- end card header -->

                <div class="card-body">
                    <div class="live-preview">
                        <form class="row g-3 needs-validation" novalidate>
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-1 text-nowrap">
                                        <label class="">Book Type:</label>
                                    </div>
                                    <div class="col-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="inlineCheckbox3"
                                                value="option3">
                                            <label class="form-check-label" for="inlineCheckbox3">Book</label>
                                        </div>
                                    </div>
                                    <div class="col-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="inlineCheckbox3"
                                                value="option3">
                                            <label class="form-check-label" for="inlineCheckbox3">Copy</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select " id="" name="" aria-label="select" required="">
                                        <option value=""> Select </option>
                                        <option value="1">
                                            Pakistani</option>
                                        <option value="2">
                                            British</option>
                                        <option value="3">
                                            Chinese</option>
                                    </select>
                                    <label for="" class="form-label">Subject</label>
                                    <div class="invalid-tooltip">
                                        required!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="text" class="form-control " id="" name="" placeholder="Please enter "
                                        value="" required="">
                                    <label for="" class="form-label">Book Name</label>
                                    <div class="invalid-tooltip">
                                        required!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="text" class="form-control " id="" name="" placeholder="Please enter"
                                        value="" required="">
                                    <label for="" class="form-label">Distributer/Publisher</label>
                                    <div class="invalid-tooltip">
                                        required!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="text" class="form-control " id="" name="" placeholder="Please enter"
                                        value="" required="">
                                    <label for="" class="form-label">Copy Code</label>
                                    <div class="invalid-tooltip">
                                        required!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="text" class="form-control " id="" name="" placeholder="Please enter"
                                        value="" required="">
                                    <label for="" class="form-label">Copy Pages</label>
                                    <div class="invalid-tooltip">
                                        required!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <input type="text" class="form-control " id="" name="" placeholder="Please enter"
                                        value="" required="">
                                    <label for="" class="form-label">Copy Qty</label>
                                    <div class="invalid-tooltip">
                                        required!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-label-group in-border">
                                    <textarea class="form-control" name="description" id="classGroupDescription"
                                        placeholder="Enter class description here..."></textarea>
                                    <label for="classGroupDescription" class="form-label">Description</label>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <select class="form-select " id="" name="" aria-label="select" required="">
                                        <option value=""> Select </option>
                                        <option value="1">
                                            Pakistani</option>
                                        <option value="2">
                                            British</option>
                                        <option value="3">
                                            Chinese</option>
                                    </select>
                                    <label for="" class="form-label">Created By</label>
                                    <div class="invalid-tooltip">
                                        required!
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-label-group in-border">
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control @if ($errors->has('test_date_time')) is-invalid @endif"
                                            data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d M, Y H:i"
                                            value="{{ old('test_date_time') }}" name="test_date_time" id="test_date_time"
                                            data-enable-time required>
                                        <label for="registrationDate" class="form-label">Created On</label>
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                        <div class="invalid-tooltip">
                                            @if ($errors->has('test_date_time'))
                                                {{ $errors->first('test_date_time') }}
                                            @else
                                                Date Time is required!
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 text-end">
                                <button class="btn btn-primary" type="submit">Save</button>
                                <button type="button"
                                    class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Book Detail List</h4>
                    <!-- <div class="flex-shrink-0">
                                                                                                                                                            <div class="form-check form-switch form-switch-right form-switch-md">
                                                                                                                                                                <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                                                                                                                                                                <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                                                                                                                                                            </div>
                                                                                                                                                        </div> -->
                    <div class="form-check form-switch form-switch-right form-switch-md">
                        <label for="FormVaidationCustom" class="form-label text-muted">Show Code</label>
                        <input class="form-check-input code-switcher" type="checkbox" id="FormVaidationCustom">
                    </div>
                </div> -->
            </div><!-- end card header -->

            <div class="card-body table-overflow-cover">

                <table id="example" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Book Name</th>
                            <th>Distributer / Publisher</th>
                            <th>Copy Code</th>
                            <th>Copy Page</th>
                            <th>Copy Qty</th>
                            <th>Copy Despcription</th>
                            <th>Created By</th>
                            <th>Created on</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>System Architect</td>
                            <td>Edinburgh</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>System Architect</td>
                            <td>Edinburgh</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>$320,800</td>
                            <td>
                                <a href="" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
                                    <i class="mdi mdi-lead-pencil"></i>
                                </a>

                                <a href="" data-table="example"
                                    class="btn btn-sm btn-danger btn-icon waves-effect  delete-record">
                                    <i class="ri-delete-bin-5-line"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Subject</th>
                            <th>Book Name</th>
                            <th>Distributer / Publisher</th>
                            <th>Copy Code</th>
                            <th>Copy Page</th>
                            <th>Copy Qty</th>
                            <th>Copy Despcription</th>
                            <th>Created By</th>
                            <th>Created on</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    </div>

    <div class="bg-danger w-100 h-10" style="margin: 10px 0 30px 0; background:red; height:5px;width:100%;"></div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center text-capitalize mb-2">
                        <u>royalty re-imbursement note</u>
                    </h3>

                    <table class="table border-0 mt-3 w-100">
                        <tbody>
                            <tr class="border-0">
                                <th class="text-capitalize text-nowrap border-0">
                                    campus name:
                                </th>
                                <td class="text-capitalize w-100 border-0">
                                    <u>
                                        the educators
                                    </u>
                                </td>
                                <th class="text-capitalize text-nowrap border-0">
                                    TE / CR / 001
                                </th>
                            </tr>
                            <tr>
                                <th class="text-capitalize text-nowrap border-0">
                                    address:
                                </th>
                                <td class="text-capitalize w-100 border-0">
                                    <u>
                                        lahore pakistan
                                    </u>
                                </td>
                                <th class="text-capitalize text-nowrap border-0">
                                    may 23, 2022
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>


                <div class="card-body">

                    <table id="example" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center text-nowrap">Sr. No.</th>
                                <th class="text-center w-100"> Description</th>
                                <th class="text-center text-nowrap"> amount (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>System Architect</td>
                                <td>Edinburgh</td>
                                <td>61</td>
                            </tr>
                            <tr>
                                <th class="text-left text-capitalize" colspan="3"> amount in words</th>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-center text-nowrap">Sr. No.</th>
                                <th class="text-center"> Description</th>
                                <th class="text-center text-nowrap"> amount (Rs.)</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-danger w-100 h-10" style="margin: 10px 0 30px 0; background:red; height:5px;width:100%;"></div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body table-overflow-cover">
                    <table id="example" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <thead>
                            <tr>
                                <td colspan="2" class="text-center align-middle">
                                    <img src="" class="img-fluid" alt="logo">
                                </td>
                                <td colspan="5" class="text-capitalize text-center text-black">
                                    <h1>
                                        the educatores
                                    </h1>
                                    <h2>
                                        campus
                                    </h2>
                                    <h5>
                                        apr-22 to may-22
                                    </h5>
                                </td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row" class="text-center">Sr. No.</th>
                                <th class="text-center"> Description</th>
                                <th class="text-center"> amount (Rs.)</th>
                                <th class="text-center">Sr. No.</th>
                                <th class="text-center"> Description</th>
                                <th class="text-center"> amount (Rs.)</th>
                                <th class="text-center"> Description</th>
                                <th class="text-center"> amount (Rs.)</th>
                            </tr>

                            <tr>
                                <td scope="row">1</td>
                                <td>
                                    45000
                                </td>
                                <td>
                                    45000
                                </td>
                                <td>
                                    45000
                                </td>
                                <td>
                                    45000
                                </td>
                                <td>
                                    45000
                                </td>
                                <td>
                                    45000
                                </td>
                                <td>
                                    45000
                                </td>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-center text-capitalize">total amount : 255</th>
                                <th>0</th>
                                <th>8767</th>
                                <th>0</th>
                                <th>7878</th>
                            </tr>
                            <tr>
                                <td colspan="7" class="text-end">royalty @ 9%</td>
                                <td class="text-capitalize">131232</td>
                            </tr>
                            <tr>
                                <th colspan="7" class="text-end"></th>
                                <th class="text-capitalize"></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-body table-overflow-cover">
                    <table id="example" class="table table-bordered table-striped align-middle table-nowrap mb-0"
                        style="width:100%">
                        <tbody>
                            <tr>
                                <th scope="row" class="text-center">fee package</th>
                                <th class="text-center"> class range</th>
                                <th class="text-center">tuition</th>
                                <th class="text-center">laboratory</th>
                                <th class="text-center"> computer</th>
                                <th class="text-center">admission</th>
                                <th class="text-center"> medical</th>
                                <th class="text-center">annual</th>
                                <th class="text-center">monthly fee</th>
                            </tr>
                            <tr>
                                <td scope="row">
                                    pre-school-2022
                                </td>
                                <td>
                                    play group to KG
                                </td>
                                <td>
                                    2750
                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    2700
                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    2750
                                </td>
                                <td>
                                    2750
                                </td>
                            </tr>


                            <tr>
                                <th colspan="2" class="text-end">monthly fee:</th>
                                <td colspan="7" class="text-capitalize">tuition fee</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-danger w-100 h-10" style="margin: 10px 0 30px 0; background:red; height:5px;width:100%;"></div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <table class="table border-0 mt-3 w-100">
                        <tbody>
                            <tr class="border-0">
                                <td class="text-center align-middle border-0">
                                    <img src="" class="img-fluid" alt="logo">
                                </td>
                                <td class="text-capitalize text-center text-black border-0">
                                    <h1>
                                        campus name
                                    </h1>
                                    <h5>
                                        (2022 - 2023)
                                    </h5>
                                    <h2>
                                        lesson plan topic
                                    </h2>
                                    <h5>
                                        team 1 week 1 day 1
                                    </h5>
                                </td>
                                <td class="border-0"></td>
                            </tr>
                            <tr class="border-0">
                                <td class="text-capitalize text-nowrap border-0">
                                    <b>school type</b> : early years
                                </td>
                                <td class="text-capitalize border-0">
                                    <b>class</b> : two
                                </td>
                                <td class="text-capitalize text-nowrap border-0">
                                    <b> taught date</b> : (10-06-2022 to 10-06-2022)
                                </td>
                            </tr>
                            <tr class="border-0">
                                <td class="text-capitalize text-nowrap border-0">
                                    <b>subject</b> : english
                                </td>
                                <td class="text-capitalize border-0">
                                    <b>topic</b> : plants
                                </td>
                                <td class="text-capitalize text-nowrap border-0">
                                    <b> theme / (unit / chapter)</b> : plants
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card-body">
                    <table id="example" class="table table-bordered table-striped align-middle mb-0 text-align-custom"
                        style="width:100%">
                        <tbody>
                            <tr>
                                <th scope="row" class="text-left">
                                    student's learning outcomes (SLOs)
                                </th>
                                <th class="text-center">methodlogy</th>
                                <th class="text-center">time <br /> 60mints </th>
                                <th class="text-center">resources</th>
                                <th class="text-center">assessment</th>

                            </tr>
                            <tr>
                                <td scope="row" rowspan="2">
                                    by the end of the lesson students would have:
                                    <ul>
                                        <li>
                                            identified and formed small letters a-z with the correct sequence
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    <dl>
                                        <dt>recap:</dt>
                                        <dd>
                                            <ul>
                                                <li>
                                                    show some object to the students and ask them to say the initial sound
                                                    of the project
                                                </li>
                                                <li>
                                                    Tell them to come and write the initial sound on the whiteboard e.g.
                                                    book (b), crayon(c), pencil (p), marker(m), paper(p), drawer or
                                                    doormat(d), story (s) and so on
                                                </li>
                                            </ul>
                                        </dd>
                                    </dl>

                                </td>
                                <td>
                                    5 min
                                </td>
                                <td>
                                    objects from the class e.g. table, book girl,
                                </td>
                                <td rowspan="2">
                                    students will be assessed on :
                                    <ul>
                                        <li>
                                            identified and formed small letters a-z with the correct sequence
                                        </li>
                                    </ul>
                                </td>

                            </tr>

                            <tr>
                                <td>

                                    <dl>
                                        <dt>
                                            Introduction:

                                        </dt>
                                        <dd>
                                            <ul>
                                                <li>
                                                    Tell them that there are 26 letters in our English language. Each letter
                                                    has a different sound. We use small alphabets to show the sounds, we
                                                    also call them phonics. These sounds help us to read the words. (TIB)
                                                </li>
                                                <li>
                                                    Show the video: <b>Writing Alphabet Letters for Children</b> or the
                                                    teacher can
                                                    give the demo to the students on the big whiteboard of the class.
                                                </li>
                                                <li>
                                                    While giving the demo explain to the students that each letter starts
                                                    with a different line, as we have grass line, root line, and skyline.
                                                </li>
                                            </ul>
                                        </dd>
                                    </dl>
                                </td>
                                <td>
                                    20 mins
                                </td>
                                <td>

                                    Writing Alphabet Letters for Children (18 mins
                                    8 secs) from: <a href=" https://www.yout ube.com/watch?v -Sw2KZki eaA">
                                        https://www.yout ube.com/watch?v -Sw2KZki eaA
                                    </a>
                                    Focus on the small alphabets from the sequence and skip the remaining part from the
                                    video)
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="live-preview mt-5">
                        <form class="row g-3 needs-validation" novalidate>
                            <div class="col-md-6">
                                <div class="form-label-group in-border">
                                    <textarea class="form-control" name="description" id="classGroupDescription"
                                        placeholder="Enter class description here..."></textarea>
                                    <label for="classGroupDescription" class="form-label">Evaluation of the Student
                                        Leaming</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-group in-border">
                                    <textarea class="form-control" name="description" id="classGroupDescription"
                                        placeholder="Enter class description here..."></textarea>
                                    <label for="classGroupDescription" class="form-label">Evaluation of
                                        teaching</label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="bg-danger w-100 h-10" style="margin: 10px 0 30px 0; background:red; height:5px;width:100%;"></div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <table class="table border-0 mt-3 w-100">
                        <tbody>
                            <tr class="border-0">
                                <td class="text-center align-middle border-0">
                                    <img src="" class="img-fluid" alt="logo">
                                </td>
                                <td class="text-capitalize text-center text-black border-0">
                                    <h1>
                                        campus name
                                    </h1>
                                    <h5>
                                        (2022 - 2023)
                                    </h5>
                                    <h2>
                                        lesson plan topic
                                    </h2>
                                    <h5>
                                        team 1 week 1 day 1
                                    </h5>
                                </td>
                                <td class="border-0"></td>
                            </tr>
                            <tr class="border-0">
                                <td class="text-capitalize text-nowrap border-0">
                                    <b>school type</b> : early years
                                </td>
                                <td class="text-capitalize border-0">
                                    <b>class</b> : two
                                </td>
                                <td class="text-capitalize text-nowrap border-0">
                                    <b>subject</b> : english
                                </td>
                            </tr>
                            <tr class="border-0">
                                <td class="text-capitalize text-nowrap border-0">
                                    <b> theme / (unit / chapter)</b> : plants
                                </td>
                                <td class="text-capitalize border-0">
                                    <b>topic</b> : plants
                                </td>
                                <td class="border-0"></td>
                            </tr>
                        </tbody>
                    </table>


                    <div class="live-preview mt-5">
                        <form class="row g-3 needs-validation" novalidate>

                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-label-group in-border">
                                            <div class="input-group">
                                                <input type="text"
                                                    class="form-control @if ($errors->has('test_date_time')) is-invalid @endif"
                                                    data-provider="flatpickr" data-date-format="Y-m-d"
                                                    data-altFormat="d M, Y H:i" value="{{ old('test_date_time') }}"
                                                    name="test_date_time" id="test_date_time" data-enable-time required>
                                                <label for="registrationDate" class="form-label">taught date</label>
                                                <div class="input-group-text bg-primary border-primary text-white">
                                                    <i class="ri-calendar-2-line"></i>
                                                </div>
                                                <div class="invalid-tooltip">
                                                    @if ($errors->has('test_date_time'))
                                                        {{ $errors->first('test_date_time') }}
                                                    @else
                                                        Date Time is required!
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-label-group in-border">
                                            <div class="input-group">
                                                <input type="text"
                                                    class="form-control @if ($errors->has('test_date_time')) is-invalid @endif"
                                                    data-provider="flatpickr" data-date-format="Y-m-d"
                                                    data-altFormat="d M, Y H:i" value="{{ old('test_date_time') }}"
                                                    name="test_date_time" id="test_date_time" data-enable-time required>
                                                <label for="registrationDate" class="form-label">To</label>
                                                <div class="input-group-text bg-primary border-primary text-white">
                                                    <i class="ri-calendar-2-line"></i>
                                                </div>
                                                <div class="invalid-tooltip">
                                                    @if ($errors->has('test_date_time'))
                                                        {{ $errors->first('test_date_time') }}
                                                    @else
                                                        Date Time is required!
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-label-group in-border">
                                    <textarea class="form-control" name="description" id="classGroupDescription"
                                        placeholder="Enter class description here..."></textarea>
                                    <label for="classGroupDescription" class="form-label">Evaluation of the Student
                                        Leaming</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-label-group in-border">
                                    <textarea class="form-control" name="description" id="classGroupDescription"
                                        placeholder="Enter class description here..."></textarea>
                                    <label for="classGroupDescription" class="form-label">Evaluation of
                                        teaching</label>
                                </div>
                            </div>

                            <div class="col-12 text-end">
                                <button class="btn btn-primary" type="submit">Save</button>
                                <button type="button"
                                    class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@push('header_scripts')
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#example').DataTable({
                retrieve: true,
                processing: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
                    searchPlaceholder: "Search..."
                },
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,

            });
        });
    </script>
@endpush
