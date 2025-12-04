<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalhes da despesa parcelada
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">

                {{-- Cabeçalho da despesa --}}
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">
                            Descrição
                        </p>
                        <h3 class="text-lg font-semibold text-slate-900">
                            {{ $expense->description }}
                        </h3>

                        <p class="mt-2 text-sm text-slate-600">
                            Banco / cartão:
                            <span class="font-medium">
                                {{ $expense->bank?->name ?? 'Sem banco' }}
                            </span>
                        </p>

                        <p class="text-sm text-slate-600">
                            Valor total:
                            <span class="font-semibold">
                                R$ {{ number_format($expense->total_amount, 2, ',', '.') }}
                            </span>
                        </p>
                    </div>

                    <div class="text-right space-y-2">

                        {{-- Botão excluir despesa (todas as parcelas) --}}
                        <form
                            action="{{ route('despesas.destroy', $expense) }}"
                            method="POST"
                            onsubmit="return confirm('ATENÇÃO: Isso vai excluir a despesa inteira e TODAS as parcelas. Tem certeza que deseja continuar?');">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-xs font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                                Excluir despesa (todas as parcelas)
                            </button>
                        </form>

                        <a href="{{ route('despesas.index') }}"
                           class="inline-flex items-center text-xs text-slate-500 hover:text-slate-700">
                            ← Voltar para despesas
                        </a>
                    </div>
                </div>

                {{-- Tabela de parcelas --}}
                <div class="overflow-hidden rounded-xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold">Vencimento</th>
                                <th class="px-4 py-2 text-center font-semibold">Parcela</th>
                                <th class="px-4 py-2 text-right font-semibold">Valor (R$)</th>
                                <th class="px-4 py-2 text-center font-semibold">Status</th>
                                <th class="px-4 py-2 text-center font-semibold">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($installments as $installment)
                                <tr class="hover:bg-slate-50/80">
                                    <td class="px-4 py-2 text-slate-700">
                                        {{ \Carbon\Carbon::parse($installment->due_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-2 text-center text-slate-700">
                                        {{ $installment->installment_number }} / {{ $installment->total_installments }}
                                    </td>
                                    <td class="px-4 py-2 text-right font-medium text-slate-900">
                                        R$ {{ number_format($installment->amount, 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 text-center text-xs">
                                        @if ($installment->is_paid)
                                            <span class="inline-flex rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-700 border border-emerald-100">
                                                Paga em {{ optional($installment->paid_at)->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-amber-50 px-2 py-0.5 text-[11px] font-medium text-amber-700 border border-amber-100">
                                                Em aberto
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        @if (! $installment->is_paid)
                                            <form
                                                action="{{ route('parcelas.pay', $installment) }}"
                                                method="POST"
                                                onsubmit="return confirm('Confirmar pagamento desta parcela?');"
                                                class="inline">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center rounded-lg bg-emerald-600 px-3 py-1 text-xs font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1">
                                                    Marcar como paga
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
