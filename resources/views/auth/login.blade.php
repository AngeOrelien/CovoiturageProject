<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — TGether</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <script>tailwind.config={theme:{extend:{colors:{primary:{DEFAULT:'#0374BA',50:'#e6f2fa',100:'#b3d8f0',600:'#025d95',700:'#024670'},secondary:{DEFAULT:'#64AC5A',600:'#508948'}}}}}</script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>* { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="min-h-screen flex">

    {{-- Left: Image --}}
    <div class="hidden lg:block lg:w-1/2 relative overflow-hidden">
        <img src="{{ asset('images/login.jpg') }}" alt="TGether" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-br from-primary/80 to-primary/40"></div>
        <div class="relative h-full flex flex-col justify-between p-12">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white font-black text-xl">T</div>
                <span class="text-2xl font-black text-white">TGether</span>
            </a>
            <div>
                <h2 class="text-4xl font-black text-white leading-tight mb-4">Content de<br>vous revoir! 👋</h2>
                <p class="text-white/80 text-lg">Connectez-vous pour accéder à vos trajets, réservations et bien plus encore.</p>
            </div>
        </div>
    </div>

    {{-- Right: Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
        <div class="w-full max-w-md">

            {{-- Mobile logo --}}
            <div class="lg:hidden flex items-center gap-2 mb-8">
                <div class="w-9 h-9 bg-primary rounded-xl flex items-center justify-center text-white font-black">T</div>
                <span class="text-xl font-black text-gray-900">TGether</span>
            </div>

            <h1 class="text-3xl font-black text-gray-900 mb-2">Connexion</h1>
            <p class="text-gray-500 text-sm mb-8">Entrez vos identifiants pour accéder à votre espace.</p>

            @if($errors->any())
            <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                @foreach($errors->all() as $error)<p>• {{ $error }}</p>@endforeach
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Adresse email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary bg-white transition"
                        placeholder="vous@exemple.com">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary bg-white transition"
                        placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="text-sm text-gray-600">Se souvenir de moi</span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full bg-primary text-white py-3.5 rounded-xl font-bold text-sm hover:bg-primary-600 transition shadow-lg shadow-primary/25 hover:shadow-primary/40">
                    Se connecter →
                </button>
            </form>

            <div class="mt-6 text-center">
                <span class="text-sm text-gray-500">Pas encore de compte? </span>
                <a href="{{ route('register') }}" class="text-primary font-semibold text-sm hover:underline">Créer un compte</a>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('home') }}" class="text-xs text-gray-400 hover:text-gray-600 transition">← Retour à l'accueil</a>
            </div>
        </div>
    </div>
</body>
</html>
