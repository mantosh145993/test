<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Test Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        .question-text table {
            width: 50%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .question-text table th,
        .question-text table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .question-text table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .question-text table td {
            background-color: #fafafa;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 200px; /* Adjust width as needed */
            height: auto;
        }
    </style>
</head>

<body>

    <?php
$logoPath = public_path('logo/logo.png');
$type = pathinfo($logoPath, PATHINFO_EXTENSION);
$data = file_get_contents($logoPath);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>
    <div class="logo-container">
    <img src="{{ $base64 }}" class="logo" alt="Logo">

    </div>
    <div class="row">
        <div class="col text-center">
            <h4>Test Results ({{ $test->quiz->title }})</h4>
        </div>
        <div class="col text-end">
            <h3>BODMAS Education Services Pvt Ltd</h3>
            <p></p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Question</th>
                <th>Your Answer</th>
                <th>Correct Answer</th>
                <th>Marks</th>
            </tr>
        </thead>
        <tbody>
            @php $index = 1; @endphp
            @foreach($results as $result)
            <tr>
                <td>{{ $index++ }}</td>
                <td class="question-text">{!! $result->question->text !!}</td>
                <td>{!! $result->option->text ?? 'Not Answered' !!}</td>
                <td>
                    {!! $result->question->options->firstWhere('correct', 1)->text ?? 'No Answer' !!}
                </td>
                <td>{!! $result->question->marks !!}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
