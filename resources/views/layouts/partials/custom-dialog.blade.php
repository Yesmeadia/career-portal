{{-- Global Custom Confirmation & Alert Dialog --}}
<div id="global-custom-dialog"
     class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs transition-opacity duration-200 hidden opacity-0"
     style="display: none;"
     role="dialog"
     aria-modal="true">

    <div id="global-dialog-box"
         class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-gray-100 transform transition-all duration-200 scale-95 p-6 text-center">
        
        {{-- Icon Container --}}
        <div id="global-dialog-icon-wrap"
             class="w-16 h-16 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4 border border-red-100 shadow-xs">
            <span id="global-dialog-icon" class="material-symbols-outlined text-[34px]">delete_forever</span>
        </div>

        {{-- Dialog Title --}}
        <h3 id="global-dialog-title" class="text-xl font-extrabold text-[#111827] mb-2 leading-tight">
            Confirm Action
        </h3>
        
        {{-- Dialog Message --}}
        <p id="global-dialog-message" class="text-xs text-gray-500 leading-relaxed mb-6 font-medium">
            Are you sure you want to proceed with this action?
        </p>

        {{-- Action Buttons Container --}}
        <div class="flex items-center justify-center gap-3">
            <button type="button"
                    id="global-dialog-cancel-btn"
                    class="flex-1 py-2.5 px-4 rounded-full border border-gray-200 text-gray-700 text-xs font-bold hover:bg-gray-50 active:scale-95 transition-all cursor-pointer">
                Cancel
            </button>
            <button type="button"
                    id="global-dialog-confirm-btn"
                    class="flex-1 py-2.5 px-5 rounded-full bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition-all shadow-md active:scale-95 cursor-pointer">
                Yes, Delete
            </button>
        </div>
    </div>
</div>

<script>
(function () {
    const dialogEl = document.getElementById('global-custom-dialog');
    const boxEl = document.getElementById('global-dialog-box');
    const iconWrapEl = document.getElementById('global-dialog-icon-wrap');
    const iconEl = document.getElementById('global-dialog-icon');
    const titleEl = document.getElementById('global-dialog-title');
    const messageEl = document.getElementById('global-dialog-message');
    const cancelBtn = document.getElementById('global-dialog-cancel-btn');
    const confirmBtn = document.getElementById('global-dialog-confirm-btn');

    let currentResolve = null;

    function openDialog({ title = 'Confirm Action', message = 'Are you sure?', confirmText = 'Confirm', cancelText = 'Cancel', type = 'danger', isAlert = false }) {
        return new Promise((resolve) => {
            currentResolve = resolve;

            titleEl.textContent = title;
            messageEl.innerHTML = message;
            confirmBtn.textContent = confirmText;
            cancelBtn.textContent = cancelText;

            // Configure Styling by Type
            if (type === 'danger') {
                iconWrapEl.className = 'w-16 h-16 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4 border border-red-100 shadow-xs';
                iconEl.textContent = 'delete_forever';
                confirmBtn.className = 'flex-1 py-2.5 px-5 rounded-full bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition-all shadow-md active:scale-95 cursor-pointer';
            } else if (type === 'warning') {
                iconWrapEl.className = 'w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-4 border border-amber-100 shadow-xs';
                iconEl.textContent = 'warning';
                confirmBtn.className = 'flex-1 py-2.5 px-5 rounded-full bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold transition-all shadow-md active:scale-95 cursor-pointer';
            } else if (type === 'info') {
                iconWrapEl.className = 'w-16 h-16 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center mx-auto mb-4 border border-blue-100 shadow-xs';
                iconEl.textContent = 'info';
                confirmBtn.className = 'flex-1 py-2.5 px-5 rounded-full bg-[#21255E] hover:bg-[#1a1d4b] text-white text-xs font-bold transition-all shadow-md active:scale-95 cursor-pointer';
            } else {
                iconWrapEl.className = 'w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center mx-auto mb-4 border border-emerald-100 shadow-xs';
                iconEl.textContent = 'check_circle';
                confirmBtn.className = 'flex-1 py-2.5 px-5 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-md active:scale-95 cursor-pointer';
            }

            if (isAlert) {
                cancelBtn.style.display = 'none';
                confirmBtn.className = 'w-full py-2.5 px-6 rounded-full bg-[#21255E] text-white text-xs font-bold transition-all shadow-md active:scale-95 cursor-pointer';
            } else {
                cancelBtn.style.display = 'block';
            }

            // Show Dialog
            dialogEl.style.display = 'flex';
            requestAnimationFrame(() => {
                dialogEl.classList.remove('opacity-0');
                boxEl.classList.remove('scale-95');
                boxEl.classList.add('scale-100');
            });
        });
    }

    function closeDialog(result) {
        dialogEl.classList.add('opacity-0');
        boxEl.classList.remove('scale-100');
        boxEl.classList.add('scale-95');

        setTimeout(() => {
            dialogEl.style.display = 'none';
            if (currentResolve) {
                currentResolve(result);
                currentResolve = null;
            }
        }, 150);
    }

    confirmBtn.addEventListener('click', () => closeDialog(true));
    cancelBtn.addEventListener('click', () => closeDialog(false));
    dialogEl.addEventListener('click', (e) => {
        if (e.target === dialogEl) closeDialog(false);
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && dialogEl.style.display === 'flex') {
            closeDialog(false);
        }
    });

    // Expose API on Window
    window.confirmModal = function (options) {
        if (typeof options === 'string') {
            options = { message: options };
        }
        return openDialog({
            title: options.title || 'Confirm Action',
            message: options.message || 'Are you sure you want to proceed? This action cannot be undone.',
            confirmText: options.confirmText || 'Yes, Delete',
            cancelText: options.cancelText || 'Cancel',
            type: options.type || 'danger',
            isAlert: false
        });
    };

    window.alertModal = function (options) {
        if (typeof options === 'string') {
            options = { message: options };
        }
        return openDialog({
            title: options.title || 'Notice',
            message: options.message || '',
            confirmText: options.confirmText || 'OK',
            type: options.type || 'info',
            isAlert: true
        });
    };

    // Override browser native alert
    window.alert = function (msg) {
        window.alertModal({ message: msg });
    };

    // Automatically convert any onsubmit="return confirm(...)" into custom modal data-confirm
    function sanitizeForms() {
        document.querySelectorAll('form').forEach(form => {
            const onsubmitAttr = form.getAttribute('onsubmit');
            if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
                const match = onsubmitAttr.match(/confirm\(\s*['"`](.*?)['"`]\s*\)/);
                if (match && match[1]) {
                    const cleanMsg = match[1].replace(/\\'/g, "'").replace(/\\"/g, '"');
                    form.setAttribute('data-confirm', cleanMsg);
                } else {
                    form.setAttribute('data-confirm', 'Are you sure you want to perform this action?');
                }
                form.removeAttribute('onsubmit');
                form.onsubmit = null;
            }
        });
    }

    // Run on load and whenever DOM changes
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', sanitizeForms);
    } else {
        sanitizeForms();
    }

    const observer = new MutationObserver(() => sanitizeForms());
    observer.observe(document.body, { childList: true, subtree: true });

    // Global submit handler for data-confirm forms
    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (form._customConfirmed) {
            return; // Proceed if user already confirmed via custom modal
        }

        const dataConfirm = form.getAttribute('data-confirm');
        if (dataConfirm) {
            e.preventDefault();
            e.stopImmediatePropagation();

            window.confirmModal({
                title: form.getAttribute('data-confirm-title') || 'Confirm Action',
                message: dataConfirm,
                confirmText: form.getAttribute('data-confirm-btn') || 'Yes, Delete',
                type: 'danger'
            }).then((confirmed) => {
                if (confirmed) {
                    form._customConfirmed = true;
                    HTMLFormElement.prototype.submit.call(form);
                }
            });
        }
    }, true);
})();
</script>
