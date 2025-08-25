<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full text-sm text-left">
                    <thead class="border-b font-medium dark:border-neutral-500">
                        <tr>
                            <th class="px-6 py-4">Barang</th>
                            <th class="px-6 py-4">Jumlah</th>
                            <th class="px-6 py-4">Peminjam</th>
                            <th class="px-6 py-4">Dipinjam</th>
                            <th class="px-6 py-4">Dikembalikan</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $record)
                            <tr class="border-b dark:border-neutral-500">
                                <td class="whitespace-nowrap px-6 py-4">{{ $record->item->name }}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{ $record->quantity }}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{ $record->user->name }}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{ $record->borrowed_at->format('d/m/Y H:i') }}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{ $record->returned_at ? $record->returned_at->format('d/m/Y H:i') : '-' }}</td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if (!$record->returned_at)
                                        <form method="POST" action="{{ route('borrow.return', $record) }}">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded">Kembalikan</button>
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
</x-app-layout>
