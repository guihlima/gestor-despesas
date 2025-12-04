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
                <form action="{{ route('despesas.pay', $expense) }}" method="POST" class="mt-4">
                    @csrf
                    @method('PATCH')
                    <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Marcar como paga
                    </button>
                </form>
            @endif

        </div>
    </div>
</x-app-layout>
