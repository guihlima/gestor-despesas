<nav x-data="{ open: false }" class="bg-slate-900 border-b border-slate-700 relative">

    <!-- Barra superior -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- Logo -->
            <div class="flex-shrink-0 text-white font-semibold">
                Meu Controle
            </div>

            <!-- Menu desktop -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">

                <a href="{{ route('resumo.index') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium
                   {{ request()->routeIs('resumo.*') ? 'bg-emerald-500 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                    Resumo
                </a>

                <a href="{{ route('receitas.index') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium
                   {{ request()->routeIs('receitas.*') ? 'bg-emerald-500 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                    Receitas
                </a>

                <a href="{{ route('despesas.index') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium
                   {{ request()->routeIs('despesas.*') || request()->routeIs('parcelas.*') ? 'bg-emerald-500 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                    Despesas
                </a>

                <a href="{{ route('bancos.index') }}"
                   class="px-3 py-2 rounded-lg text-sm font-medium
                   {{ request()->routeIs('bancos.*') ? 'bg-emerald-500 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                    Bancos
                </a>
            </div>

            <!-- Auth desktop -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <span class="text-xs text-slate-300 mr-3">
                        {{ auth()->user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="text-xs px-3 py-1.5 rounded-lg border border-slate-600 text-slate-200 hover:bg-slate-800">
                            Sair
                        </button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('login') }}"
                       class="text-xs px-3 py-1.5 rounded-lg border border-emerald-400 text-emerald-300 hover:bg-emerald-600 hover:text-white">
                        Entrar
                    </a>
                @endguest
            </div>

            <!-- Botão mobile (hambúrguer) -->
            <button @click="open = true"
                class="sm:hidden text-slate-200 hover:text-white p-2 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

        </div>
    </div>

    <!-- BACKDROP -->
    <div
        x-show="open"
        @click="open = false"
        x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 transition-opacity sm:hidden">
    </div>

    <!-- OFFCANVAS (menu lateral direito) -->
    <div
        x-show="open"
        x-transition:enter="transform transition ease-in-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition ease-in-out duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        x-cloak
        class="fixed right-0 top-0 h-full w-64 bg-slate-900 border-l border-slate-800 shadow-xl z-50 sm:hidden">

        <div class="px-4 py-4 flex justify-between items-center border-b border-slate-800">
            <span class="text-slate-200 font-semibold">Menu</span>

            <button @click="open = false" class="text-slate-200 hover:text-white">
                <svg class="h-6 w-6" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="px-4 py-4 space-y-2">

            <a href="{{ route('resumo.index') }}"
               class="block px-3 py-2 rounded text-sm font-medium
               {{ request()->routeIs('resumo.*') ? 'bg-emerald-500 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                Resumo
            </a>

            <a href="{{ route('receitas.index') }}"
               class="block px-3 py-2 rounded text-sm font-medium
               {{ request()->routeIs('receitas.*') ? 'bg-emerald-500 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                Receitas
            </a>

            <a href="{{ route('despesas.index') }}"
               class="block px-3 py-2 rounded text-sm font-medium
               {{ request()->routeIs('despesas.*') || request()->routeIs('parcelas.*') ? 'bg-emerald-500 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                Despesas
            </a>

            <a href="{{ route('bancos.index') }}"
               class="block px-3 py-2 rounded text-sm font-medium
               {{ request()->routeIs('bancos.*') ? 'bg-emerald-500 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                Bancos
            </a>

            <hr class="border-slate-700 my-3">

            @auth
                <div class="text-slate-300 text-sm mb-2">
                    {{ auth()->user()->name }}
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="w-full text-left block px-3 py-2 rounded text-sm text-red-400 bg-slate-800 border border-red-500 hover:bg-red-600 hover:text-white">
                        Sair
                    </button>
                </form>
            @endauth

            @guest
                <a href="{{ route('login') }}"
                   class="block px-3 py-2 rounded text-sm text-emerald-300 border border-emerald-400 hover:bg-emerald-600 hover:text-white">
                    Entrar
                </a>
            @endguest

        </div>

    </div>

</nav>
