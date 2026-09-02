<div x-data="toastManager()" 
     @toast.window="addToast($event.detail)" 
     class="fixed top-5 right-5 z-50 flex flex-col gap-3 w-full max-w-sm pointer-events-none">
    
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible"
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90"
             class="pointer-events-auto flex items-start gap-3 rounded-2xl border p-4 shadow-2xl backdrop-blur-md transition"
             :class="{
                 'border-emerald-500/50 bg-slate-900/95 text-emerald-100 shadow-emerald-950/40': toast.type === 'success',
                 'border-rose-500/50 bg-slate-900/95 text-rose-100 shadow-rose-950/40': toast.type === 'error',
                 'border-amber-500/50 bg-slate-900/95 text-amber-100 shadow-amber-950/40': toast.type === 'warning',
                 'border-blue-500/50 bg-slate-900/95 text-blue-100 shadow-blue-950/40': toast.type === 'info'
             }">
            
            <!-- Toast Icon -->
            <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-xl font-bold shadow-sm"
                 :class="{
                     'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30': toast.type === 'success',
                     'bg-rose-500/20 text-rose-400 border border-rose-500/30': toast.type === 'error',
                     'bg-amber-500/20 text-amber-400 border border-amber-500/30': toast.type === 'warning',
                     'bg-blue-500/20 text-blue-400 border border-blue-500/30': toast.type === 'info'
                 }">
                <template x-if="toast.type === 'success'">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </template>
                <template x-if="toast.type === 'error'">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </template>
                <template x-if="toast.type === 'warning'">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </template>
                <template x-if="toast.type === 'info'">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </template>
            </div>

            <!-- Toast Content -->
            <div class="flex-1 min-w-0">
                <b class="block text-xs font-bold uppercase tracking-wider text-white" x-text="toast.title"></b>
                <p class="mt-0.5 text-xs text-slate-300 leading-relaxed break-words" x-text="toast.message"></p>
            </div>

            <!-- Close Button -->
            <button type="button" @click="removeToast(toast.id)" class="shrink-0 text-slate-400 hover:text-white transition p-1">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </template>
</div>

<script>
function toastManager() {
    return {
        toasts: [],
        init() {
            @if(session('success'))
                this.addToast({ type: 'success', title: 'Berhasil', message: {{ Js::from(session('success')) }} });
            @endif

            @if(session('error'))
                this.addToast({ type: 'error', title: 'Gagal', message: {{ Js::from(session('error')) }} });
            @endif

            @if(session('warning'))
                this.addToast({ type: 'warning', title: 'Peringatan', message: {{ Js::from(session('warning')) }} });
            @endif

            @if(session('info'))
                this.addToast({ type: 'info', title: 'Informasi', message: {{ Js::from(session('info')) }} });
            @endif

            @if($errors->any())
                this.addToast({ type: 'error', title: 'Validasi Gagal', message: {{ Js::from($errors->first()) }} });
            @endif
        },

        addToast({ type = 'info', title = null, message = '' }) {
            if (!title) {
                title = type === 'success' ? 'Berhasil' : (type === 'error' ? 'Gagal' : 'Pemberitahuan');
            }

            const id = Date.now() + Math.random().toString(36).substr(2, 4);
            const toast = { id, type, title, message, visible: true };
            this.toasts.push(toast);

            setTimeout(() => {
                this.removeToast(id);
            }, 4500);
        },

        removeToast(id) {
            const index = this.toasts.findIndex(t => t.id === id);
            if (index !== -1) {
                this.toasts[index].visible = false;
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 300);
            }
        }
    };
}
</script>
