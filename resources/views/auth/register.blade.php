<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription — TGether</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <script>tailwind.config={theme:{extend:{colors:{primary:{DEFAULT:'#0374BA',600:'#025d95'},secondary:{DEFAULT:'#64AC5A',600:'#508948'}}}}}</script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>* { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="min-h-screen flex">

    {{-- Left: Image --}}
    <div class="hidden lg:block lg:w-1/2 relative overflow-hidden">
        <img src="{{ asset('images/register.jpg') }}" alt="TGether" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-br from-secondary/80 to-primary/60"></div>
        <div class="relative h-full flex flex-col justify-between p-12">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white font-black text-xl">T</div>
                <span class="text-2xl font-black text-white">TGether</span>
            </a>
            <div>
                <h2 class="text-4xl font-black text-white leading-tight mb-4">Rejoignez<br>la communauté! 🚗</h2>
                <p class="text-white/80 text-lg">Créez votre compte gratuitement et commencez à partager vos trajets dès aujourd'hui.</p>
                <div class="mt-8 space-y-3">
                    @foreach(['✅ Inscription gratuite en 2 minutes','🔒 Profils vérifiés et sécurisés','💰 Économisez sur vos trajets','🌿 Réduisez votre empreinte carbone'] as $item)
                    <div class="flex items-center gap-3 text-white/90 text-sm">
                        <span>{{ $item }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50 overflow-y-auto">
        <div class="w-full max-w-md py-8">
            <div class="lg:hidden flex items-center gap-2 mb-8">
                <div class="w-9 h-9 bg-primary rounded-xl flex items-center justify-center text-white font-black">T</div>
                <span class="text-xl font-black text-gray-900">TGether</span>
            </div>

            <h1 class="text-3xl font-black text-gray-900 mb-2">Créer un compte</h1>
            <p class="text-gray-500 text-sm mb-8">Rejoignez TGether gratuitement.</p>

            @if($errors->any())
            <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                @foreach($errors->all() as $error)<p>• {{ $error }}</p>@endforeach
            </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Role selector --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Je suis</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="passenger" class="sr-only peer" {{ old('role','passenger') === 'passenger' ? 'checked' : '' }}>
                            <div class="peer-checked:border-secondary peer-checked:bg-secondary-50 border-2 border-gray-200 rounded-xl p-4 text-center hover:border-secondary transition">
                                <div class="text-2xl mb-1">👤</div>
                                <p class="text-sm font-bold text-gray-800">Passager</p>
                                <p class="text-xs text-gray-500">Je cherche un trajet</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="driver" class="sr-only peer" {{ old('role') === 'driver' ? 'checked' : '' }}>
                            <div class="peer-checked:border-primary peer-checked:bg-primary-50 border-2 border-gray-200 rounded-xl p-4 text-center hover:border-primary transition">
                                <div class="text-2xl mb-1">🚗</div>
                                <p class="text-sm font-bold text-gray-800">Conducteur</p>
                                <p class="text-xs text-gray-500">Je propose un trajet</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Prénom *</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary bg-white"
                            placeholder="Jean">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nom *</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary bg-white"
                            placeholder="Dupont">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary bg-white"
                        placeholder="vous@exemple.com">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Date de naissance</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Genre</label>
                        <select name="gender" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary bg-white">
                            <option value="">—</option>
                            <option value="male" {{ old('gender')=='male'?'selected':'' }}>Homme</option>
                            <option value="female" {{ old('gender')=='female'?'selected':'' }}>Femme</option>
                            <option value="other" {{ old('gender')=='other'?'selected':'' }}>Autre</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Mot de passe *</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary bg-white"
                        placeholder="Min. 8 caractères">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Confirmer le mot de passe *</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary bg-white"
                        placeholder="Répétez votre mot de passe">
                </div>

                <button type="submit"
                    class="w-full bg-primary text-white py-3.5 rounded-xl font-bold text-sm hover:bg-primary-600 transition shadow-lg shadow-primary/25 mt-2">
                    Créer mon compte gratuitement →
                </button>
            </form>

            <div class="mt-6 text-center">
                <span class="text-sm text-gray-500">Déjà inscrit? </span>
                <a href="{{ route('login') }}" class="text-primary font-semibold text-sm hover:underline">Se connecter</a>
            </div>
            <div class="mt-2 text-center">
                <a href="{{ route('home') }}" class="text-xs text-gray-400 hover:text-gray-600">← Retour à l'accueil</a>
            </div>
        </div>
    </div>
</body>
</html>
