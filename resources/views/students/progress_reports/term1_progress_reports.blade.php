<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Term 1 Progress Report</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 0.5em;
        }
    </style>
</head>
<body>
<h1>Term 1 Progress Report</h1>
<table>
    <tr>
        <th rowspan="2">Subjects</th>
        @foreach($assessment_weightage as $assessment_name => $weightage)
            <th>{{ $assessment_name }}</th>
        @endforeach
        <th>Total Marks</th>
    </tr>
    <tr>
        @foreach($assessment_weightage as $assessment_name => $weightage)
            <th>{{ $weightage }}</th>
        @endforeach
        <th>{{array_sum($assessment_weightage) }}</th>
    </tr>
        @foreach($all_assessment_data as $assessment => $single_subject_mark)
            <tr>
                <td>{{$single_subject_mark['subject_name']}}</td>
                @foreach($assessment_weightage as $assessment_name => $weightage)
                    @if(array_key_exists($assessment_name, $single_subject_mark))
                        <td>{{$single_subject_mark[$assessment_name]}}</td>
                    @else
                        <td></td>
                    @endif
                @endforeach
            {{--@foreach($single_subject_mark as $mark)
                    <td>{{$mark}}</td>
            @endforeach--}}
            {{--@if(count($single_subject_mark) < count($assessment_weightage) )
                @for($i = 1; $i < count($single_subject_mark); $i++)
                    <td>-</td>
                @endfor
            @endif--}}
                <td>{{array_sum($single_subject_mark)}}</td>
            </tr>
        @endforeach

</table>
</body>
</html>
