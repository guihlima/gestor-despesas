<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Resumo Mensal
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- FILTRO DE MÊS --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-semibold tracking-tight text-slate-900">Resumo do mês</h2>
                    <p class="text-sm text-slate-500">
                        Período: {{ $start->format('d/m/Y') }} a {{ $end->format('d/m/Y') }}
                    </p>
                </div>

                <form method="GET" action="{{ route('resumo.index') }}" class="flex items-center gap-2">
                    <label for="mes" class="text-xs font-medium text-slate-600">
                        Mês de referência
                    </label>
                    <input
                        type="month"
                        id="mes"
                        name="mes"
                        value="{{ $month }}"
                        class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                    >
                    <button
                        type="submit"
                        class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-slate-800"
                    >
                        Filtrar
                    </button>
                </form>
            </div>


            {{-- CARDS PRINCIPAIS --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

                <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3">
                    <p class="text-xs font-medium text-emerald-700 uppercase tracking-wide">Entradas no mês</p>
                    <p class="mt-1 text-2xl font-semibold text-emerald-900">
                        R$ {{ number_format($incomesTotal, 2, ',', '.') }}
                    </p>
                </div>

                <div class="rounded-2xl border border-rose-100 bg-rose-50 px-4 py-3">
                    <p class="text-xs font-medium text-rose-700 uppercase tracking-wide">Saídas pagas no mês</p>
                    <p class="mt-1 text-2xl font-semibold text-rose-900">
                        R$ {{ number_format($cashOutTotal, 2, ',', '.') }}
                    </p>
                </div>

                <div class="rounded-2xl border border-amber-100 bg-amber-50 px-4 py-3">
                    <p class="text-xs font-medium text-amber-700 uppercase tracking-wide">Parcelas em aberto (vencendo no mês)</p>
                    <p class="mt-1 text-2xl font-semibold text-amber-900">
                        R$ {{ number_format($openInstallmentsDue, 2, ',', '.') }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3">
                    <p class="text-xs font-medium text-slate-600 uppercase tracking-wide">Saldo do mês</p>
                    <p class="mt-1 text-2xl font-semibold {{ $balance >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                        R$ {{ number_format($balance, 2, ',', '.') }}
                    </p>
                </div>
            </div>


            {{-- LISTAS: ÚLTIMAS ENTRADAS E SAÍDAS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Últimas Receitas --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                    <h3 class="text-sm font-semibold text-slate-800 mb-3">Últimas receitas do mês</h3>

                    @if ($latestIncomes->isEmpty())
                        <p class="text-xs text-slate-500">Nenhuma receita lançada neste mês.</p>
                    @else
                        <ul class="divide-y divide-slate-100 text-sm">
                            @foreach ($latestIncomes as $income)
                                <li class="flex items-center justify-between py-2">
                                    <div>
                                        <p class="font-medium text-slate-800">{{ $income->description }}</p>
                                        <p class="text-xs text-slate-500">{{ $income->date->format('d/m/Y') }}</p>
                                    </div>
                                    <span class="font-semibold text-emerald-700">
                                        R$ {{ number_format($income->amount, 2, ',', '.') }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>


                {{-- Últimas Despesas --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                    <h3 class="text-sm font-semibold text-slate-800 mb-3">Últimas despesas do mês</h3>

                    @if ($latestExpenses->isEmpty())
                        <p class="text-xs text-slate-500">Nenhuma despesa lançada neste mês.</p>
                    @else
                        <ul class="divide-y divide-slate-100 text-sm">
                            @foreach ($latestExpenses as $expense)
                                <li class="py-2">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium text-slate-800">
                                                {{ $expense->description }}
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                {{ $expense->date->format('d/m/Y') }}
                                                @if ($expense->bank)
                                                    • {{ $expense->bank->name }}
                                                @endif
                                                • {{ $expense->is_installment ? 'Parcelada' : 'À vista' }}
                                            </p>
                                        </div>
                                        <span class="font-semibold text-rose-700">
                                            R$ {{ number_format($expense->total_amount, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
