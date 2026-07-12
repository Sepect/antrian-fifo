@props([
    'label' => 'Nama Pasien',
    'placeholder' => 'Ketik nama pasien...',
    'required' => false,
])

<div
    x-data="{
        query: '',
        results: [],
        selected: null,
        open: false,
        loading: false,
        highlighted: -1,
        timer: null,

        onInput() {
            this.selected = null;
            this.highlighted = -1;
            clearTimeout(this.timer);

            if (this.query.trim().length < 2) {
                this.results = [];
                this.loading = false;
                this.open = true;
                return;
            }

            this.loading = true;
            this.open = true;
            this.timer = setTimeout(() => this.fetchResults(), 250);
        },

        async fetchResults() {
            const term = this.query.trim();
            try {
                const response = await fetch('/patients/search?q=' + encodeURIComponent(term));
                if (!response.ok) throw new Error('gagal');
                this.results = await response.json();
            } catch (e) {
                this.results = [];
            } finally {
                this.loading = false;
            }
        },

        choose(patient) {
            this.selected = patient;
            this.query = patient.name;
            this.results = [];
            this.open = false;
            this.highlighted = -1;
        },

        clear() {
            this.selected = null;
            this.query = '';
            this.results = [];
            this.open = false;
            this.highlighted = -1;
            $refs.input.focus();
        },

        move(step) {
            if (!this.results.length) return;
            this.open = true;
            this.highlighted = (this.highlighted + step + this.results.length) % this.results.length;
        },

        pickHighlighted() {
            if (this.highlighted >= 0 && this.results[this.highlighted]) {
                this.choose(this.results[this.highlighted]);
            }
        },

        subtitle(patient) {
            return [patient.gender_label, patient.birth_date_label].filter(Boolean).join(' · ');
        },
    }"
    @click.outside="open = false"
    class="relative"
>
    <label class="block text-sm font-semibold text-slate-700 mb-1">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>

    <input type="hidden" name="patient_id" :value="selected ? selected.id : ''">

    <div class="relative">
        <input
            type="text"
            x-ref="input"
            x-model="query"
            @input="onInput()"
            @focus="open = true"
            @keydown.arrow-down.prevent="move(1)"
            @keydown.arrow-up.prevent="move(-1)"
            @keydown.enter.prevent="pickHighlighted()"
            @keydown.escape="open = false"
            autocomplete="off"
            placeholder="{{ $placeholder }}"
            :class="selected ? 'border-blue-500 pr-10' : 'border-slate-300 pr-10'"
            class="w-full px-4 py-3 rounded-md border bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-colors text-slate-800"
        >

        <button
            type="button"
            x-show="query.length > 0"
            @click="clear()"
            class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-700 transition-colors"
            aria-label="Hapus pilihan"
            style="display: none;"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <p x-show="selected" class="text-xs text-blue-600 mt-1 font-medium" style="display: none;">
        Pasien terpilih: <span x-text="selected ? selected.name : ''"></span>
    </p>
    <p x-show="!selected" class="text-xs text-slate-400 mt-1">
        Ketik nama Anda, lalu pilih dari daftar yang muncul.
    </p>

    <div
        x-show="open && !selected"
        x-transition.opacity
        class="absolute z-30 w-full mt-1 bg-white border border-slate-200 rounded-md shadow-lg max-h-64 overflow-y-auto"
        style="display: none;"
    >
        <template x-if="query.trim().length < 2">
            <p class="px-4 py-3 text-sm text-slate-400">Ketik minimal 2 huruf.</p>
        </template>

        <template x-if="query.trim().length >= 2 && loading">
            <p class="px-4 py-3 text-sm text-slate-400">Mencari...</p>
        </template>

        <template x-if="query.trim().length >= 2 && !loading && results.length === 0">
            <p class="px-4 py-3 text-sm text-slate-500">Pasien tidak ditemukan.</p>
        </template>

        <template x-for="(patient, index) in results" :key="patient.id">
            <button
                type="button"
                @click="choose(patient)"
                @mouseenter="highlighted = index"
                :class="highlighted === index ? 'bg-blue-50' : 'bg-white'"
                class="w-full text-left px-4 py-2.5 border-b border-slate-100 last:border-b-0 transition-colors"
            >
                <span class="block font-semibold text-slate-800" x-text="patient.name"></span>
                <span
                    x-show="subtitle(patient).length > 0"
                    class="block text-xs text-slate-500 mt-0.5"
                    x-text="subtitle(patient)"
                ></span>
            </button>
        </template>
    </div>
</div>
