@php
    $map = [
        'menunggu'   => ['Menunggu', 'bg-amber-100 text-amber-800 dark:bg-amber-700/30 dark:text-amber-200'],
        'disetujui'  => ['Disetujui', 'bg-green-100 text-green-800 dark:bg-green-700/30 dark:text-green-200'],
        'ditolak'    => ['Ditolak', 'bg-red-100 text-red-800 dark:bg-red-700/30 dark:text-red-200'],
        'dibatalkan' => ['Dibatalkan', 'bg-stone-200 text-stone-700 dark:bg-stone-700 dark:text-stone-200'],
    ];
    [$label, $cls] = $map[$status] ?? [$status, 'bg-stone-200 text-stone-700'];
@endphp
<span class="inline-block rounded-full px-2.5 py-1 text-xs font-bold {{ $cls }}">{{ $label }}</span>