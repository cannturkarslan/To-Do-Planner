<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Assignment</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        @if(isset($assignmentResults) && !empty($assignmentResults['assignments']))
            <task-list :assignments="{{ json_encode($assignmentResults['assignments']) }}" :total-weeks="{{ $assignmentResults['weeks'] }}"></task-list>
        @else
            <p>No task assignments available at the moment.</p>
        @endif
    </div>
</body>
</html>
