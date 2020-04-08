<!DOCTYPE HTML>
<html lang="ko">
<head>
    <title>Content Manager</title>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="Content-Script-Type" content="text/javascript">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css"/>
    <link rel="stylesheet" type="text/css" href="/assets/content-manager/css/content-manager.css"/> 
    <script>
        let APP_URL = "{{ route('content-manager.index') }}"
    </script>
</head>
<body>
<div id="content-manager">
    <v-content-manager></v-content-manager>
</div>
<script src="/assets/content-manager/js/content-manager.js"></script>
<script type="text/javascript">
    app.$store.commit('setGroups', {!! json_encode($groups) !!})
</script>
</body>
</html>
