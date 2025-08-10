<!-- Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('payment-proof.store') }}" enctype="multipart/form-data"
            class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <!-- Pilih Bank -->
                <div class="mb-3">
                    <label class="form-label">Pilih Metode Pembayaran</label>
                    <select name="payment_id" class="form-select" id="paymentSelect" required>
                        <option value="">-- Pilih Bank --</option>
                        @foreach ($payments as $payment)
                            <option value="{{ $payment->id }}" data-nama="{{ $payment->nama_bank }}"
                                data-atas-nama="{{ $payment->atas_nama }}"
                                data-no-rekening="{{ $payment->no_rekening }}">
                                {{ $payment->nama_bank }} - {{ $payment->atas_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Info Bank -->
                <div id="paymentInfo" class="border rounded p-3 mb-3 d-none">
                    <p><strong id="bankNama"></strong></p>
                    <p>A/N: <span id="bankAtasNama"></span></p>
                    <p>
                        No Rek:
                        <span id="bankNoRek" style="user-select: all;"></span>
                        <button type="button" id="copyRekBtn" class="btn btn-sm btn-outline-success ms-2"
                            title="Copy No Rekening"
                            style="padding: 10px 10px; font-size: 1rem; vertical-align: middle;">
                            <i class="bi bi-clipboard"></i>
                        </button>

                    </p>
                </div>

                <!-- Upload Bukti -->
                <div class="mb-3">
                    <label class="form-label">Upload Bukti Transfer</label>
                    <input type="file" name="bukti_pembayaran" class="form-control" id="buktiInput" accept="image/*"
                        required>
                </div>

                <!-- Preview Bukti -->
                <div id="previewContainer" class="text-center d-none">
                    <p class="mb-2">Preview Bukti Transfer:</p>
                    <img id="previewImage" class="img-fluid rounded" style="max-height: 250px;" />
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success w-100">Kirim</button>
            </div>

            <!-- Alamat -->
            <div class="mb-4 px-3 px-md-4" id="alamatDiModal">
                <h5 class="fw-bold mb-2">📦 Dikirim ke:</h5>
                <div class="bg-white border rounded shadow-sm p-3">
                    <div id="modalNamaPenerima" class="fw-semibold mb-1"></div>
                    <div id="modalAlamatLengkap" class="mb-1"></div>
                    <div id="modalKotaProvinsi" class="text-muted mb-1"></div>
                    <div id="modalTelepon" class="text-muted"></div>
                </div>
            </div>

            <!-- Ringkasan Pesanan -->
            @if (isset($cartItems) && count($cartItems) > 0)
                <div class="mb-4 px-3 px-md-4">
                    <h5 class="fw-bold mb-3">🛍️ Ringkasan Pesanan</h5>
                    <div class="list-group shadow-sm rounded">
                        @php $subtotal = 0; @endphp
                        @foreach ($cartItems as $item)
                            @php
                                $subtotalBarang =
                                    $item->qty * $item->harga_saat_itulah * (1 - ($item->diskon ?? 0) / 100);
                                $subtotal += $subtotalBarang;
                            @endphp
                            <div class="list-group-item d-flex justify-content-between">
                                <div>
                                    <div class="fw-semibold">{{ $item->item->nama }}</div>
                                    <small class="text-muted">Qty: {{ $item->qty }}</small>
                                </div>
                                <div class="fw-semibold">Rp{{ number_format($subtotalBarang, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 p-3 rounded bg-light border">
                        @php $potongan = $subtotal * ($diskonPersen / 100); @endphp
                        @php $totalAkhir = $subtotal - $potongan; @endphp

                        <ul class="list-unstyled">
                            <li class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Subtotal</span>
                                <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                            </li>
                            <li class="d-flex justify-content-between text-danger mb-1">
                                <span>Diskon ({{ $diskonPersen }}%)</span>
                                <span>- Rp{{ number_format($potongan, 0, ',', '.') }}</span>
                            </li>
                            <li class="d-flex justify-content-between text-secondary mb-1">
                                <span>Harga diluar Sumsel</span>
                                <span id="modalOngkir">+ Rp {{ number_format($ongkirPerItem, 0, ',', '.') }}</span>
                            </li>
                            <li class="d-flex justify-content-between fw-bold fs-5 border-top pt-2">
                                <span>Total Bayar</span>
                                <span id="modalTotalBayar">Rp{{ number_format($totalAkhir, 0, ',', '.') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Hidden Fields -->
            <input type="hidden" name="alamat[nama]" id="inputNamaPenerima">
            <input type="hidden" name="alamat[alamat]" id="inputAlamatLengkap">
            <input type="hidden" name="alamat[kota]" id="inputKota">
            <input type="hidden" name="alamat[provinsi]" id="inputProvinsi">
            <input type="hidden" name="alamat[telp]" id="inputTelp">
            <input type="hidden" name="summary[ongkir]" id="inputOngkir">
            <input type="hidden" name="summary[subtotal]" value="{{ $subtotal }}">
            <input type="hidden" name="summary[diskon_persen]" value="{{ $diskonPersen }}">
            <input type="hidden" name="summary[total]" value="{{ $totalAkhir }}">

            @foreach ($cartItems as $index => $item)
                <input type="hidden" name="items[{{ $index }}][item_id]" value="{{ $item->item->id }}">
                <input type="hidden" name="items[{{ $index }}][qty]" value="{{ $item->qty }}">
                <input type="hidden" name="items[{{ $index }}][harga]"
                    value="{{ $item->harga_saat_itulah }}">
                <input type="hidden" name="items[{{ $index }}][diskon]" value="{{ $item->diskon ?? 0 }}">
            @endforeach
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const paymentSelect = document.getElementById("paymentSelect");
        const paymentInfo = document.getElementById("paymentInfo");
        const bankNama = document.getElementById("bankNama");
        const bankAtasNama = document.getElementById("bankAtasNama");
        const bankNoRek = document.getElementById("bankNoRek");
        const buktiInput = document.getElementById("buktiInput");
        const previewContainer = document.getElementById("previewContainer");
        const previewImage = document.getElementById("previewImage");
        const checkoutModal = document.getElementById("checkoutModal");

        const copyRekBtn = document.getElementById("copyRekBtn");

        // Update informasi bank
        paymentSelect.addEventListener("change", function() {
            const option = this.options[this.selectedIndex];
            if (option.value !== "") {
                bankNama.textContent = option.dataset.nama;
                bankAtasNama.textContent = option.dataset.atasNama;
                bankNoRek.textContent = option.dataset.noRekening;
                paymentInfo.classList.remove("d-none");
            } else {
                paymentInfo.classList.add("d-none");
                bankNoRek.textContent = "";
            }
        });

        // Copy nomor rekening ke clipboard
        copyRekBtn.addEventListener("click", function() {
            const textToCopy = bankNoRek.textContent.trim();
            if (!textToCopy) return;

            navigator.clipboard.writeText(textToCopy).then(() => {
                copyRekBtn.setAttribute('title', 'Copied!');
                copyRekBtn.classList.add('btn-success');
                setTimeout(() => {
                    copyRekBtn.setAttribute('title', 'Copy No Rekening');
                    copyRekBtn.classList.remove('btn-success');
                }, 1500);
            }).catch(() => {
                alert('Gagal menyalin nomor rekening');
            });
        });

        // Preview bukti transfer
        buktiInput.addEventListener("change", function() {
            const file = this.files[0];
            if (file && file.type.startsWith("image/")) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove("d-none");
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add("d-none");
            }
        });

        // Saat modal tampil, isi data alamat & ongkir
        checkoutModal.addEventListener("shown.bs.modal", function() {
            const alamatSelect = document.getElementById("alamatSelect");
            const selected = alamatSelect?.options[alamatSelect.selectedIndex];

            const nama = selected?.dataset.nama || '';
            const alamat = selected?.dataset.alamat || '';
            const kota = selected?.dataset.kota || '';
            const provinsi = selected?.dataset.provinsi || '';
            const telp = selected?.dataset.telp || '';

            document.getElementById("modalNamaPenerima").textContent = nama;
            document.getElementById("modalAlamatLengkap").textContent = alamat;
            document.getElementById("modalKotaProvinsi").textContent = `${kota}, ${provinsi}`;
            document.getElementById("modalTelepon").textContent = `Telp: ${telp}`;

            document.getElementById("inputNamaPenerima").value = nama;
            document.getElementById("inputAlamatLengkap").value = alamat;
            document.getElementById("inputKota").value = kota;
            document.getElementById("inputProvinsi").value = provinsi;
            document.getElementById("inputTelp").value = telp;

            const totalQty = {{ $cartItems->sum('qty') }};
            const provBersih = provinsi.trim().toLowerCase();

            // Daftar provinsi di Pulau Jawa
            const provinsiJawa = [
                'banten',
                'dki jakarta',
                'jawa barat',
                'jawa tengah',
                'di yogyakarta',
                'jawa timur'
            ];

            // Kalau alamat provinsi ada di daftar Jawa -> ongkir 1000 per item
            const ongkir = !provinsiJawa.includes(provBersih) ? 1000 * totalQty : 0;

            document.getElementById("inputOngkir").value = ongkir;
            document.getElementById("modalOngkir").textContent = `Rp ${ongkir.toLocaleString("id-ID")}`;

            const totalSetelahDiskon = {{ $subtotal }} - ({{ $subtotal }} *
                {{ $diskonPersen }} / 100);
            const totalDenganOngkir = totalSetelahDiskon + ongkir;

            document.getElementById("modalTotalBayar").textContent =
                `Rp${totalDenganOngkir.toLocaleString("id-ID")}`;
        });

    });
</script>
