<table class="table table-borderless table-hover table-nowrap align-middle mb-0">
    <thead class="table-light">
    <tr class="text-muted">
        <th scope="col">Name</th>
        <th scope="col" style="width: 16%;" class="text-end">Action</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($employees as $employee)
        <tr>
            <td>
                <div class="d-flex align-items-center">
                    <img src="{{ default_image() }}" alt="" class="avatar-xs rounded-circle me-2">
                    <div class="ms-2">
                        <h5 class="fs-14 my-1"><a href="{{route('edit-employee',$employee['id']).'?tab=basic_info'}}" class="text-reset">{{$employee['user']['first_name'] . ' '.$employee['user']['middle_name'] .' '.$employee['user']['last_name'] }}</a></h5>
                        <span class="text-muted">Emp ID. {{ $employee['id'] }}</span>
                    </div>
                </div>
            </td>
            <td class="text-end">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="employee_id" value="{{ $employee['id'] }}">
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="2" class="text-center">No Record Found</td>
        </tr>
    @endforelse
    </tbody><!-- end tbody -->
</table><!-- end table -->
