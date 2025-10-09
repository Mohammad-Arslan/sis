@foreach ($daily_lesson_plans as $lesson_plan)
    <tr>
        <td>
            @permission('duplicate-lesson-plan')<input type="checkbox" name="lesson_plan_row[]" id="lesson_plan_row_{{$lesson_plan->id}}" value="{{$lesson_plan->id}}">@endpermission {{ $lesson_plan->topic }}
        </td>
        <td>Day {{ $lesson_plan->day }}</td>
        <td>
            <span class="badge badge-outline-warning pending_for_approval_row{{ $lesson_plan->id }}"
                style="display:{{ $lesson_plan->approval_status == 'pending_for_approval' ? '' : 'none' }}">Pending for
                approval</span>
            <span class="badge badge-outline-info approved_row{{ $lesson_plan->id }}"
                style="display:{{ $lesson_plan->approval_status == 'approved' ? '' : 'none' }}">Approved</span>
            <span class="badge badge-outline-info publish_row{{ $lesson_plan->id }}"
                style="display:{{ $lesson_plan->approval_status == 'publish' ? '' : 'none' }}">Published</span>
            <span class="badge badge-outline-success draft_row{{ $lesson_plan->id }}"
                style="display:{{ empty($lesson_plan->approval_status) ? '' : 'none' }}">Draft</span>
        </td>
        <td>{{ \Carbon\Carbon::parse($lesson_plan->created_at)->format('d-m-Y') }}</td>
        @if (!auth()->user()->hasRole('teacher'))
            <td>{{ isset($lesson_plan['creater']) ? $lesson_plan['creater']['name'] : '-' }}</td>
            <td>{{ isset($lesson_plan['user']) ? $lesson_plan['user']['name'] : '-' }}</td>
        @endif
        <td>
            <div>
                <a href="#" role="button" id="dropdownMenuLink2" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-more-2-fill"></i>
                </a>

                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink2">
                    @permission('view-lesson-plan')
                        <li><a class="dropdown-item" href="{{ route('lesson-plans.show', $lesson_plan->id) }}">View</a>
                        </li>
                    @endpermission
                    @permission('edit-lesson-plan')
                        @if (($lesson_plan->approval_status =='pending_for_approval' || $lesson_plan->approval_status =='approved' ||empty($lesson_plan->approval_status)))
                            <li><a class="dropdown-item" href="{{ route('lesson-plans.edit', $lesson_plan->id) }}">Edit</a>
                            </li>
                        @endif
                    @endpermission

                    @permission('add-lessonplan-taughtdate')
                        <li><a class="dropdown-item"
                                href="{{ route('lesson-plans.taught-date', $lesson_plan->id) }}">Taught
                                Date / Evaluation</a></li>
                    @endpermission
                    <li><a class="dropdown-item show-attachment-modal"
                            data-attachments-route="{{ route('lesson-plans.list-attachments', $lesson_plan->id) }}"
                            href="javascript:void(0)">Attachments</a></li>
                    @if (empty($lesson_plan->approval_status) &&
                        auth()->user()->hasPermission('sendfor-approval-lessonplan'))
                        <li class="sendForApproval_lessonplan_btn{{ $lesson_plan->id }}"><a href="javascript:void(0)"
                                class="dropdown-item change_lessonplan_status" data-status="pending_for_approval"
                                data-approval-for="day" data-lessonplan-id="{{ $lesson_plan->id }}">Send for
                                approval</a></li>
                    @endif
                    @if ($lesson_plan->approval_status == 'pending_for_approval' &&
                        auth()->user()->hasPermission('approve-lesson-plan'))
                        <li class="approved_lessonplan_btn{{ $lesson_plan->id }}"><a href="javascript:void(0)"
                                class="dropdown-item change_lessonplan_status" data-status="approved"
                                data-approval-for="day" data-lessonplan-id="{{ $lesson_plan->id }}">Approve</a>
                        </li>
                    @endif
                    @if ($lesson_plan->approval_status == 'approved' &&
                        auth()->user()->hasPermission('publish-lesson-plan'))
                        <li class="publish_lessonplan_btn{{ $lesson_plan->id }}"><a href="javascript:void(0)"
                                class="dropdown-item change_lessonplan_status" data-status="publish"
                                data-approval-for="day" data-lessonplan-id="{{ $lesson_plan->id }}">Publish</a>
                        </li>
                    @endif
                    @if ($lesson_plan->approval_status == 'publish' &&
                        auth()->user()->hasPermission('publish-lesson-plan'))
                        <li class="approved_lessonplan_btn{{ $lesson_plan->id }}"><a href="javascript:void(0)"
                                class="dropdown-item change_lessonplan_status" data-status="approved"
                                data-approval-for="day" data-lessonplan-id="{{ $lesson_plan->id }}">Unpublish</a>
                        </li>
                    @endif
                    @permission('duplicate-lesson-plan')
                        <li>
                            <a href="javascript:void(0)" class="dropdown-item duplicate-lesson-plan"
                                data-duplicate-lp-route="{{ route('lesson-plans.duplicate', $lesson_plan->id) }}">Duplicate</a>
                        </li>
                    @endpermission

                    @if (auth()->user()->hasPermission('delete-lesson-plan'))
                        <li>
                            <a href="{{ route('lesson-plans.destroy', $lesson_plan->id) }}"
                                data-table="dailyLessonPlan_Datatable" data-isajax="false"
                                class="dropdown-item delete-record">
                                Delete
                            </a>
                        </li>
                    @elseif (auth()->user()->hasRole('subject_coordinator') && empty($lesson_plan->approval_status) && auth()->user()->hasPermission('delete-draft-lesson-plan'))
                        <li>
                            <a href="{{ route('lesson-plans.destroy', $lesson_plan->id) }}"
                                data-table="dailyLessonPlan_Datatable" data-isajax="false"
                                class="dropdown-item delete-record">
                                Delete
                            </a>
                        </li>
                    @elseif (auth()->user()->hasRole('subject_coordinator') && $lesson_plan->approval_status=='pending_for_approval' && auth()->user()->hasPermission('delete-pending-approvals'))
                        <li>
                            <a href="{{ route('lesson-plans.destroy', $lesson_plan->id) }}"
                                data-table="dailyLessonPlan_Datatable" data-isajax="false"
                                class="dropdown-item delete-record">
                                Delete
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </td>
    </tr>
@endforeach
