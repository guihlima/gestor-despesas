@props([
'title' => 'Mensagem de confirmação',
'message' => null,
'confirmText' => 'Sim',
'cancelText' => 'Cancelar',
])

<div x-data="{ open: false }" class="inline-block">
    {{-- Botão que abre o modal --}}
    <button
        type="button"
        @click="open = true"
        {{ $attributes->class('rounded-lg bg-rose-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-rose-700 shadow-sm') }}>
        {{ $slot }}
    </button>

    {{-- Overlay --}}
    <div
        x-cloak
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60">
        {{-- Caixa do modal (estilo do seu desenho) --}}
        <div
            @click.away="open = false"
            x-transition.scale.origin.center
            class="w-full max-w-md rounded-[2rem] bg-white px-10 py-8 shadow-2xl border border-slate-100">
            <div class="flex flex-col items-center text-center gap-6">
                {{-- Ícone triângulo de alerta --}}
                <div class="flex items-center justify-center">
                    <svg class="h-16 w-16 text-red-500" viewBox="0 0 24 24" fill="none">
                        <!-- Triângulo -->
                        <path
                            d="M12 3L2.5 19h19L12 3z"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linejoin="round"
                            fill="none" />

                        <!-- Traço do ponto de exclamação -->
                        <path
                            d="M12 8v6"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round" />

                        <!-- Ponto do ponto de exclamação -->
                        <circle
                            cx="12"
                            cy="16.5"
                            r="1.2"
                            fill="currentColor" />
                    </svg>
                </div>


                {{-- Texto principal --}}
                <div class="space-y-1">
                    <p class="text-base font-semibold text-slate-900">
                        {{ $title }}
                    </p>

                    @if ($message)
                    <p class="text-sm text-slate-500">
                        {{ $message }}
                    </p>
                    @endif
                </div>

                {{-- Botões --}}
                <div class="mt-4 flex w-full items-center justify-center gap-8">
                    {{-- Cancelar --}}
                    <button
                        type="button"
                        @click="open = false"
                        class="rounded-full border border-slate-300 px-6 py-2 text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 shadow-sm">
                        {{ $cancelText }}
                    </button>

                    {{-- Confirmação (vem de fora, via slot "actions") --}}
                    {{ $actions ?? '' }}
                </div>
            </div>
        </div>
    </div>
</div>