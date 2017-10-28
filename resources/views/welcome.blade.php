<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Home</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.0/css/bulma.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <Home v-if="signedIn"></Home>
    <welcome v-else></welcome>
</div>
<script>
    window.Auth = {!! json_encode([
            'user' => auth()->user(),
            'signedIn' => auth()->check()
        ]) !!};
</script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
