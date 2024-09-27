@php
    use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html>

<head>
    <title>Badges</title>
    <x-pdf-badge-style />
</head>

<body>
    <div class="body">
        @foreach ($adherents as $adherent)
            <x-pdf-badge :adherent="$adherent" />
        @endforeach
    </div>
</body>

</html>
