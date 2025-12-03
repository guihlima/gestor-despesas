<x-guest-layout>
    <h1 class="text-xl font-semibold text-slate-900 mb-1">
        Criar conta
    </h1>
    <p class="text-sm text-slate-500 mb-6">
        Monte seu controle financeiro em poucos segundos.
    </p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Nome --}}
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">
                Nome
            </label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                class="block w-full rounded-lg border @error('name') border-red-500 @else border-slate-300 @enderror px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
            >
            @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

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
                autocomplete="new-password"
                class="block w-full rounded-lg border @error('password') border-red-500 @else border-slate-300 @enderror px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
            >
            @error('password')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirmar senha --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">
                Confirmar senha
            </label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
            >
        </div>

        {{-- Botão --}}
        <div class="pt-2">
            <button
                type="submit"
                class="w-full inline-flex justify-center items-center rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                Criar conta
            </button>
        </div>
    </form>

    <div class="mt-6 text-center text-xs text-slate-500">
        Já tem conta?
        <a href="{{ route('login') }}" class="font-semibold text-emerald-700 hover:text-emerald-800">
            Entrar
        </a>
    </div>
</x-guest-layout>
