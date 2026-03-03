<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) ? $title . ' — TGether' : 'TGether — Covoiturage & Mobilité Partagée' }}</title>
    <link rel="shortcut icon" href="{{ asset('logo.png') }}" type="image/x-icon">

    {{-- Nunito font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Vite assets (prod) --}}
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            font-family: 'Nunito', sans-serif;
        }
        html { scroll-behavior: smooth; }
        body { margin: 0; padding: 0; background: #F2F6FC; color: #0D1B2A; }

        :root {
            --blue:       #006EFF;
            --blue-dark:  #0057CC;
            --blue-light: #EBF4FF;
            --green:      #00C853;
            --dark:       #0D1B2A;
            --mid:        #4A6080;
            --muted:      #7090B8;
            --border:     #E2EBF6;
            --bg:         #F2F6FC;
        }
    </style>
</head>
