<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 text-green-600">{{ session('success') }}</div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('items.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium">Kategori</label>
                        <select name="category_id" id="category_id" class="border rounded w-full">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" data-code="{{ $category->code }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Kode Barang</label>
                        <input type="text" id="code" name="code" class="border rounded w-full" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Nama Barang</label>
                        <input type="text" name="name" class="border rounded w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Serial Number</label>
                        <input type="text" name="serial_number" class="border rounded w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Tahun Pengadaan</label>
                        <input type="number" name="procurement_year" class="border rounded w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Detail Barang</label>
                        <textarea name="description" class="border rounded w-full"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Kondisi</label>
                        <input type="text" name="condition" class="border rounded w-full">
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
                    </div>
                </form>
            </div>
            <div class="mt-8 bg-white shadow-sm sm:rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Daftar Barang</h2>
                <table class="min-w-full text-sm">
                    <thead>
                        <tr>
                            <th class="px-2 py-1 text-left">Kode</th>
                            <th class="px-2 py-1 text-left">Nama</th>
                            <th class="px-2 py-1 text-left">Kategori</th>
                            <th class="px-2 py-1 text-left">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td class="border px-2 py-1">{{ $item->code }}</td>
                                <td class="border px-2 py-1">{{ $item->name }}</td>
                                <td class="border px-2 py-1">{{ $item->category?->name }}</td>
                                <td class="border px-2 py-1">{{ $item->condition }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('category_id').addEventListener('change', function(){
            const id = this.value;
            if(!id){
                document.getElementById('code').value = '';
                return;
            }
            fetch('/items/create-code/' + id)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('code').value = data.code;
                });
        });
    </script>
</x-app-layout>
