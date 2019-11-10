<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Teste</title>
</head>
<body>
  <h1>Test view</h1>
  <p><?= htmlspecialchars($name, ENT_QUOTES); ?></p>
  <p>{{ $name }}</p>
  <p>{{!! $name !!}}</p>
</body>
</html>