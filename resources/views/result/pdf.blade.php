<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Student Result</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #000;
            margin: 20px;
            background: #fff;
        }
        .header {
            background: #5e72e4;
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 22px;
        }
        .label {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 16px;
        }
        .value-box {
            border: 1px solid #5e72e4;
            padding: 8px 10px;
            margin-bottom: 10px;
            width: 100%;
            box-sizing: border-box;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            margin-bottom: 22px;
        }
        th, td {
            border: 1px solid #5e72e4;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #dde1f7;
            font-weight: bold;
            color: #000;
        }
        .summary-table, .identity-table {
            margin-bottom: 28px;
        }
        .identity-table th, .identity-table td {
            text-align: left;
            font-weight: normal;
        }
    </style>
</head>
<body>

    <div class="header">Student Result Overview</div>

    <!-- Class, Student, Exam Date Table -->
    <div class="section">
        <table class="identity-table">
            <tr>
                <th>Class:</th>
                <th>Student:</th>
                <th>Exam Date:</th>
            </tr>
            <tr>
                <td>{{ $result->class->name }}</td>
                <td>{{ $result->student->user->name }}</td>
                <td>{{ \Illuminate\Support\Carbon::parse($result->exam_date)->format('d M, Y') }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h4>Subject Marks</h4>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Subject</th>
                    <th>Total Marks</th>
                    <th>Obtained Marks</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($result->subjectMarks as $subjectMark)
                    <tr>
                        <td>{{ $subjectMark->subject->id }}</td>
                        <td>{{ $subjectMark->subject->name }}</td>
                        <td>{{ $subjectMark->total_mark }}</td>
                        <td>{{ $subjectMark->obtained_mark }}</td>
                        <td>{{ $subjectMark->grade }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <table class="summary-table">
            <tr>
                <th>Total Marks:</th>
                <th>Obtained Marks:</th>
                <th>Overall Grade:</th>
                <th>Exam Type:</th>
            </tr>
            <tr>
                <td>{{ $result->total_mark }}</td>
                <td>{{ $result->obtained_mark }}</td>
                <td>{{ $result->grade }}</td>
                <td>{{ ucfirst($result->exam_type) }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="label">Result Status:</div>
        <div class="value-box">{{ ucfirst($result->result_status) }}</div>
    </div>

</body>
</html>
