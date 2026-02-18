<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Dokumen Backdate') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">

                    {{-- Judul --}}
                    <div class="flex items-center mb-8">
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center justify-center w-10 h-10 bg-gray-200 rounded-full hover:bg-gray-300 transition">
                            <i class="fas fa-arrow-left text-gray-700"></i>
                        </a>

                        <h1 class="text-xl font-bold text-gray-800 ml-4">
                            Dokumen Backdate
                        </h1>
                    </div>

                    {{-- Alert Success --}}
                    @if (session('success'))
                        @php
                            $message = session('success');
                            preg_match('/nomor:\s*(.+)$/i', $message, $matches);
                            $nomorSurat = $matches[1] ?? null;
                        @endphp

                        <div
                            class="mb-6 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded flex flex-wrap gap-2">
                            <span>{{ $message }}</span>

                            @if ($nomorSurat)
                                <button type="button" onclick="copyNomorSurat('{{ addslashes($nomorSurat) }}')"
                                    class="bg-green-600 text-white px-2 py-1 text-sm rounded hover:bg-green-700">
                                    Salin
                                </button>
                            @endif
                        </div>
                    @endif


                    {{-- ================= Alpine Root ================= --}}
                    <div x-data="{ selectedType: '{{ old('jenis_dokumen', 'memo_internal') }}' }" class="space-y-6">

                        {{-- Jenis Dokumen --}}
                        <div>
                            <x-input-label value="Jenis Dokumen" />

                            <x-select-input x-model="selectedType" class="w-full">
                                <option value="memo_internal">Memo Internal</option>
                                <option value="surat_keluar">Surat Keluar</option>
                            </x-select-input>
                        </div>


                        {{-- ===================================================== --}}
                        {{-- ================= FORM MEMO ========================== --}}
                        {{-- ===================================================== --}}
                        <form x-show="selectedType==='memo_internal'" method="POST"
                            action="{{ route('dokumen.store.backdate.memo') }}" class="space-y-6">

                            @csrf
                            <input type="hidden" name="jenis_dokumen" value="memo_internal">

                            <div>
                                <x-input-label value="Tanggal Backdate" />
                                <x-text-input type="date" name="tanggal_backdate"
                                    max="{{ \Carbon\Carbon::yesterday()->toDateString() }}" required class="w-full" />
                            </div>

                            <div>
                                <x-input-label for="unit_kerja" :value="__('Unit Kerja')" />
                                <x-select-input id="unit_kerja" name="unit_kerja" class="block mt-1 w-full" required>
                                    <option value="" disabled selected>-- Pilih Unit Kerja --</option>
                                    @foreach ($unitKerja as $unit)
                                        <option value="{{ $unit }}"
                                            {{ old('unit_kerja') == $unit ? 'selected' : '' }}>
                                            {{ $unit }}
                                        </option>
                                    @endforeach
                                </x-select-input>
                                <x-input-error :messages="$errors->get('unit_kerja')" class="mt-2" />
                            </div>

                            {{-- Tujuan --}}
                            <div>
                                <x-input-label for="tujuan" :value="__('Tujuan')" />
                                <x-select-input id="tujuan" class="block mt-1 w-full" name="tujuan"
                                    :value="old('tujuan')" required>
                                    <option value="" disabled selected>-- Pilih Tujuan --</option>
                                    @foreach ($tujuans as $tujuan)
                                        <option value="{{ $tujuan }}"
                                            {{ old('tujuan') == $tujuan ? 'selected' : '' }}>
                                            {{ $tujuan }}
                                        </option>
                                    @endforeach
                                </x-select-input>
                                <x-input-error :messages="$errors->get('tujuan')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="dari" :value="__('Dari')" />
                                <x-select-input id="dari" class="block mt-1 w-full" name="dari"
                                    :value="old('dari')" required>
                                    <option value="" disabled selected>-- Pilih dari --</option>
                                    @foreach ($daris as $dari)
                                        <option value="{{ $dari }}"
                                            {{ old('dari') == $dari ? 'selected' : '' }}>
                                            {{ $dari }}
                                        </option>
                                    @endforeach
                                </x-select-input>
                                <x-input-error :messages="$errors->get('dari')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="tembusan" :value="__('Tembusan')" />
                                <x-select-input id="tembusan" class="block mt-1 w-full" name="tembusan"
                                    :value="old('tembusan')" required>
                                    <option value="" disabled selected>-- Pilih tembusan --</option>
                                    @foreach ($tembusans as $tembusan)
                                        <option value="{{ $tembusan }}"
                                            {{ old('tembusan') == $tembusan ? 'selected' : '' }}>
                                            {{ $tembusan }}
                                        </option>
                                    @endforeach
                                </x-select-input>
                                <x-input-error :messages="$errors->get('dari')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="perihal" :value="__('Perihal')" />
                                <x-text-input id="perihal" class="block mt-1 w-full" type="text" name="perihal"
                                    :value="old('perihal')" required />
                                <x-input-error :messages="$errors->get('perihal')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="lampiran" :value="__('Lampiran')" />
                                <x-text-input id="lampiran" class="block mt-1 w-full" type="text" name="lampiran"
                                    :value="old('lampiran')" required />
                                <x-input-error :messages="$errors->get('lampiran')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="order" :value="__('Order')" />
                                <x-text-input id="order" class="block mt-1 w-full" type="text" name="order"
                                    :value="old('order')" />
                                <x-input-error :messages="$errors->get('order')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="badan_surat" :value="__('Badan Surat / Isi Memo')" />
                                <textarea id="badan_surat" name="badan_surat"
                                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    rows="6">{{ old('badan_surat') }}</textarea>
                                <x-input-error :messages="$errors->get('badan_surat')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end">
                                <x-primary-button type="submit">
                                    {{ __('Buat Memo') }}
                                </x-primary-button>
                            </div>

                        </form>



                        {{-- ===================================================== --}}
                        {{-- ================= FORM SURAT KELUAR ================== --}}
                        {{-- ===================================================== --}}
                        <form x-show="selectedType==='surat_keluar'" method="POST"
                            action="{{ route('dokumen.store.backdate.surat') }}" class="space-y-6">

                            @csrf
                            <input type="hidden" name="jenis_dokumen" value="surat_keluar">

                            <div>
                                <x-input-label value="Tanggal Backdate" />
                                <x-text-input type="date" name="tanggal_backdate"
                                    max="{{ \Carbon\Carbon::yesterday()->toDateString() }}" required class="w-full" />
                            </div>

                            <div>
                                <x-input-label value="Jenis Surat" />
                                <x-select-input name="kode_surat" class="w-full" required>
                                    <option value="">-- Pilih Jenis Surat --</option>
                                    @foreach ($kodeSurat as $kode)
                                        <option value="{{ $kode }}">{{ $kode }}</option>
                                    @endforeach
                                </x-select-input>
                            </div>

                            <div>
                                <x-input-label value="Tujuan (Nama)" />
                                <x-text-input name="tNama" class="block mt-1 w-full" :value="old('tNama')" required />
                            </div>

                            <div>
                                <x-input-label value="Tujuan (Jabatan)" />
                                <x-text-input name="tJabatan" class="block mt-1 w-full" :value="old('tJabatan')" required />
                            </div>

                            <div>
                                <x-input-label value="Tujuan (Divisi)" />
                                <x-text-input name="tujuan" class="block mt-1 w-full" :value="old('tujuan')" required />
                            </div>

                            <div>
                                <x-input-label value="Tujuan (Perusahaan)" />
                                <x-text-input name="tPerusahaan" class="block mt-1 w-full" :value="old('tPerusahaan')"
                                    required />
                            </div>

                            {{-- Dari --}}
                            <div>
                                <x-input-label for="dari" :value="__('Dari')" />
                                <x-select-input id="dari" class="block mt-1 w-full" name="dari"
                                    :value="old('dari')" required>
                                    <option value="" disabled selected>-- Pilih dari --</option>
                                    @foreach ($daris as $dari)
                                        <option value="{{ $dari }}"
                                            {{ old('dari') == $dari ? 'selected' : '' }}>
                                            {{ $dari }}
                                        </option>
                                    @endforeach
                                </x-select-input>
                                <x-input-error :messages="$errors->get('dari')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label :value="__('Tembusan')" />

                                @for ($i = 0; $i < 3; $i++)
                                    <x-select-input name="tembusan[]" class="block mt-2 w-full">

                                        <option value="">-- Pilih tembusan {{ $i + 1 }} --</option>

                                        @foreach ($tembusans as $tembusan)
                                            <option value="{{ $tembusan }}"
                                                {{ old('tembusan.' . $i) == $tembusan ? 'selected' : '' }}>
                                                {{ $tembusan }}
                                            </option>
                                        @endforeach

                                    </x-select-input>
                                @endfor
                            </div>

                            <div>
                                <x-input-label for="perihal" :value="__('Perihal')" />
                                <x-text-input id="perihal" class="block mt-1 w-full" type="text" name="perihal"
                                    :value="old('perihal')" required />
                                <x-input-error :messages="$errors->get('perihal')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="lampiran" :value="__('Lampiran')" />
                                <x-text-input id="lampiran" class="block mt-1 w-full" type="text"
                                    name="lampiran" :value="old('lampiran')" required />
                                <x-input-error :messages="$errors->get('lampiran')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="order" :value="__('Order')" />
                                <x-text-input id="order" class="block mt-1 w-full" type="text" name="order"
                                    :value="old('order')" />
                                <x-input-error :messages="$errors->get('order')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="badan_surat" :value="__('Badan Surat / Isi Memo')" />
                                <textarea id="badan_surat" name="badan_surat"
                                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    rows="6">{{ old('badan_surat') }}</textarea>
                                <x-input-error :messages="$errors->get('badan_surat')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end">
                                <x-primary-button type="submit">
                                    {{ __('Buat Surat') }}
                                </x-primary-button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>



    {{-- ================= Copy Script ================= --}}
    <script>
        function copyNomorSurat(nomor) {
            const input = document.createElement('input');
            input.value = nomor;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);

            const toast = document.createElement('div');
            toast.textContent = 'Nomor surat disalin';
            toast.className = 'fixed bottom-5 right-5 bg-green-600 text-white px-4 py-2 rounded shadow';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2000);
        }
    </script>

    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let badanEditor;
            ClassicEditor
                .create(document.querySelector('#badan_surat'), {
                    toolbar: [
                        'heading',
                        '|',
                        'bold',
                        'italic',
                        'underline',
                        '|',
                        'bulletedList',
                        'numberedList',
                        '|',
                        'alignment',
                        '|',
                        'undo',
                        'redo'
                    ]
                })
                .then(editor => {
                    badanEditor = editor;
                    document.querySelector('form').addEventListener('submit', function() {
                        document.querySelector('#badan_surat').value = badanEditor.getData();
                    });
                })
                .catch(error => {
                    console.error(error);
                });
        });
    </script>

</x-app-layout>
