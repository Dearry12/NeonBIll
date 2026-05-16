<dialog
    id="delete-confirm-modal"
    class="mx-auto w-[calc(100%-2rem)] max-w-md rounded-xl border border-slate-700 bg-slate-800 p-5 text-slate-100 shadow-2xl neon-border-cyan backdrop:bg-black/70 sm:p-6"
>
    <h2 class="text-lg font-semibold text-white">Delete subscription?</h2>
    <p class="mt-2 text-sm text-slate-400">
        Are you sure you want to delete <span id="delete-subscription-name" class="font-medium text-cyan-300"></span>?
        This action cannot be undone.
    </p>
    <form id="delete-confirm-form" method="POST" class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        @csrf
        @method('DELETE')
        <button
            type="button"
            id="delete-modal-cancel"
            class="min-h-11 rounded-lg border border-slate-600 px-4 py-2.5 text-sm text-slate-300 hover:border-slate-500 hover:text-white touch-manipulation"
        >
            Cancel
        </button>
        <x-neon-button type="submit" variant="danger">
            Delete
        </x-neon-button>
    </form>
</dialog>
