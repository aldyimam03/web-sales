<div class="form-group">
    <label class="block text-sm font-semibold text-slate-700 mb-3">Daftar Item</label>
    <div class="overflow-x-auto rounded-xl border border-slate-200">
        <table class="w-full text-sm">
            <thead class="bg-slate-100 text-slate-700 uppercase text-xs">
                <tr>
                    <th class="p-3 text-center">Item</th>
                    <th class="p-3 text-center">Qty</th>
                    <th class="p-3 text-center">Harga</th>
                    <th class="p-3 text-center">Total</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="items-table" class="divide-y divide-slate-200">
                @php $rowIndex = 0; @endphp

                @forelse ($sale->items ?? [null] as $index => $row)
                    <tr>
                        <td class="p-3">
                            <select name="items[{{ $index }}][item_id]"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                                @foreach ($items as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->harga }}"
                                        {{ $row && $row->items_id == $product->id ? 'selected' : '' }}>
                                        {{ $product->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="p-3 text-center">
                            <input type="number" name="items[{{ $index }}][quantity]"
                                value="{{ $row->quantity ?? 1 }}" min="1"
                                class="w-20 text-center border rounded-lg qty-input no-spinner">
                        </td>
                        <td class="p-3 text-center">
                            <input type="text" name="items[{{ $index }}][price]"
                                value="{{ $row->harga ?? 0 }}" readonly
                                class="w-24 text-center border rounded-lg bg-slate-50 price-input">
                        </td>
                        <td class="p-3 text-center">
                            <input type="text" name="items[{{ $index }}][total]"
                                value="{{ $row->total_harga ?? '' }}" readonly
                                class="w-28 text-center border rounded-lg bg-slate-50 total-input">
                        </td>
                        <td class="p-3 text-center">
                            <button type="button" class="text-red-600 hover:text-red-800 remove-row cursor-pointer">✕</button>
                        </td>
                    </tr>
                    @php $rowIndex = $index+1; @endphp
                @empty
                @endforelse
            </tbody>
        </table>
    </div>

    <button type="button" id="add-row"
        class="mt-4 px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md transition">
        + Tambah Item
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let rowIndex = {{ $rowIndex ?? 1 }};

        function recalc() {
            let total = 0;
            document.querySelectorAll('#items-table tr').forEach(tr => {
                const qty = parseFloat(tr.querySelector('.qty-input')?.value || 0);
                const price = parseFloat(tr.querySelector('.price-input')?.value || 0);
                const rowTotal = qty * price;
                if (tr.querySelector('.total-input')) {
                    tr.querySelector('.total-input').value = rowTotal;
                }
                total += rowTotal;
            });
            const grandTotal = document.getElementById('grand-total');
            if (grandTotal) grandTotal.value = total;
        }

        // hitung awal
        recalc();

        // qty berubah
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('qty-input')) recalc();
        });

        // item berubah
        document.addEventListener('change', function(e) {
            if (e.target.tagName === 'SELECT') {
                const tr = e.target.closest('tr');
                const price = e.target.selectedOptions[0].dataset.price;
                tr.querySelector('.price-input').value = price;
                recalc();
            }
        });

        // tambah row
        document.getElementById('add-row').addEventListener('click', function() {
            const tbody = document.getElementById('items-table');
            const newRow = tbody.querySelector('tr').cloneNode(true);

            newRow.querySelectorAll('input, select').forEach(input => {
                if (input.tagName === 'SELECT') {
                    input.name = `items[${rowIndex}][item_id]`;
                } else if (input.classList.contains('qty-input')) {
                    input.name = `items[${rowIndex}][quantity]`;
                    input.value = 1;
                } else if (input.classList.contains('price-input')) {
                    input.name = `items[${rowIndex}][price]`;
                    input.value = '';
                } else if (input.classList.contains('total-input')) {
                    input.name = `items[${rowIndex}][total]`;
                    input.value = '';
                }
            });

            tbody.appendChild(newRow);
            rowIndex++;
        });

        // hapus row
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row')) {
                const tbody = document.getElementById('items-table');
                if (tbody.rows.length > 1) {
                    e.target.closest('tr').remove();
                    recalc();
                }
            }
        });
    });
</script>
