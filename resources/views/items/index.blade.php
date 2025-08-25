<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Barang') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('items.store') }}" class="mb-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <input name="name" type="text" placeholder="Nama" class="border-gray-300 rounded" required>
                        <input name="description" type="text" placeholder="Deskripsi" class="border-gray-300 rounded">
                        <input name="condition" type="text" placeholder="Kondisi" class="border-gray-300 rounded">
                        <input name="stock" type="number" min="0" placeholder="Stok" class="border-gray-300 rounded" required>
                    </div>
                    <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">Tambah</button>
                </form>

                <table class="min-w-full text-sm text-left">
                    <thead class="border-b font-medium dark:border-neutral-500">
                        <tr>
                            <th class="px-6 py-4">Nama</th>
                            <th class="px-6 py-4">Stok</th>
                            <th class="px-6 py-4">Barcode</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr class="border-b dark:border-neutral-500">
                                <td class="whitespace-nowrap px-6 py-4">{{ $item->name }}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{ $item->stock }}</td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if ($item->barcode_path)
                                        <img src="{{ asset('storage/' . $item->barcode_path) }}" alt="barcode" class="h-12">
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
