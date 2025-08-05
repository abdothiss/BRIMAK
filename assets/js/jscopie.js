document.addEventListener('DOMContentLoaded', function () {

    // ==========================================================
    // SECTION 1: GLOBAL NAVIGATION & MENUS
    // ==========================================================
    
    // Slide-Out Menu Logic
    const menuBtn = document.getElementById('menu-btn');
    const menuPanel = document.getElementById('menu-panel');
    const menuOverlay = document.getElementById('menu-overlay');
    const body = document.body;
    function toggleMenu() {
        if (menuPanel && menuOverlay) {
            menuPanel.classList.toggle('-translate-x-full');
            menuOverlay.classList.toggle('hidden');
            body.classList.toggle('overflow-hidden');
        }
    }
    menuBtn?.addEventListener('click', toggleMenu);
    menuOverlay?.addEventListener('click', toggleMenu);

    // Command Card "Three Dots" Menu Logic
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.command-menu-container')) {
            document.querySelectorAll('.command-menu-panel').forEach(p => p.classList.add('hidden'));
        }
    });
    document.querySelectorAll('.command-menu-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const panel = btn.nextElementSibling;
            const isHidden = panel.classList.contains('hidden');
            document.querySelectorAll('.command-menu-panel').forEach(p => p.classList.add('hidden'));
            if (isHidden) panel.classList.remove('hidden');
        });
    });

    // ==========================================================
    // SECTION 2: ALL MODAL LOGIC FOR THE ENTIRE SITE
    // ==========================================================

    // --- Progress & Cancel Modals (from Three Dots Menu) ---
    const progressModal = document.getElementById('view-progress-modal');
    const cancelModal = document.getElementById('cancel-command-modal');
    const iconCheckTemplate = document.getElementById('template-icon-check')?.firstElementChild;
    const iconClockTemplate = document.getElementById('template-icon-clock')?.firstElementChild;
    
    document.querySelectorAll('.open-progress-modal-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (!iconCheckTemplate || !iconClockTemplate) { return; }
            const workflow = JSON.parse(btn.dataset.workflow || '[]');
            const completed = JSON.parse(btn.dataset.completed || '[]');
            const uid = btn.dataset.commandUid;
            const container = document.getElementById('progress-steps-container');
            const title = document.getElementById('progress-modal-title');
            if(title) title.textContent = `Progress for ${uid}`;
            if(container) container.innerHTML = '';
            workflow.forEach(step => {
                const isDone = completed.includes(step);
                const icon = (isDone ? iconCheckTemplate.cloneNode(true) : iconClockTemplate.cloneNode(true));
                const textClass = isDone ? 'font-bold text-gray-800' : 'text-gray-500';
                container.innerHTML += `<div class="flex items-center space-x-3"><div class="flex-shrink-0">${icon.outerHTML}</div><p class="${textClass}">${step}</p></div>`;
            });
            progressModal?.classList.remove('hidden');
        });
    });

    document.querySelectorAll('.open-cancel-modal-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('cancel-command-id').value = btn.dataset.commandId;
            document.getElementById('cancel-command-uid').textContent = btn.dataset.commandUid;
            cancelModal?.classList.remove('hidden');
        });
    });

    // --- History Page Modals ---
    const deleteOneModal = document.getElementById('delete-one-modal');
    if (deleteOneModal) {
        document.querySelectorAll('.open-delete-one-modal-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('delete-one-id').value = btn.dataset.commandId;
                document.getElementById('delete-one-uid').textContent = btn.dataset.commandUid;
                deleteOneModal.classList.remove('hidden');
            });
        });
    }
    const deleteAllModal = document.getElementById('delete-all-modal');
    const deleteAllForm = document.getElementById('delete-all-form');
    document.getElementById('open-delete-all-modal-btn')?.addEventListener('click', () => {
        deleteAllModal?.classList.remove('hidden');
    });
    // This makes the custom modal work with a form
    deleteAllModal?.querySelector('.confirm-delete-all-btn')?.addEventListener('click', () => {
        deleteAllForm?.submit();
    });

    

    // --- Admin User Management Modals ---
    const userModal = document.getElementById('user-modal');
    if (userModal) {
        const userForm = document.getElementById('user-form');
        const userModalTitle = document.getElementById('user-modal-title');
        const userFormAction = document.getElementById('user-form-action');
        const userFormId = document.getElementById('user-form-id');
        const passwordContainer = document.getElementById('password-field-container');
        const passwordInput = document.getElementById('user-form-password');
        document.getElementById('add-user-btn')?.addEventListener('click', () => { userForm.reset(); userModalTitle.textContent = 'Add New User'; userFormAction.value = 'add_user'; userFormId.value = ''; passwordContainer.style.display = 'block'; passwordInput.required = true; userModal.classList.remove('hidden'); });
        document.querySelectorAll('.edit-user-btn').forEach(btn => { btn.addEventListener('click', e => { const userData = JSON.parse(e.currentTarget.dataset.user); userForm.reset(); userModalTitle.textContent = 'Edit User'; userFormAction.value = 'edit_user'; userFormId.value = userData.id; document.getElementById('user-form-name').value = userData.name; document.getElementById('user-form-username').value = userData.username; document.getElementById('user-form-role').value = userData.role; document.getElementById('user-form-section').value = userData.section || 'null'; passwordContainer.style.display = 'none'; passwordInput.required = false; userModal.classList.remove('hidden'); }); });
    }
    const deleteUserModal = document.getElementById('delete-user-modal');
    if(deleteUserModal) { document.querySelectorAll('.delete-user-btn').forEach(btn => { btn.addEventListener('click', e => { document.getElementById('delete-user-id').value = e.currentTarget.dataset.userid; document.getElementById('delete-username').textContent = e.currentTarget.dataset.username; deleteUserModal.classList.remove('hidden'); }); }); }
    const resetPwModal = document.getElementById('reset-pw-modal');
    if(resetPwModal) { document.querySelectorAll('.reset-pw-btn').forEach(btn => { btn.addEventListener('click', e => { document.getElementById('reset-pw-id').value = e.currentTarget.dataset.userid; document.getElementById('reset-pw-username').textContent = e.currentTarget.dataset.username; resetPwModal.classList.remove('hidden'); }); }); }
    
    // --- Commercial Command Modal ---
    const commandModal = document.getElementById('command-modal');
    if (commandModal) {
        const modalTitle = document.getElementById('modal-title');
        const commandForm = document.getElementById('command-form');
        const formAction = document.getElementById('form-action');
        const formCommandId = document.getElementById('form-command-id');
        document.getElementById('open-create-modal-btn')?.addEventListener('click', () => { commandForm.reset(); modalTitle.textContent = 'Create New Command'; formAction.value = 'create'; formCommandId.value = ''; commandModal.classList.remove('hidden'); });
        document.querySelectorAll('.edit-command-btn').forEach(btn => { btn.addEventListener('click', (e) => { const commandData = JSON.parse(e.currentTarget.dataset.command); commandForm.reset(); modalTitle.textContent = 'Modify & Resend Command'; formAction.value = 'update'; formCommandId.value = commandData.id; document.getElementById('form-type').value = commandData.type; document.getElementById('form-dimensions').value = commandData.dimensions; document.getElementById('form-quantity').value = commandData.quantity; document.getElementById('form-delivery-date').value = commandData.delivery_date; document.getElementById('form-client-name').value = commandData.client_name; document.getElementById('form-client-phone').value = commandData.client_phone; document.getElementById('form-additional-notes').value = commandData.additional_notes || ''; commandModal.classList.remove('hidden'); }); });
    }

    // --- Chef Decline Modal ---
    const declineModal = document.getElementById('decline-modal');
    if (declineModal) {
        const title = document.getElementById('decline-modal-title');
        const commandIdInput = document.getElementById('decline-command-id');
        document.querySelectorAll('.open-decline-modal').forEach(btn => { btn.addEventListener('click', e => { const commandId = e.currentTarget.dataset.commandId; const commandUid = e.currentTarget.dataset.commandUid; title.textContent = `Decline Command ${commandUid}`; commandIdInput.value = commandId; declineModal.classList.remove('hidden'); }); });
    }
    
    // --- Settings Page "Edit" Logic ---
    function setupEditToggle(editBtnId, displayId, formId){ const editBtn = document.getElementById(editBtnId); const displayEl = document.getElementById(displayId); const formEl = document.getElementById(formId); const cancelBtn = formEl?.querySelector('.cancel-btn'); editBtn?.addEventListener('click', () => { displayEl?.classList.add('hidden'); formEl?.classList.remove('hidden'); }); cancelBtn?.addEventListener('click', () => { displayEl?.classList.remove('hidden'); formEl?.classList.add('hidden'); }); }
    setupEditToggle('edit-name-btn', 'display-name', 'form-name');
    setupEditToggle('edit-username-btn', 'display-username', 'form-username');
    setupEditToggle('edit-password-btn', 'display-password', 'form-password');

    // --- Universal Close Button Logic for ALL modals ---
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', (e) => {
            // If the user clicks on a button with a "close-*" class OR the dark background overlay itself
            if (e.target.matches('[class*="close-"]') || e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
});