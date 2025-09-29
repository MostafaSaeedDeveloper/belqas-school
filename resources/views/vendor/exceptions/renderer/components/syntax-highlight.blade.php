@props([
    'language' => null,
    'lineNumbers' => null,
    'highlightedLines' => [],
    'title' => null,
])

@php
    $code = $attributes->get('code');

    if ($code === null) {
        $code = rtrim((string) $slot, "\n");
    }

    if (is_array($code)) {
        $code = implode("\n", $code);
    }

    $lineNumbers = is_array($lineNumbers)
        ? implode(',', $lineNumbers)
        : $lineNumbers;

    $highlightedLines = is_array($highlightedLines)
        ? implode(',', $highlightedLines)
        : $highlightedLines;
@endphp

<section
    {{ $attributes->class(['syntax-highlight block rounded-lg bg-slate-950/95 text-slate-100 shadow-lg ring-1 ring-slate-900/10']) }}
>
    @if ($title)
        <header class="flex items-center justify-between border-b border-slate-800 px-4 py-2 text-xs font-medium uppercase tracking-wide text-slate-300">
            <span>{{ $title }}</span>
            @if ($language)
                <span class="rounded bg-slate-800 px-2 py-0.5 text-[0.65rem] text-slate-400">{{ strtoupper($language) }}</span>
            @endif
        </header>
    @elseif ($language)
        <header class="flex justify-end border-b border-slate-800 px-4 py-2 text-xs font-medium uppercase tracking-wide text-slate-400">
            <span>{{ strtoupper($language) }}</span>
        </header>
    @endif

    <pre
        @if ($language) data-language="{{ $language }}" @endif
        @if ($lineNumbers) data-line-numbers="{{ $lineNumbers }}" @endif
        @if ($highlightedLines) data-highlighted-lines="{{ $highlightedLines }}" @endif
        class="max-h-[32rem] overflow-auto p-4 text-sm leading-6"
    ><code>{{ $code }}</code></pre>
</section>
