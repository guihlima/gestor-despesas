{{-- resources/views/banks/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Cadastrar Banco
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-semibold tracking-tight text-slate-900">
                            Cadastrar banco / cartão
                        </h2>
                        <p class="text-sm text-slate-500">
                            Exemplos: Nubank, Itaú, Santander, Inter, Caixa...
                        </p>
                    </div>

                    <a
                        href="{{ route('bancos.index') }}"
                        class="text-sm text-slate-500 hover:text-slate-700"
                    >
                        Voltar para lista
                    </a>
                </div>

                <form action="{{ route('bancos.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">
                            Nome do banco / cartão
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                            placeholder="Ex: Nubank, Itaú, Santander..."
                        >
                    </div>

                    <div class="pt-2 flex items-center gap-3">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                        >
                            Salvar banco
                        </button>

                        <a
                            href="{{ route('bancos.index') }}"
                            class="text-sm text-slate-500 hover:text-slate-700"
                        >
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
