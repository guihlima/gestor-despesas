@props([
'type' => 'success', // success | warning | error | info
'title' => null,
'message' => null,
])

@php
switch ($type) {
case 'success':
$bgTop = 'bg-emerald-500';
$bgBottom = 'bg-emerald-100';
$iconBg = 'bg-emerald-100';
$iconColor = 'text-emerald-500';
$title = $title ?? 'Sucesso';
break;

case 'warning':
$bgTop = 'bg-amber-500';
$bgBottom = 'bg-amber-100';
$iconBg = 'bg-amber-100';
$iconColor = 'text-amber-500';
$title = $title ?? 'Atenção';
break;

case 'error':
$bgTop = 'bg-rose-500';
$bgBottom = 'bg-rose-100';
$iconBg = 'bg-rose-100';
$iconColor = 'text-rose-500';
$title = $title ?? 'Erro';
break;

default: // info
$bgTop = 'bg-sky-500';
$bgBottom = 'bg-sky-100';
$iconBg = 'bg-sky-100';
$iconColor = 'text-sky-500';
$title = $title ?? 'Informação';
}
@endphp

<div
     x-data="{ show: true }"
    x-init="setTimeout(() => show = false, 3500)" {{-- 3.5s, ajuste se quiser --}}
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-2"
    x-cloak
    class="inline-block">
    <div class="relative w-72">
        {{-- parte de cima (card branco) --}}
        <div class="rounded-2xl bg-white px-5 py-4 shadow-lg">
            <div class="flex items-start gap-3">
                {{-- ícone --}}
                <div class="flex h-10 w-10 items-center justify-center rounded-full {{ $iconBg }}">
                    @if($type === 'success')
                    {{-- check --}}
                    <svg class="h-6 w-6 {{ $iconColor }}" viewBox="0 0 24 24" fill="none">
                        <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    @elseif($type === 'warning')
                    {{-- ! --}}
                    <svg class="h-6 w-6 {{ $iconColor }}" viewBox="0 0 24 24" fill="none">
                        <path d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a1 1 0 00.86 1.5h18.64a1 1 0 00.86-1.5L13.71 3.86a1 1 0 00-1.72 0z"
                            stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    @elseif($type === 'error')
                    {{-- X --}}
                    <svg class="h-6 w-6 {{ $iconColor }}" viewBox="0 0 24 24" fill="none">
                        <path d="M6 6l12 12M18 6L6 18"
                            stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    @else
                    {{-- i --}}
                    <svg class="h-6 w-6 {{ $iconColor }}" viewBox="0 0 24 24" fill="none">
                        <path d="M12 8h.01M11 12h1v4m8-4a8 8 0 11-16 0 8 8 0 0116 0z"
                            stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    @endif
                </div>

                {{-- textos --}}
                <div>
                    <h3 class="text-base font-semibold text-slate-900">
                        {{ $title }}
                    </h3>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $message ?? $slot }}
                    </p>
                </div>
            </div>
        </div>

        {{-- “base colorida” embaixo  --}}
        <div class="absolute inset-x-3 -bottom-3 h-4 rounded-xl {{ $bgBottom }}"></div>

        {{-- “top bar” colorida para lembrar o card da imagem --}}
        <div class="absolute inset-x-0 -top-1 h-1 rounded-t-2xl {{ $bgTop }}"></div>
    </div>
</div>