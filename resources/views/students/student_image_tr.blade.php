<div class="d-flex gap-2 align-items-center">
    <div class="flex-shrink-0">
        <img src="{{ get_file_from_s3('images/' . ($row['student_image'] ?? ''), $row['student_image'] ?? '') }}"
            alt="" class="avatar-xs rounded-circle" />
    </div>
    <div class="flex-grow-1">
        <a
            href="{{ route('students.edit', $row['id'] ?? '') . '?tab=personal' }}">
            {{ ($row['first_name'] ?? '') . ' ' . ($row['middle_name'] ?? '') . ' ' . ($row['last_name'] ?? '') }}
        </a>
    </div>
</div>
