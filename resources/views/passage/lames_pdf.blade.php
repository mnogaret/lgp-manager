@php
    use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html>

<head>
    <title>Groupes</title>
    <x-pdf-lames-resultats-style />
    <style>
        body {
            margin-top: 3cm;
        }
        .newpage {
            page-break-before: always;
        }
        .samepage {
            margin-top: 5mm;
        }
        .header,.footer {
            width: 100%;
            text-align: center;
            position: fixed;
            clear: both;
        }
        .header {
            top: 0px;
        }
        .footer {
            bottom: 0px;
        }
        .pagenum:before {
            content: counter(page);
        }
        .header .titre {
            text-transform: uppercase;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 40px;
            color: #ff4a4a;
            border-left: 1px solid gray;
            line-height: 1em;
            padding-left: 5mm;
            font-weight: bold;
        }
        .header .logo {
            padding-right: 5mm;
        }
        .header .logo img {
            width: 4cm;
        }
        .header table {
            background-color: white;
        }
    </style>
</head>

<body>
    <div class="header">
        <table>
            <tr>
                <td class="logo">
                    <div class="lgp">
                        <img src="{{ public_path('assets/img/lgp-pdf-logo.PNG') }}"/>
                    </div>
                </td>
                <td class="titre">Passage des lames Résultats</td>
            </tr>
        </table>
    </div>
    @php
    $newpage = false;
    @endphp
    <div class="body">
    @foreach ($impressions as $impression)
        <x-pdf-lames-resultats-table :impression="$impression" />
        @php
            $newpage = true;
        @endphp
    @endforeach
    </div>
</body>

</html>
