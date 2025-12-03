<x-app-layout>
    {{-- Cabeçalho --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Receitas
        </h2>
    </x-slot>

    {{-- Conteúdo --}}
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-semibold tracking-tight text-slate-900">Receitas</h2>
                        <p class="text-sm text-slate-500">Acompanhe todas as entradas de dinheiro do seu fluxo.</p>
                    </div>

                    <a
                        href="{{ route('receitas.create') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                    >
                        <span class="text-base leading-none">＋</span>
                        Nova receita
                    </a>
                </div>

                <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-100 px-4 py-3 flex items-center justify-between">
                    <span class="text-sm font-medium text-emerald-900">Total de receitas</span>
                    <span class="text-lg font-semibold text-emerald-700">
                        R$ {{ number_format($total, 2, ',', '.') }}
                    </span>
                </div>

                @if ($incomes->isEmpty())
                    <p class="text-sm text-slate-500">
                        Ainda não há receitas cadastradas. Clique em <strong>“Nova receita”</strong> para adicionar a primeira.
                    </p>
                @else
                    <div class="overflow-hidden rounded-xl border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Data</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Descrição</th>
                                    <th class="px-4 py-2 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Valor (R$)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach ($incomes as $income)
                                    <tr class="hover:bg-slate-50/80">
                                        <td class="px-4 py-2 whitespace-nowrap text-slate-700">
                                            {{ $income->date->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-2 text-slate-800">
                                            {{ $income->description }}
                                        </td>
                                        <td class="px-4 py-2 text-right font-medium text-slate-900">
                                            R$ {{ number_format($income->amount, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
