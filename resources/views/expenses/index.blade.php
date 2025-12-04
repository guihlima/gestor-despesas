<x-app-layout>
    {{-- Cabeçalho da página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Despesas
        </h2>
    </x-slot>

    @if (session('success'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 3000)"
        class="mx-6 mt-4 rounded-lg bg-emerald-500 text-white px-4 py-3 shadow transition-all">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 3000)"
        class="mx-16 mt-4 rounded-lg bg-red-600 text-white px-4 py-3 shadow transition-all">
        {{ session('error') }}
    </div>
    @endif


    {{-- Conteúdo --}}
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-semibold tracking-tight text-slate-900">Despesas</h2>
                        <p class="text-sm text-slate-500">Controle de saídas, incluindo despesas à vista e parceladas.</p>
                    </div>

                    <a
                        href="{{ route('despesas.create') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        <span class="text-base leading-none">＋</span>
                        Nova despesa
                    </a>
                </div>

                {{-- Cards de resumo --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="rounded-xl border border-slate-200 bg-white px-4 py-3">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total de despesas</p>
                        <p class="mt-1 text-lg font-semibold text-slate-900">
                            R$ {{ number_format($totalExpenses, 2, ',', '.') }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3">
                        <p class="text-xs font-medium text-emerald-700 uppercase tracking-wide">Parcelas pagas</p>
                        <p class="mt-1 text-lg font-semibold text-emerald-800">
                            R$ {{ number_format($paidInstallmentsAmount, 2, ',', '.') }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-amber-100 bg-amber-50 px-4 py-3">
                        <p class="text-xs font-medium text-amber-700 uppercase tracking-wide">Parcelas em aberto</p>
                        <p class="mt-1 text-lg font-semibold text-amber-800">
                            R$ {{ number_format($openInstallmentsAmount, 2, ',', '.') }}
                        </p>
                    </div>
                </div>

                {{-- Resumo por banco / cartão --}}
                @if(isset($totalsByBank) && $totalsByBank->isNotEmpty())
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-slate-700 mb-2">
                        Resumo por banco / cartão
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @foreach ($totalsByBank as $bankName => $total)
                        <div class="rounded-xl border border-slate-200 bg-white px-4 py-3">
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">
                                {{ $bankName }}
                            </p>
                            <p class="mt-1 text-base font-semibold text-slate-900">
                                R$ {{ number_format($total, 2, ',', '.') }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif


                @if ($expenses->isEmpty())
                <p class="text-sm text-slate-500">
                    Ainda não há despesas cadastradas. Clique em <strong>“Nova despesa”</strong> para adicionar a primeira.
                </p>
                @else
                <div class="overflow-hidden rounded-xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Data</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Banco</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Descrição</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Tipo</th>
                                <th class="px-4 py-2 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Valor total (R$)</th>
                                <th class="px-4 py-2 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">Parcelas</th>
                                <th class="px-4 py-2 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($expenses as $expense)
                            @php
                            $totalInstallments = $expense->installments->count();
                            $paidCount = $expense->installments->where('is_paid', true)->count();
                            @endphp
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-2 whitespace-nowrap text-slate-700">
                                    {{ $expense->date->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-2 text-slate-700">
                                    {{ $expense->bank?->name ?? '—' }}
                                </td>

                                <td class="px-4 py-2 text-slate-800">
                                    {{ $expense->description }}
                                </td>
                                <td class="px-4 py-2 text-slate-700">
                                    @if ($expense->is_installment)
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-700 border border-emerald-100">
                                        Parcelada
                                    </span>
                                    @else
                                    <span class="inline-flex items-center rounded-full bg-slate-50 px-2 py-0.5 text-[11px] font-medium text-slate-700 border border-slate-100">
                                        À vista
                                    </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-right font-medium text-slate-900">
                                    R$ {{ number_format($expense->total_amount, 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-2 text-center text-xs text-slate-700">
                                    @if ($expense->is_installment && $totalInstallments > 0)
                                    {{ $paidCount }} / {{ $totalInstallments }} pagas
                                    @else
                                    —
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex items-center justify-center gap-2">

                                        @if ($expense->is_installment)
                                        <a
                                            href="{{ route('parcelas.index', $expense) }}"
                                            class="text-xs font-medium text-emerald-700 hover:text-emerald-900">
                                            Ver parcelas
                                        </a>
                                        @else
                                        @if ($expense->is_paid ?? false)
                                        <span class="text-xs font-medium text-emerald-700">Paga</span>
                                        @else
                                        <a
                                            href="{{ route('despesas.show', $expense) }}"
                                            class="text-xs font-medium text-blue-600 hover:text-blue-800">
                                            Pagar
                                        </a>
                                        @endif
                                        @endif


                                        {{-- Botão excluir --}}
                                        <form
                                            action="{{ route('despesas.destroy', $expense) }}"
                                            method="POST"
                                            onsubmit="return confirm('Tem certeza que deseja excluir esta despesa?');"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-flex items-center rounded-lg bg-red-600 px-3 py-1 text-xs font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                                                Excluir
                                            </button>
                                        </form>
                                    </div>
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