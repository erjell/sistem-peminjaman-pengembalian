<x-app-layout>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Form Input Barang - Mobile</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100 p-4">
        <div class="max-w-md mx-auto bg-white p-4 rounded shadow">
            <h1 class="text-xl font-bold mb-4">Form Input Barang</h1>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
            @endif

            <form id="barangForm" action="{{ route('barang.store') }}" method="POST">
                @csrf
                <div class="space-y-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Partner</label>
                        <input type="text" class="w-full p-2 border border-gray-300 rounded" placeholder="Nama Partner">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keperluan</label>
                        <input type="text" class="w-full p-2 border border-gray-300 rounded" placeholder="Keperluan Barang">
                    </div>
                </div>

                <div class="relative mb-4">
                    <label for="barcode" class="block text-sm font-medium text-gray-700 mb-1">Scan Barcode atau Cari Nama Barang:</label>
                    <input type="text" id="barcode" autocomplete="off" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Scan barcode atau ketik nama barang..." oninput="showSuggestions()" onkeydown="handleScan(event)">
                    <ul id="suggestions" class="absolute z-10 bg-white border border-gray-300 w-full mt-1 rounded shadow hidden max-h-48 overflow-y-auto"></ul>
                </div>

                <div id="barang-list" class="space-y-2"></div>

                <button type="submit" class="mt-4 w-full bg-blue-500 hover:bg-blue-600 text-white p-2 rounded">Simpan Barang</button>
            </form>
        </div>

        <div id="popup" class="fixed bottom-5 right-5 bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-2 rounded shadow hidden"></div>

        <script>
        const dataBarang = @json($barangList);

        const namaToBarcode = {};
        for (const [barcode, barang] of Object.entries(dataBarang)) {
            namaToBarcode[barang.nama.toLowerCase()] = barcode;
        }

        const barcodeToCardMap = {};
        let counter = 1;
        let formIndex = 0;

        function showSuggestions() {
            const input = document.getElementById('barcode');
            const query = input.value.toLowerCase();
            const suggestions = document.getElementById('suggestions');

            suggestions.innerHTML = '';
            suggestions.classList.add('hidden');

            if (query.length === 0) return;

            const matched = Object.entries(dataBarang).filter(([_, barang]) =>
                barang.nama.toLowerCase().includes(query)
            );

            if (matched.length > 0) {
                matched.forEach(([barcode, barang]) => {
                    const li = document.createElement('li');
                    li.textContent = `${barang.nama}`;
                    li.className = 'px-4 py-2 hover:bg-blue-100 cursor-pointer';
                    li.onclick = () => {
                        input.value = barcode;
                        suggestions.classList.add('hidden');
                        addBarang(barcode);
                        input.value = '';
                    };
                    suggestions.appendChild(li);
                });

                suggestions.classList.remove('hidden');
            }
        }

        function handleScan(event) {
            if (event.key === 'Enter') {
                const input = document.getElementById('barcode');
                const kode = input.value.trim();
                input.value = '';
                document.getElementById('suggestions').classList.add('hidden');
                addBarang(kode);
            }
        }

        function addBarang(input) {
            const barcode = dataBarang[input] ? input : namaToBarcode[input.toLowerCase()];

            if (!barcode || !dataBarang[barcode]) {
                showPopup('❌ Barang tidak ditemukan.');
                return;
            }

            if (barcodeToCardMap[barcode]) {
                const card = barcodeToCardMap[barcode];
                const jumlahSpan = card.querySelector('.jumlah');
                let jumlah = parseInt(jumlahSpan.textContent);
                jumlahSpan.textContent = jumlah + 1;
                card.querySelector('.jumlah-input').value = jumlah + 1;
                return;
            }

            const barang = dataBarang[barcode];
            const list = document.getElementById('barang-list');
            const card = document.createElement('div');
            card.className = 'border rounded p-3 flex justify-between items-center';

            if (barang.kondisi.toLowerCase() === 'rusak') {
                card.classList.add('bg-red-100', 'text-red-700', 'font-semibold');
                showPopup(`⚠️ Barang "${barang.nama}" dalam kondisi RUSAK!`);
            }

            card.innerHTML = `
                <div>
                    <div class="font-bold">${counter++}. ${barang.nama}</div>
                    <div class="text-sm">Barcode: ${barcode}</div>
                    <div class="text-sm">Kategori: ${barang.kategori}</div>
                    <div class="text-sm">Kondisi: ${barang.kondisi}</div>
                    <div class="text-sm">Jumlah: <span class="jumlah">1</span></div>
                </div>
                <div>
                    <button type="button" onclick="hapusBarang('${barcode}')" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">Hapus</button>
                    <input type="hidden" name="barang[${formIndex}][barcode]" value="${barcode}">
                    <input type="hidden" name="barang[${formIndex}][nama]" value="${barang.nama}">
                    <input type="hidden" class="jumlah-input" name="barang[${formIndex}][jumlah]" value="1">
                </div>
            `;

            list.appendChild(card);
            barcodeToCardMap[barcode] = card;
            formIndex++;
        }

        function hapusBarang(barcode) {
            const card = barcodeToCardMap[barcode];
            if (!card) return;

            card.remove();
            delete barcodeToCardMap[barcode];

            const cards = document.querySelectorAll('#barang-list > div');
            counter = 1;
            cards.forEach(c => {
                const title = c.querySelector('.font-bold');
                if (title) {
                    title.textContent = `${counter++}. ${title.textContent.split('. ')[1]}`;
                }
            });
        }

        function showPopup(message) {
            const popup = document.getElementById('popup');
            popup.textContent = message;
            popup.classList.remove('hidden');
            setTimeout(() => popup.classList.add('hidden'), 3000);
        }
        </script>
    </body>
    </html>
</x-app-layout>
