<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Form Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('borrow.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block mb-1">Barang</label>
                        <select name="item_id" class="w-full border-gray-300 rounded" required>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} (stok: {{ $item->stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1">Jumlah</label>
                        <input type="number" name="quantity" min="1" value="1" class="w-full border-gray-300 rounded" required>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Pinjam</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
