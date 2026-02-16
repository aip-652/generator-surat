<x-app-layout>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">

                    <div class="flex items-center mb-8">
                        <a href="{{ route('dashboard') }}"
                            class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-arrow-left"></i>
                        </a>

                        <h2 class="text-xl font-bold ml-4">
                            Edit Surat Keluar : {{ $dokumen->nomor_dokumen }}
                        </h2>
                    </div>

                    <form action="{{ route('dokumen.update', $dokumen) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Tujuan --}}
                        <div>
                            <x-input-label value="Tujuan (Nama)" />
                            <x-text-input name="tNama" class="block mt-1 w-full" :value="old('tNama', $dokumen->tNama)" />
                        </div>

                        <div>
                            <x-input-label value="Tujuan (Jabatan)" />
                            <x-text-input name="tJabatan" class="block mt-1 w-full" :value="old('tJabatan', $dokumen->tJabatan)" />
                        </div>

                        <div>
                            <x-input-label value="Tujuan (Divisi)" />
                            <x-text-input name="tujuan" class="block mt-1 w-full" :value="old('tujuan', $dokumen->tujuan)" />
                        </div>

                        <div>
                            <x-input-label value="Tujuan (Perusahaan)" />
                            <x-text-input name="tPerusahaan" class="block mt-1 w-full" :value="old('tPerusahaan', $dokumen->tPerusahaan)" />
                        </div>

                        {{-- Dari --}}
                        <div>
                            <x-input-label value="Dari" />
                            <x-select-input name="dari" class="w-full">

                                <option value="">-- Pilih --</option>

                                @foreach ($daris as $dari)
                                    <option value="{{ $dari }}"
                                        {{ old('dari', $dokumen->dari) == $dari ? 'selected' : '' }}>
                                        {{ $dari }}
                                    </option>
                                @endforeach

                            </x-select-input>
                        </div>

                        {{-- TEMBUSAN --}}
                        <div>
                            <x-input-label value="Tembusan (pisahkan dengan koma)" />

                            <x-text-input name="tembusan" class="block mt-1 w-full" :value="old('tembusan', $dokumen->tembusan)" />

                        </div>

                        {{-- Perihal --}}
                        <div>
                            <x-input-label value="Perihal" />
                            <x-text-input name="perihal" class="block mt-1 w-full" :value="old('perihal', $dokumen->perihal)" required />
                        </div>

                        {{-- Lampiran --}}
                        <div>
                            <x-input-label value="Lampiran" />
                            <x-text-input name="lampiran" class="block mt-1 w-full" :value="old('lampiran', $dokumen->lampiran)" />
                        </div>

                        {{-- Order --}}
                        <div>
                            <x-input-label value="Order" />
                            <x-text-input name="order" class="block mt-1 w-full" :value="old('order', $dokumen->order)" />
                        </div>

                        {{-- Badan Surat --}}
                        <div>
                            <x-input-label value="Badan Surat" />
                            <textarea id="badan_surat" name="badan_surat" class="block mt-1 w-full border-gray-300 rounded-md" rows="6">{{ old('badan_surat', $dokumen->badan_surat) }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <x-primary-button>Simpan Perubahan</x-primary-button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- CKEditor --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {

            ClassicEditor.create(document.querySelector('#badan_surat'))
                .then(editor => {

                    document.querySelector('form').addEventListener('submit', () => {
                        document.querySelector('#badan_surat').value = editor.getData();
                    });

                });

        });
    </script>

</x-app-layout>
