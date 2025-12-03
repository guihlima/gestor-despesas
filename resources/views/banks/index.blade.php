{{-- resources/views/banks/index.blade.php --}}
<x-app-layout>
    {{-- Cabeçalho da página (vai para {{ $header }} do app.blade) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bancos / Cartões
        </h2>
    </x-slot>

    {{-- Conteúdo principal (vai para {{ $slot }} do app.blade) --}}
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-semibold tracking-tight text-slate-900">
                            Bancos / Cartões
                        </h2>
                        <p class="text-sm text-slate-500">
                            Cadastre aqui os bancos e cartões para associar às suas despesas.
                        </p>
                    </div>

                    <a
                        href="{{ route('bancos.create') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                    >
                        <span class="text-base leading-none">＋</span>
                        Novo banco
                    </a>
                </div>

                @if ($banks->isEmpty())
                    <p class="text-sm text-slate-500">
                        Nenhum banco cadastrado ainda. Clique em <strong>“Novo banco”</strong> para adicionar,
                        por exemplo: Nubank, Itaú, Santander...
                    </p>
                @else
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Nome
                                    </th>
                                    <th class="px-4 py-2 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Qtd. despesas
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach ($banks as $bank)
                                    <tr class="hover:bg-slate-50/80">
                                        <td class="px-4 py-2 text-slate-800">
                                            {{ $bank->name }}
                                        </td>
                                        <td class="px-4 py-2 text-right text-slate-600">
                                            {{ $bank->expenses()->count() }}
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
