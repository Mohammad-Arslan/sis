<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel" aria-modal="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="LessonPlanModalLabel">Franchise Documents</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="col-md-12 col-sm-12 mt-6 mb-6 p-6">
                <div class="table-responsive">
                    <table class="table table-nowrap mb-0 p-6" id="franchiseApplicationDocTable" style="width:100%">
                        <thead class="table-light">
                        <tr>
                            <th scope="col">Document Type</th>
                            <th scope="col">Name</th>
                            @if(auth()->user())
                                <th scope="col">Uploaded By</th>
                            @endif
                            <th scope="col">Date</th>
                            <th scope="col">Remarks</th>
                            @if (auth()->user() && auth()->user()->hasPermission('delete-franchapp-upload-doc'))
                            <th scope="col" class="text-center">Actions</th>
                            @endif
                        </tr>
                        </thead>
            @forelse($franchise_application_attachments as $attachment)
                <tr>
                    <td>{{ $attachment['attachment_type']['name'] }}</td>

                    <td>
                        <a href="{{ get_file_from_s3('franchise_application_attachments/' . $attachment['franchise_application_id'] . '/' . $attachment->file_name) }}"
                            class="avatar-group-item" target="_blank">{{ $attachment->file_name }}</a>
                        </td>
                    @if (auth()->user())
                        <td>{{ !empty($attachment['user']) ? $attachment['user']['name'] : $attachment['franchise_application']['appl_name'] . ' ' . $attachment['franchise_application']['appl_last_name'] . ' (Applicant)' }}
                        </td>
                    @endif
                    <td>{{ \Carbon\Carbon::parse($attachment['uploaded_date'])->format('d-m-Y') }}</td>
                    <td>{{ $attachment['details'] }}</td>
                    <td class="text-center">
                        @if (auth()->user() && auth()->user()->hasPermission('delete-franchapp-upload-doc'))
                            <a href="javascript:void(0);" class="link-danger fs-15 remove_attachment"
                                data-table="schoolBuildingTable" data-id="{{ $attachment['id'] }}"
                                data-route="{{ route('remove-franchise-application-docs', $attachment['id']) }}"
                                data-franchise_application_id="{{ $attachment['franchise_application_id'] }}"><i
                                    class="ri-delete-bin-line"></i></a>
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            @empty
                <tr class="text-center">
                    <td colspan="5">No Record Found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

        </div>
    </div>
</div>
