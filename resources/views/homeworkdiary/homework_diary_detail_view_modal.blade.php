<style>
    .my-custom-scrollbar {
    position: relative;
    height: 400px;
    overflow: auto;
    }
    .table-wrapper-scroll-y {
    display: block;
    }
</style>
<div id="diaryViewModal" class="modal fade zoomIn" tabindex="-1" aria-labelledby="zoomInModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoomInModalLabel">View Homework</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mt-5">
                    <div class="col-xxl-12">
                        <div class="card mt-xxl-n5">
                            <div class="card-body p-4">
                                <div class="col-md-12 col-sm-12 table-wrapper-scroll-y my-custom-scrollbar">
                                    <table class="table table-bordered table-striped align-middle table-wrap mb-0"
                                    style="width:100%" id="homework-table">
                                        <thead>
                                            <tr>
                                                <th class="col-md-2">Subject</th>
                                                <th class="col-md-7">Home Work</th>
                                                <th class="col-md-2">Attachments</th>
                                                <th class="col-md-1">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($data as $homework_detail)
                                            <tr>
                                                <td class="col-md-2">{{$homework_detail['subject']}}</td>
                                                <td class="col-md-7">{{$homework_detail['homework']}}</td>
                                                <td class="col-md-2">
                                                    {{$homework_detail['attachment']}}
                                                </td>
                                                <td class="col-md-1" style="padding: 5px; text-align: center;">
                                                    <a data-url="{{route('homeWorkDiaryDetial.edit', $homework_detail['id'])}}" data-target="#diaryEditModal" class="btn btn-sm btn-success btn-icon waves-effect waves-light show-modal" title="Edit Home Work" data-bs-dismiss="modal"><i class="mdi mdi-lead-pencil"></i></a>

                                                    <a href="{{ route('homeWorkDiaryDetial.destroy', $homework_detail['id']) }}" data-table="homework-list-data-table"
                                                        class="btn btn-sm btn-danger btn-icon waves-effect waves-light delete-row"  title="Delete">
                                                        <i class="ri-delete-bin-5-line"></i>
                                                    </a>

                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="border mt-3 border-dashed"></div>

                                <div class="col-12 text-end">
                                    <button type="button" class="btn btn-secondary bg-gradient waves-effect waves-light" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

</script>
