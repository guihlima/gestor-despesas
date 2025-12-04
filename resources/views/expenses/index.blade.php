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
        class="mx-16 mt-4 rounded-lg bg-emerald-500 text-white px-4 py-3 shadow transition-all">
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
                        <p class="text-sm text-slate-500">
                            Visão do que você está devendo no mês (à vista + parcelas em aberto).
                        </p>
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
                    {{-- Total de despesas do mês (à vista + parcelas em aberto) --}}
                    <div class="rounded-xl border border-slate-200 bg-white px-4 py-3">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">
                            Total do mês (a pagar)
                        </p>
                        <p class="mt-1 text-lg font-semibold text-slate-900">
                            R$
                            {{ number_format($totalMonth, 2, ',', '.') }}
                        </p>
                    </div>

                    {{-- Total de despesas à vista no mês --}}
                    <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3">
                        <p class="text-xs font-medium text-emerald-700 uppercase tracking-wide">
                            À vista no mês
                        </p>
                        <p class="mt-1 text-lg font-semibold text-emerald-800">
                            R$
                            {{ number_format($cashExpenses->sum('total_amount'), 2, ',', '.') }}
                        </p>
                    </div>

                    {{-- Total de parcelas em aberto no mês --}}
                    <div class="rounded-xl border border-amber-100 bg-amber-50 px-4 py-3">
                        <p class="text-xs font-medium text-amber-700 uppercase tracking-wide">
                            Parcelas em aberto no mês
                        </p>
                        <p class="mt-1 text-lg font-semibold text-amber-800">
                            R$
                            {{ number_format($installments->sum('amount'), 2, ',', '.') }}
                        </p>
                    </div>
                </div>

                {{-- Filtro de mês/ano --}}
                <form method="GET"
                    action="{{ route('despesas.index') }}"
                    class="mb-4 rounded-xl bg-white p-4 shadow flex flex-wrap gap-3 items-end">

                    {{-- Mês --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Mês</label>
                        <select name="month"
                            class="rounded-lg border-slate-300 text-sm">
                            @for ($m = 1; $m <= 12; $m++)
                                @php
                                $date=\Carbon\Carbon::createFromDate($year, $m, 1);
                                @endphp
                                <option value="{{ $m }}" @selected($m==$month)>
                                {{ $date->translatedFormat('F') }}
                                </option>
                                @endfor
                        </select>
                    </div>

                    {{-- Ano --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Ano</label>
                        <select name="year"
                            class="rounded-lg border-slate-300 text-sm">
                            @for ($y = now()->year - 2; $y <= now()->year + 1; $y++)
                                <option value="{{ $y }}" @selected($y==$year)>{{ $y }}</option>
                                @endfor
                        </select>
                    </div>

                    {{-- Botões --}}
                    <div class="flex gap-2">
                        <button type="submit"
                            class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-emerald-700">
                            Filtrar
                        </button>

                        <a href="{{ route('despesas.index') }}"
                            class="inline-flex items-center rounded-lg border border-slate-300 px-3 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50">
                            Limpar
                        </a>
                    </div>
                </form>

                {{-- Resumo por banco / cartão --}}
                @if (!empty($totalsByBank))
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-slate-700 mb-2">
                        Resumo por banco / cartão (mês selecionado)
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

                {{-- Se não houver nada no mês --}}
                @if ($cashExpenses->isEmpty() && $installments->isEmpty())
                <p class="text-sm text-slate-500">
                    Não há despesas à vista nem parcelas em aberto para o mês selecionado.
                </p>
                @else
                {{-- Tabela: Despesas à vista do mês --}}
                @if ($cashExpenses->isNotEmpty())
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-slate-700 mb-2">
                        Despesas à vista no mês
                    </h3>

                    <div class="overflow-hidden rounded-xl border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-xs text-slate-500 uppercase sticky top-0">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Data
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Banco
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Descrição
                                    </th>
                                    <th class="px-4 py-2 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Valor (R$)
                                    </th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach ($cashExpenses as $expense)
                                <tr class="hover:bg-slate-50/80">
                                    <td class="px-4 py-2 whitespace-nowrap text-slate-700">
                                        {{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-2 text-slate-700">
                                        {{ $expense->bank?->name ?? '—' }}
                                    </td>
                                    <td class="px-4 py-2 text-slate-800">
                                        {{ $expense->description }}
                                    </td>
                                    <td class="px-4 py-2 text-right font-medium text-slate-900">
                                        R$ {{ number_format($expense->total_amount, 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            @if ($expense->is_paid ?? false)
                                            <span class="text-xs font-medium text-emerald-700">
                                                Paga
                                            </span>
                                            @else
                                            <a
                                                href="{{ route('despesas.show', $expense) }}"
                                                class="text-xs font-medium text-blue-600 hover:text-blue-800">
                                                Pagar
                                            </a>
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
                </div>
                @endif

                {{-- Tabela: Parcelas em aberto no mês --}}
                {{-- Parcelas em aberto no mês (com dropdown por banco) --}}
                @if ($installments->isNotEmpty())
                <div>
                    <h3 class="text-sm font-medium text-slate-700 mb-4">
                        Parcelas em aberto no mês (agrupadas por banco)
                    </h3>

                    @foreach ($installmentsByBank as $bankName => $group)
                    <div
                        x-data="{ open: false }"
                        class="border border-slate-200 rounded-xl mb-4 overflow-hidden">
                        <!-- Cabeçalho do banco -->
                        <button
                            @click="open = !open"
                            class="w-full flex items-center justify-between px-4 py-3 bg-slate-50 hover:bg-slate-100 text-left">
                            <span class="font-semibold text-slate-800 text-sm">
                                {{ strtoupper($bankName) }}
                                <span class="text-xs text-slate-500">
                                    ({{ $group->count() }} parcelas)
                                </span>
                            </span>

                            <span class="text-slate-500" x-show="!open">▼</span>
                            <span class="text-slate-500" x-show="open">▲</span>
                        </button>

                        <!-- Conteúdo do dropdown -->
                        <div x-show="open" x-collapse>
                            <table class="min-w-full divide-y divide-slate-200 text-sm">
                                <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Vencimento</th>
                                        <th class="px-4 py-2 text-left">Descrição</th>
                                        <th class="px-4 py-2 text-center">Parcela</th>
                                        <th class="px-4 py-2 text-right">Valor (R$)</th>
                                        <th class="px-4 py-2 text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @foreach ($group as $installment)
                                    <tr class="hover:bg-slate-50/80">
                                        <td class="px-4 py-2 text-slate-700">
                                            {{ \Carbon\Carbon::parse($installment->due_date)->format('d/m/Y') }}
                                        </td>

                                        <td class="px-4 py-2 text-slate-800">
                                            {{ $installment->expense->description }}
                                        </td>

                                        <td class="px-4 py-2 text-center text-slate-700">
                                            {{ $installment->installment_number }}
                                            / {{ $installment->total_installments }}
                                        </td>

                                        <td class="px-4 py-2 text-right font-medium text-slate-900">
                                            R$ {{ number_format($installment->amount, 2, ',', '.') }}
                                        </td>

                                        <td class="px-4 py-2 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a
                                                    href="{{ route('parcelas.index', $installment->expense) }}"
                                                    class="text-xs font-medium text-emerald-700 hover:text-emerald-900">
                                                    Ver detalhes
                                                </a>

                                                <form
                                                    action="{{ route('parcelas.pay', $installment) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Confirmar pagamento desta parcela?');">
                                                    @csrf
                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center rounded-lg bg-emerald-600 px-3 py-1 text-xs font-medium text-white hover:bg-emerald-700">
                                                        Pagar parcela
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif


                @endif

            </div>
        </div>
    </div>
</x-app-layout>