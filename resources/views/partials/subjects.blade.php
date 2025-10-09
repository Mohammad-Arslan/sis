<table class="table table-borderless table-hover table-nowrap align-middle mb-0">
    <thead class="table-light">
    <tr class="text-muted">
        <th scope="col">Name</th>
        <th scope="col" style="width: 16%;" class="text-end">Action</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($subjects as $subject)
        <tr>
            <td>
                {{$subject['subject_name']}}
            </td>
            <td class="text-end">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="subject_id[]" value="{{$subject['id']}}">
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
