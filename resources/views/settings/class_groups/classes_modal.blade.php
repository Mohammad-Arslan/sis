@foreach($classes as $key => $class)
    <div class="form-check mb-3">
        <input class="form-check-input" data-class_group_id="{{$class_group_id}}" value="{{$class->id}}" type="checkbox" id="formCheck{{ $key }}" {{ in_array($class->id, $class_group_classes) ? 'checked': ""}}>
        <label class="form-check-label" for="formCheck{{ $key }}">
            {{$class->class_name}}
        </label>
    </div>
@endforeach
