<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $expense->descricao }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        <div class="bg-white p-6 rounded shadow">

            <p><strong>Valor:</strong> R$ {{ number_format($expense->valor, 2, ',', '.') }}</p>
            <p><strong>Status:</strong> {{ $expense->is_paid ? 'Paga' : 'Pendente' }}</p>

            @if (!$expense->is_paid)
            <x-confirm-button
                title="Pagar despesa?"
                message="Deseja realmente marcar esta despesa como paga?"
                class="mt-4 !bg-emerald-600 hover:!bg-emerald-700 !px-4 !py-2 !text-sm rounded-lg">
                Marcar como paga

                <x-slot name="actions">
                    <form action="{{ route('despesas.pay', $expense) }}" method="POST">
                        @csrf
                        {{-- Nada de PATCH aqui, a rota é POST --}}
                        <button
                            type="submit"
                            class="rounded-full bg-emerald-600 px-6 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                            Sim
                        </button>
                    </form>
                </x-slot>
            </x-confirm-button>
            @endif


        </div>
    </div>
</x-app-layout>