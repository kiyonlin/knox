<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Knox</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script>
        window.Auth = {!! json_encode([
            'user' => user()?user()->only(['id', 'username', 'phone_number','email']):null,
            'signedIn' => auth()->check(),
            'modules' => user()?buildModuleTree(user()->modules()->toArray()):null,
            'permissions' => user()? user()->permissions():null,
        ]) !!};
    </script>
</head>
<body>
<div id="app">
    <Home v-if="signedIn"></Home>
    <welcome v-else></welcome>
</div>
</body>
<script src="{{ asset('js/app.js') }}"></script>
</html>
