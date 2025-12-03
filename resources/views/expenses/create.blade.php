<x-app-layout>
    {{-- Cabeçalho da página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Cadastrar Despesa
        </h2>
    </x-slot>

    {{-- Conteúdo --}}
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-semibold tracking-tight text-slate-900">Cadastrar despesa</h2>
                        <p class="text-sm text-slate-500">Informe os dados da despesa. Você pode marcar como parcelada.</p>
                    </div>

                    <a
                        href="{{ route('despesas.index') }}"
                        class="text-sm text-slate-500 hover:text-slate-700">
                        Voltar para lista
                    </a>
                </div>

                <form action="{{ route('despesas.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-1">
                                Descrição
                            </label>
                            <input
                                type="text"
                                id="description"
                                name="description"
                                value="{{ old('description') }}"
                                required
                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                placeholder="Ex: Cartão de crédito, Aluguel, Compra notebook...">
                        </div>

                        <div>
                            <label for="total_amount" class="block text-sm font-medium text-slate-700 mb-1">
                                Valor total (R$)
                            </label>
                            <input
                                type="text"
                                id="total_amount"
                                name="total_amount"
                                value="{{ old('total_amount') }}"
                                required
                                data-currency="brl"
                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label for="date" class="block text-sm font-medium text-slate-700 mb-1">
                                Data da compra/lançamento
                            </label>
                            <input
                                type="date"
                                id="date"
                                name="date"
                                value="{{ old('date', now()->format('Y-m-d')) }}"
                                required
                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label for="bank_id" class="block text-sm font-medium text-slate-700 mb-1">
                            Banco / cartão utilizado
                        </label>

                        <select
                            id="bank_id"
                            name="bank_id"
                            class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Selecione um banco (opcional)</option>
                            @foreach ($banks as $bank)
                                <option
                                    value="{{ $bank->id }}"
                                    @selected(old('bank_id')==$bank->id)
                                >
                                    {{ $bank->name }}
                                </option>
                            @endforeach
                        </select>

                        <p class="mt-1 text-xs text-slate-500">
                            Não encontrou o banco? <a href="{{ route('bancos.create') }}" class="text-emerald-700 hover:underline">Cadastre aqui</a>.
                        </p>
                    </div>

                    <div class="border-t border-slate-200 pt-4">
                        <div class="flex items-center gap-2 mb-3">
                            <input
                                type="checkbox"
                                id="is_installment"
                                name="is_installment"
                                value="1"
                                @checked(old('is_installment'))
                                class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <label for="is_installment" class="text-sm font-medium text-slate-700">
                                Esta despesa é parcelada
                            </label>
                        </div>

                        <div id="installments-fields" class="space-y-4 @if(!old('is_installment')) hidden @endif">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                <div>
                                    <label for="installments_count" class="block text-sm font-medium text-slate-700 mb-1">
                                        Número de parcelas
                                    </label>
                                    <input
                                        type="number"
                                        id="installments_count"
                                        name="installments_count"
                                        min="2"
                                        value="{{ old('installments_count', 2) }}"
                                        class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>

                                <div>
                                    <label for="first_due_date" class="block text-sm font-medium text-slate-700 mb-1">
                                        Vencimento da 1ª parcela
                                    </label>
                                    <input
                                        type="date"
                                        id="first_due_date"
                                        name="first_due_date"
                                        value="{{ old('first_due_date', now()->addMonth()->format('Y-m-d')) }}"
                                        class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>

                                <div class="pt-1">
                                    <button
                                        type="button"
                                        id="btn-generate-installments"
                                        class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                        Gerar parcelas
                                    </button>
                                    <p class="mt-1 text-xs text-slate-500">
                                        Usa o valor total e divide automaticamente. Você poderá ajustar manualmente.
                                    </p>
                                </div>
                            </div>

                            {{-- Aqui vão aparecer as parcelas geradas --}}
                            <div id="installments-list" class="space-y-2 mt-4"></div>
                        </div>
                    </div>

                    <div class="pt-2 flex items-center gap-3">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Salvar despesa
                        </button>

                        <a
                            href="{{ route('despesas.index') }}"
                            class="text-sm text-slate-500 hover:text-slate-700">
                            Cancelar
                        </a>
                    </div>
                </form>

                {{-- Scripts ficam dentro do slot sem problema --}}
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const checkbox = document.getElementById('is_installment');
                        const fields = document.getElementById('installments-fields');

                        const toggleFields = () => {
                            if (checkbox.checked) {
                                fields.classList.remove('hidden');
                            } else {
                                fields.classList.add('hidden');
                            }
                        };

                        checkbox.addEventListener('change', toggleFields);
                        toggleFields();
                    });
                </script>

                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const checkbox = document.getElementById('is_installment');
                        const fields = document.getElementById('installments-fields');
                        const btnGenerate = document.getElementById('btn-generate-installments');
                        const listContainer = document.getElementById('installments-list');
                        const totalInput = document.getElementById('total_amount');
                        const installmentsInput = document.getElementById('installments_count');
                        const firstDueDateInput = document.getElementById('first_due_date');

                        function toggleFields() {
                            if (checkbox.checked) {
                                fields.classList.remove('hidden');
                            } else {
                                fields.classList.add('hidden');
                                listContainer.innerHTML = '';
                            }
                        }

                        checkbox.addEventListener('change', toggleFields);
                        toggleFields();

                        function parseBRLToCents(value) {
                            if (!value) return 0;
                            let v = String(value).trim();
                            v = v.replace(/\./g, '').replace(',', '.'); // "1.234,56" -> "1234.56"
                            const float = parseFloat(v);
                            if (isNaN(float)) return 0;
                            return Math.round(float * 100);
                        }

                        function formatCentsToBRL(cents) {
                            const v = (cents / 100).toFixed(2); // "1234.56"
                            return window.formatBRL ? window.formatBRL(v) : v;
                        }

                        function addMonthsToDate(dateStr, monthsToAdd) {
                            const [year, month, day] = dateStr.split('-').map(Number);
                            if (!year || !month || !day) return '';
                            const d = new Date(year, month - 1 + monthsToAdd, day);
                            const yyyy = d.getFullYear();
                            const mm = String(d.getMonth() + 1).padStart(2, '0');
                            const dd = String(d.getDate()).padStart(2, '0');
                            return `${yyyy}-${mm}-${dd}`;
                        }

                        function recalcLastInstallment() {
                            const totalCents = parseBRLToCents(totalInput.value);
                            if (!totalCents) return;

                            const amountInputs = listContainer.querySelectorAll('input[name="installment_amounts[]"]');
                            const len = amountInputs.length;
                            if (len === 0) return;

                            let sumExceptLast = 0;
                            for (let i = 0; i < len - 1; i++) {
                                sumExceptLast += parseBRLToCents(amountInputs[i].value);
                            }

                            let lastCents = totalCents - sumExceptLast;
                            if (lastCents < 0) {
                                lastCents = 0;
                            }

                            const lastInput = amountInputs[len - 1];
                            lastInput.value = formatCentsToBRL(lastCents);
                        }

                        function generateInstallments() {
                            const totalCents = parseBRLToCents(totalInput.value);
                            const count = parseInt(installmentsInput.value, 10);
                            const firstDue = firstDueDateInput.value;

                            if (!totalCents || !count || count < 2) {
                                alert('Informe o valor total e pelo menos 2 parcelas.');
                                return;
                            }

                            const base = Math.floor(totalCents / count);
                            const remainder = totalCents % count;

                            listContainer.innerHTML = '';

                            for (let i = 0; i < count; i++) {
                                let cents = base;
                                if (i === count - 1) {
                                    cents += remainder;
                                }

                                const dueDate = firstDue ? addMonthsToDate(firstDue, i) : '';

                                const row = document.createElement('div');
                                row.className = 'grid grid-cols-1 md:grid-cols-3 gap-4 items-end border border-slate-100 rounded-xl px-3 py-2 bg-slate-50';

                                row.innerHTML = `
                                    <div>
                                        <span class="block text-xs font-medium text-slate-500 mb-1">Parcela</span>
                                        <span class="inline-flex items-center rounded-full bg-slate-800 text-white px-2 py-0.5 text-xs font-semibold">
                                            ${i + 1} / ${count}
                                        </span>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">
                                            Valor (R$)
                                        </label>
                                        <input
                                            type="text"
                                            name="installment_amounts[]"
                                            value="${formatCentsToBRL(cents)}"
                                            data-currency="brl"
                                            class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                        >
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">
                                            Vencimento
                                        </label>
                                        <input
                                            type="date"
                                            name="installment_due_dates[]"
                                            value="${dueDate}"
                                            class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                        >
                                    </div>
                                `;

                                listContainer.appendChild(row);
                            }

                            const amountInputs = listContainer.querySelectorAll('input[name="installment_amounts[]"]');
                            amountInputs.forEach((input, idx) => {
                                if (window.attachBRLCurrencyMask) {
                                    window.attachBRLCurrencyMask(input);
                                }

                                if (idx < amountInputs.length - 1) {
                                    input.addEventListener('change', recalcLastInstallment);
                                    input.addEventListener('blur', recalcLastInstallment);
                                }
                            });
                        }

                        if (btnGenerate) {
                            btnGenerate.addEventListener('click', generateInstallments);
                        }
                    });
                </script>

            </div>
        </div>
    </div>
</x-app-layout>
