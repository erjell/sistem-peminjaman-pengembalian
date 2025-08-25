<x-app-layout>
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Form Input Barang</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="bg-gray-100 p-6">

        <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
            <h1 class="text-2xl font-bold mb-4">Form Input Barang</h1>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
            @endif

            <form id="barangForm" action="{{ route('barang.store') }}" method="POST">
                @csrf
                <!-- Form Partner dan Keperluan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Partner</label>
                        <input type="text" class="w-full p-2 border border-gray-300 rounded" placeholder="Nama Partner">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keperluan</label>
                        <input type="text" class="w-full p-2 border border-gray-300 rounded" placeholder="Keperluan Barang">
                    </div>
                </div>

                <!-- Input Barcode / Nama Barang -->
                <div class="relative mb-4">
                    <label for="barcode" class="block text-sm font-medium text-gray-700 mb-1">Scan Barcode atau Cari Nama Barang:</label>
                    <input type="text" id="barcode" autocomplete="off" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Scan barcode atau ketik nama barang..." oninput="showSuggestions()" onkeydown="handleScan(event)">
                    <ul id="suggestions" class="absolute z-10 bg-white border border-gray-300 w-full mt-1 rounded shadow hidden max-h-48 overflow-y-auto"></ul>
                </div>

                <!-- Tabel Daftar Barang -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border border-gray-300">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-4 py-2">No</th>
                                <th class="px-4 py-2">Barcode</th>
                                <th class="px-4 py-2">Nama Barang</th>
                                <th class="px-4 py-2">Kategori</th>
                                <th class="px-4 py-2">Kondisi</th>
                                <th class="px-4 py-2">Jumlah</th>
                                <th class="px-4 py-2">Opsi</th>
                            </tr>
                        </thead>
                        <tbody id="barang-table" class="bg-white"></tbody>
                    </table>
                </div>

                <button type="submit" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Simpan Barang</button>
            </form>
        </div>

        <!-- Popup Peringatan -->
        <div id="popup" class="fixed bottom-5 right-5 bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-2 rounded shadow hidden"></div>

        <script>
            const dataBarang = @json($barangList);
    
        const namaToBarcode = {};
        for (const [barcode, barang] of Object.entries(dataBarang)) {
          namaToBarcode[barang.nama.toLowerCase()] = barcode;
        }
    
        const barcodeToRowMap = {};
        let counter = 1;
        let formIndex = 0;
    
        function showSuggestions() {
          const input = document.getElementById("barcode");
          const query = input.value.toLowerCase();
          const suggestions = document.getElementById("suggestions");
    
          suggestions.innerHTML = "";
          suggestions.classList.add("hidden");
    
          if (query.length === 0) return;
    
          const matched = Object.entries(dataBarang).filter(([_, barang]) =>
            barang.nama.toLowerCase().includes(query)
          );
    
          if (matched.length > 0) {
            matched.forEach(([barcode, barang]) => {
              const li = document.createElement("li");
              li.textContent = `${barang.nama}`;
              li.className = "px-4 py-2 hover:bg-blue-100 cursor-pointer";
              li.onclick = () => {
                input.value = barcode;
                suggestions.classList.add("hidden");
                addBarang(barcode);
                input.value = "";
              };
              suggestions.appendChild(li);
            });
    
            suggestions.classList.remove("hidden");
          }
        }
    
        function handleScan(event) {
          if (event.key === "Enter") {
            const input = document.getElementById("barcode");
            const kode = input.value.trim();
            input.value = "";
            document.getElementById("suggestions").classList.add("hidden");
            addBarang(kode);
          }
        }
    
        function addBarang(input) {
          const barcode = dataBarang[input]
            ? input
            : namaToBarcode[input.toLowerCase()];
    
          if (!barcode || !dataBarang[barcode]) {
            showPopup("❌ Barang tidak ditemukan.");
            return;
          }
    
          const tableBody = document.getElementById("barang-table");

          if (barcodeToRowMap[barcode]) {
            const row = barcodeToRowMap[barcode];
            const jumlahCell = row.querySelector(".jumlah-cell");
            let jumlah = parseInt(jumlahCell.textContent);
            jumlahCell.textContent = jumlah + 1;
            row.querySelector('.jumlah-input').value = jumlah + 1;
          } else {
            const barang = dataBarang[barcode];
            const row = document.createElement("tr");
            row.className = barang.kondisi.toLowerCase() === "rusak"
              ? "bg-red-100 text-red-700 font-semibold"
              : "";

            if (barang.kondisi.toLowerCase() === "rusak") {
              showPopup(`⚠️ Barang "${barang.nama}" dalam kondisi RUSAK!`);
            }

            row.innerHTML = `
              <td class="px-4 py-2 nomor">${counter++}</td>
              <td class="px-4 py-2">${barcode}</td>
              <td class="px-4 py-2">${barang.nama}</td>
              <td class="px-4 py-2">${barang.kategori}</td>
              <td class="px-4 py-2">${barang.kondisi}</td>
              <td class="px-4 py-2 jumlah-cell">1</td>
              <td class="px-4 py-2">
                <button type="button" onclick="hapusBarang('${barcode}')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</button>
                <input type="hidden" name="barang[${formIndex}][barcode]" value="${barcode}">
                <input type="hidden" name="barang[${formIndex}][nama]" value="${barang.nama}">
                <input type="hidden" class="jumlah-input" name="barang[${formIndex}][jumlah]" value="1">
              </td>
            `;

            tableBody.appendChild(row);
            barcodeToRowMap[barcode] = row;
            formIndex++;
          }
        }
    
        function hapusBarang(barcode) {
          const row = barcodeToRowMap[barcode];
          if (row) {
            row.remove();
            delete barcodeToRowMap[barcode];
            updateNomor();
          }
        }
    
        function updateNomor() {
          const rows = document.querySelectorAll("#barang-table tr");
          rows.forEach((row, index) => {
            row.querySelector(".nomor").textContent = index + 1;
          });
          counter = rows.length + 1;
        }
    
        function showPopup(message) {
          const popup = document.getElementById("popup");
          popup.textContent = message;
          popup.classList.remove("hidden");
          setTimeout(() => popup.classList.add("hidden"), 3000);
        }
        </script>

    </body>

    </html>

    {{-- <div class="container">
        <h4>Input Data Barang dengan Scanner</h4>
        <form action="{{ route('barang.store') }}" method="POST">
            @csrf

            <table class="table table-bordered" id="barangTable">
                <thead>
                    <tr>
                        <th>Barcode</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th><button type="button" class="btn btn-success btn-sm" id="addRow">+</button></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="barang[0][barcode]" class="form-control barcode-input" required></td>
                        <td><input type="text" name="barang[0][nama]" class="form-control"></td>
                        <td><input type="number" name="barang[0][jumlah]" class="form-control" value="1" min="1" required></td>
                        <td><button type="button" class="btn btn-danger btn-sm removeRow">-</button></td>
                    </tr>
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">Simpan Barang</button>
        </form>
    </div>

    <script>
        let rowCount = 1;
    
        document.getElementById('addRow').addEventListener('click', function () {
            const tableBody = document.querySelector('#barangTable tbody');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input type="text" name="barang[${rowCount}][barcode]" class="form-control barcode-input" required></td>
                <td><input type="text" name="barang[${rowCount}][nama]" class="form-control"></td>
                <td><input type="number" name="barang[${rowCount}][jumlah]" class="form-control" value="1" min="1" required></td>
                <td><button type="button" class="btn btn-danger btn-sm removeRow">-</button></td>
            `;
            tableBody.appendChild(newRow);
            rowCount++;
        });
    
        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('removeRow')) {
                e.target.closest('tr').remove();
            }
        });
    
        // Autofocus ke kolom barcode terakhir saat baris ditambahkan
        document.addEventListener('DOMNodeInserted', function (e) {
            if (e.target && e.target.querySelector('.barcode-input')) {
                setTimeout(() => {
                    e.target.querySelector('.barcode-input').focus();
                }, 100);
            }
        });
    </script> --}}
</x-app-layout>