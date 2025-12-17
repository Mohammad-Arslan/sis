<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">{{isset($generalBehaviour) ? 'Update' : 'Add'}} General Behaviour</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" method="POST" action="{{ isset($generalBehaviour) ? route('general-behaviour.update',$generalBehaviour['id']) : route('general-behaviour.store') }}" novalidate>
                    @if(isset($generalBehaviour))
                        @method('PATCH')
                    @endif
                    <div class="col-md-12 col-sm-12 mt-4">
                        <div class="form-label-group in-border">
                            <textarea class="form-control" name="title" id="title" placeholder="Enter title here...">{{ old('title') ? old('title') : (isset($generalBehaviour) ? $generalBehaviour->title : '')}}</textarea>
                            <label for="title" class="form-label">Title</label>
                            <div class="invalid-tooltip">
                                @if ($errors->has('title'))
                                    {{ $errors->first('title') }}
                                @else
                                    Title is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('class_id')) is-invalid @endif" id="class_id" name="class_id">
                                <option value="">Please select a Class</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}" {{ isset($generalBehaviour) && $generalBehaviour['class_id'] == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                            <label for="section" class="form-label">Class <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('class_id'))
                                    {{ $errors->first('class_id') }}
                                @else
                                    Class is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('has_parent')) is-invalid @endif" id="has_parent" name="has_parent" required>
                                <option value="">Please select</option>
                                <option value="1" {{ isset($generalBehaviour) && $generalBehaviour['parent_id'] ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ isset($generalBehaviour) && !$generalBehaviour['parent_id'] ? 'selected' : '' }}>No</option>
                            </select>
                            <label for="section" class="form-label">Type Has Parent? <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('has_parent'))
                                    {{ $errors->first('has_parent') }}
                                @else
                                    Has Parent is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 parent-div" style="display: {{isset($generalBehaviour) && $generalBehaviour['parent_id'] ? '' : 'none'}}">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('parent_id')) is-invalid @endif" id="parent_id" name="parent_id">
                                <option value="0">Please select a Parent</option>
                                @foreach ($general_behaviours as $general_behaviour)
                                    <option value="{{ $general_behaviour->id }}" {{ isset($generalBehaviour) && $generalBehaviour['parent_id'] == $general_behaviour->id ? 'selected' : '' }}>{{ $general_behaviour->title }}</option>
                                @endforeach
                            </select>
                            <label for="section" class="form-label">Parent Behaviour <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('parent_id'))
                                    {{ $errors->first('parent_id') }}
                                @else
                                    Parent is required!
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select @if($errors->has('status')) is-invalid @endif" name="status" required>
                                <option value="">Please select</option>
                                <option value="active" {{old('status') == 'active' ? 'selected' : (isset($generalBehaviour) && $generalBehaviour->status == 'active' ? 'selected' : '') }}>Active</option>
                                <option value="inactive" {{old('status') == 'inactive' ? 'selected' : (isset($generalBehaviour) && $generalBehaviour->status == 'inactive' ? 'selected' : '') }}>In Active</option>
                            </select>
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <div class="invalid-tooltip">
                                @if($errors->has('status'))
                                    {{ $errors->first('status') }}
                                @else
                                    Status is required!
                                @endif
                            </div>
                        </div>
                    </div>

                    @csrf
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Submit form</button>
                        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#has_parent').on('change',function (){
                if($(this).val() == '1'){
                    $('.parent-div').show();
                    $("#parent_id").prop('required',true);
                }
                else{
                    $('.parent-div').hide();
                    $("#parent_id").prop('required',false).val('0');
                }
            });
        });
    </script>
@endpush
