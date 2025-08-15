<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup Now</title>
</head>
<body>
    <h1>Download Backup</h1>
    <form action="{{ url('/backup') }}" method="get">
        <button type="submit">Download Backup Now</button>
    </form>
</body>
</html>
