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
                            Edit Memo : {{ $dokumen->nomor_dokumen }}
                        </h2>
                    </div>

                    <form action="{{ route('dokumen.update', $dokumen) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Tujuan --}}
                        <div>
                            <x-input-label value="Tujuan" />
                            <x-select-input name="tujuan" class="w-full">

                                <option value="">-- Pilih --</option>

                                @foreach ($tujuans as $tujuan)
                                    <option value="{{ $tujuan }}"
                                        {{ old('tujuan', $dokumen->tujuan) == $tujuan ? 'selected' : '' }}>
                                        {{ $tujuan }}
                                    </option>
                                @endforeach

                            </x-select-input>
                        </div>

                        {{-- Tembusan --}}
                        <div>
                            <x-input-label value="tembusan" />
                            <x-select-input name="tembusan" class="w-full">

                                <option value="">-- Pilih --</option>

                                @foreach ($tembusans as $tembusan)
                                    <option value="{{ $tembusan }}"
                                        {{ old('tembusan', $dokumen->tembusan) == $tembusan ? 'selected' : '' }}>
                                        {{ $tembusan }}
                                    </option>
                                @endforeach

                            </x-select-input>
                        </div>

                        {{-- Perihal --}}
                        <div>
                            <x-input-label value="Perihal" />
                            <x-text-input name="perihal" class="block mt-1 w-full" :value="old('perihal', $dokumen->perihal)" required />
                        </div>

                        {{-- Order --}}
                        <div>
                            <x-input-label value="Order" />
                            <x-text-input name="order" class="block mt-1 w-full" :value="old('order', $dokumen->order)" />
                        </div>

                        {{-- Badan Surat --}}
                        <div>
                            <x-input-label value="Isi Memo" />
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
