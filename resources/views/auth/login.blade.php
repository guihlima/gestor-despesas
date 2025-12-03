<x-guest-layout>
    <h1 class="text-xl font-semibold text-slate-900 mb-1">
        Entrar
    </h1>
    <p class="text-sm text-slate-500 mb-6">
        Acesse sua conta para ver seu resumo financeiro.
    </p>

    {{-- Status (ex: senha redefinida) --}}
    @if (session('status'))
        <div class="mb-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        {{-- E-mail --}}
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                E-mail
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                class="block w-full rounded-lg border @error('email') border-red-500 @else border-slate-300 @enderror px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
            >
            @error('email')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Senha --}}
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">
                Senha
            </label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                class="block w-full rounded-lg border @error('password') border-red-500 @else border-slate-300 @enderror px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
            >
            @error('password')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Lembrar-me + Esqueci senha --}}
        <div class="flex items-center justify-between">
            <label class="inline-flex items-center gap-2 text-xs text-slate-600">
                <input
                    type="checkbox"
                    name="remember"
                    class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <span>Lembrar-me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-xs font-medium text-emerald-700 hover:text-emerald-800">
                    Esqueci minha senha
                </a>
            @endif
        </div>

        {{-- Botão --}}
        <div class="pt-2">
            <button
                type="submit"
                class="w-full inline-flex justify-center items-center rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                Entrar
            </button>
        </div>
    </form>

    <div class="mt-6 text-center text-xs text-slate-500">
        Não tem conta?
        <a href="{{ route('register') }}" class="font-semibold text-emerald-700 hover:text-emerald-800">
            Cadastre-se
        </a>
    </div>
</x-guest-layout>
