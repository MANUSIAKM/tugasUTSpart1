@extends('app')

@section('content')
    <header class="text-center py-10 bg-white border-b border-gray-100">
        <h1 class="text-4xl md:text-5xl font-bold text-green-600 mb-2">Selamat datang di Masa Depan</h1>
        <p class="text-gray-500 text-lg">Mari berkembang & berbagi bersama orang susah</p>
    </header>

    <div class="max-w-6xl w-full mx-auto p-6 md:py-12 grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <section class="bg-white p-6 rounded-xl shadow-xs border border-gray-200 h-fit">
            <h2 id="form-title" class="text-xl font-bold text-gray-800 mb-4">Kirim Donasi Baru</h2>
            
            <form id="donation-form" onsubmit="handleFormSubmit(event)">
                <input type="hidden" id="donation-id">

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Donatur</label>
                    <input type="text" id="nama" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah Donasi (Rp)</label>
                    <input type="number" id="jumlah" required min="1000" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pesan Kebaikan</label>
                    <textarea id="pesan" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500" placeholder="Bismillah..."></textarea>
                </div>

                <div class="flex flex-col gap-2">
                    <button type="submit" id="btn-submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-md transition cursor-pointer">
                        Konfirmasi Donasi
                    </button>
                    <button type="button" id="btn-cancel" onclick="resetFormState()" class="hidden w-full bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold py-2 rounded-md transition cursor-pointer">
                        Batal Edit
                    </button>
                </div>
            </form>
        </section>

        <section class="lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Riwayat Donasi Warga</h2>
                <span id="total-donasi" class="bg-green-100 text-green-800 text-sm font-semibold px-3 py-1 rounded-full">Total: Rp 0</span>
            </div>

            <div id="donation-list" class="space-y-4">
                </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script>
        let donations = JSON.parse(localStorage.getItem('donasiku_data')) || [];

        function syncAndRender() {
            localStorage.setItem('donasiku_data', JSON.stringify(donations));
            renderDonations();
        }

        function renderDonations() {
            const listContainer = document.getElementById('donation-list');
            const totalContainer = document.getElementById('total-donasi');
            listContainer.innerHTML = '';
            let grandTotal = 0;

            if (donations.length === 0) {
                listContainer.innerHTML = `<div class="bg-white border border-gray-200 rounded-xl p-8 text-center text-gray-400">Belum ada riwayat donasi tercatat.</div>`;
                totalContainer.innerText = "Total: Rp 0";
                return;
            }

            donations.forEach(item => {
                grandTotal += parseInt(item.jumlah);
                const card = document.createElement('div');
                card.className = "bg-white p-5 rounded-xl border border-gray-200 flex justify-between items-start";
                card.innerHTML = `
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <h4 class="font-bold text-gray-900">${item.nama}</h4>
                            <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded">Rp ${parseInt(item.jumlah).toLocaleString('id-ID')}</span>
                        </div>
                        <p class="text-gray-600 italic">"${item.pesan}"</p>
                    </div>
                    <div class="flex gap-3 ml-4 text-xs font-medium shrink-0">
                        <button onclick="triggerEdit('${item.id}')" class="text-blue-600 hover:underline cursor-pointer">Edit</button>
                        <button onclick="deleteDonation('${item.id}')" class="text-red-500 hover:underline cursor-pointer">Hapus</button>
                    </div>`;
                listContainer.appendChild(card);
            });
            totalContainer.innerText = `Total: Rp ${grandTotal.toLocaleString('id-ID')}`;
        }

        function handleFormSubmit(e) {
            e.preventDefault();
            const id = document.getElementById('donation-id').value;
            const nama = document.getElementById('nama').value;
            const jumlah = document.getElementById('jumlah').value;
            const pesan = document.getElementById('pesan').value;

            if (id) {
                donations = donations.map(d => d.id === id ? { id, nama, jumlah, pesan } : d);
            } else {
                donations.push({ id: Date.now().toString(), nama, jumlah, pesan });
            }
            syncAndRender();
            resetFormState();
        }

        function triggerEdit(id) {
            const selected = donations.find(d => d.id === id);
            if (selected) {
                document.getElementById('donation-id').value = selected.id;
                document.getElementById('nama').value = selected.nama;
                document.getElementById('jumlah').value = selected.jumlah;
                document.getElementById('pesan').value = selected.pesan;
                document.getElementById('form-title').innerText = "Ubah Data Donasi";
                document.getElementById('btn-submit').innerText = "Simpan Perubahan";
                document.getElementById('btn-cancel').classList.remove('hidden');
            }
        }

        function deleteDonation(id) {
            if (confirm('Apakah Anda yakin ingin menghapus catatan donasi ini?')) {
                donations = donations.filter(d => d.id !== id);
                syncAndRender();
                if (document.getElementById('donation-id').value === id) resetFormState();
            }
        }

        function resetFormState() {
            document.getElementById('donation-form').reset();
            document.getElementById('donation-id').value = '';
            document.getElementById('form-title').innerText = "Kirim Donasi Baru";
            document.getElementById('btn-submit').innerText = "Konfirmasi Donasi";
            document.getElementById('btn-cancel').classList.add('hidden');
        }

        renderDonations();
    </script>
@endsection