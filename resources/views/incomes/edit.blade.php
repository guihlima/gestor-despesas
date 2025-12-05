<x-app-layout>
    {{-- Cabeçalho --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Receita
        </h2>
    </x-slot>

    {{-- Conteúdo --}}
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-semibold tracking-tight text-slate-900">Editar receita</h2>
                        <p class="text-sm text-slate-500">Atualize os dados da entrada de dinheiro.</p>
                    </div>

                    <a
                        href="{{ route('receitas.index') }}"
                        class="text-sm text-slate-500 hover:text-slate-700">
                        Voltar para lista
                    </a>
                </div>

                {{-- Erros de validação --}}
                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-50 border border-red-100 px-4 py-3 text-sm text-red-800">
                        <ul class="list-disc ml-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('receitas.update', $income) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-1">
                                Descrição
                            </label>
                            <input
                                type="text"
                                id="description"
                                name="description"
                                value="{{ old('description', $income->description) }}"
                                required
                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-medium text-slate-700 mb-1">
                                Valor (R$)
                            </label>
                            <input
                                type="text"
                                id="amount"
                                name="amount"
                                value="{{ old('amount', number_format($income->amount, 2, ',', '.')) }}"
                                required
                                data-currency="brl"
                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label for="date" class="block text-sm font-medium text-slate-700 mb-1">
                                Data
                            </label>
                            <input
                                type="date"
                                id="date"
                                name="date"
                                value="{{ old('date', $income->date->format('Y-m-d')) }}"
                                required
                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                    </div>

                    <div class="pt-2 flex items-center gap-3">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Atualizar receita
                        </button>

                        <a
                            href="{{ route('receitas.index') }}"
                            class="text-sm text-slate-500 hover:text-slate-700">
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
