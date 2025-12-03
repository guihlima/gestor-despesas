<x-app-layout>
    {{-- Cabeçalho --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Parcelas — {{ $expense->description }}
        </h2>
    </x-slot>

    {{-- Conteúdo --}}
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-semibold tracking-tight text-slate-900">
                            Parcelas — {{ $expense->description }}
                        </h2>
                        <p class="text-sm text-slate-500">
                            Valor total: R$ {{ number_format($expense->total_amount, 2, ',', '.') }}
                        </p>
                    </div>

                    <a
                        href="{{ route('despesas.index') }}"
                        class="text-sm text-slate-500 hover:text-slate-700"
                    >
                        Voltar para despesas
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="rounded-xl border border-slate-200 bg-white px-4 py-3">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total de parcelas</p>
                        <p class="mt-1 text-lg font-semibold text-slate-900">
                            {{ $installments->count() }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3">
                        <p class="text-xs font-medium text-emerald-700 uppercase tracking-wide">Pagas</p>
                        <p class="mt-1 text-lg font-semibold text-emerald-800">
                            {{ $paidCount }} (R$ {{ number_format($paidAmount, 2, ',', '.') }})
                        </p>
                    </div>

                    <div class="rounded-xl border border-amber-100 bg-amber-50 px-4 py-3">
                        <p class="text-xs font-medium text-amber-700 uppercase tracking-wide">Em aberto</p>
                        <p class="mt-1 text-lg font-semibold text-amber-800">
                            {{ $openCount }} (R$ {{ number_format($openAmount, 2, ',', '.') }})
                        </p>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <p class="text-xs font-medium text-slate-600 uppercase tracking-wide">Valor total</p>
                        <p class="mt-1 text-lg font-semibold text-slate-900">
                            R$ {{ number_format($totalAmount, 2, ',', '.') }}
                        </p>
                    </div>
                </div>

                @if ($installments->isEmpty())
                    <p class="text-sm text-slate-500">Nenhuma parcela cadastrada para esta despesa.</p>
                @else
                    <div class="overflow-hidden rounded-xl border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Nº</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Vencimento</th>
                                    <th class="px-4 py-2 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Valor (R$)</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach ($installments as $installment)
                                    <tr class="hover:bg-slate-50/80">
                                        <td class="px-4 py-2 text-slate-700">
                                            {{ $installment->installment_number }} / {{ $installment->total_installments }}
                                        </td>
                                        <td class="px-4 py-2 text-slate-700">
                                            {{ $installment->due_date->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-2 text-right font-medium text-slate-900">
                                            R$ {{ number_format($installment->amount, 2, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-2">
                                            @if ($installment->is_paid)
                                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-700 border border-emerald-100">
                                                    Paga {{ $installment->paid_at ? 'em '.$installment->paid_at->format('d/m/Y') : '' }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-[11px] font-medium text-amber-700 border border-amber-100">
                                                    Em aberto
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            @if (! $installment->is_paid)
                                                <form action="{{ route('parcelas.pay', $installment) }}" method="POST">
                                                    @csrf
                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center rounded-lg bg-emerald-600 px-3 py-1 text-xs font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1"
                                                    >
                                                        Marcar como paga
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-slate-400">—</span>
                                            @endif
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
