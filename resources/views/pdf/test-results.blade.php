<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Test Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 50%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
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
    </style>
</head>

<body>
    <div class="row">
        <div class="col text-center">
            <h3>BODMAS Education Services Pvt Ltd</h3>
            <p>Address: Z -169 Ground Floor, Sector 12, Noida, Uttar Pradesh 201301 </br>
             Hours: Open 9 am ⋅ Closes 8 pm </br>
             Phone: 09511626721</p>
        </div>
        <div class="col text-center">
            <h4 >Answer Key of ({{ $test->quiz->title }} ) </h4>
            <p>{{ $currentDateTime }}</p> 
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