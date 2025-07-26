document.addEventListener('DOMContentLoaded', function () {

    // ==========================================================
    //  SECTION 1: Slide-Out Menu Logic
    // ==========================================================
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
    
    // ==========================================================
    // SECTION 2: NEW History Page Logic
    // ==========================================================
    // Logic to open/close history drawers
    document.querySelectorAll('.history-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.dataset.target;
            const content = document.getElementById(targetId);
            const chevron = button.querySelector('.chevron-icon');
            
            content?.classList.toggle('hidden');
            chevron?.classList.toggle('rotate-90');
        });
    });

    // Logic for the custom "Delete All" confirmation modal
    const deleteAllModal = document.getElementById('delete-all-modal');
    document.getElementById('open-delete-all-modal-btn')?.addEventListener('click', () => {
        deleteAllModal?.classList.remove('hidden');
    });

    // Logic for the custom "Delete One" confirmation modal
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

    // Universal close button for the new history modals
    document.querySelectorAll('.close-history-modal-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            deleteOneModal?.classList.add('hidden');
            deleteAllModal?.classList.add('hidden');
        });
    });

    
    // ==========================================================
    //  SECTION 2: Logic for the NEW Settings Page
    // ==========================================================
    function setupEditToggle(editBtnId, displayId, formId) {
        const editBtn = document.getElementById(editBtnId);
        const displayEl = document.getElementById(displayId);
        const formEl = document.getElementById(formId);
        const cancelBtn = formEl?.querySelector('.cancel-btn');

        editBtn?.addEventListener('click', () => {
            displayEl?.classList.add('hidden');
            formEl?.classList.remove('hidden');
        });

        cancelBtn?.addEventListener('click', () => {
            displayEl?.classList.remove('hidden');
            formEl?.classList.add('hidden');
        });
    }

    setupEditToggle('edit-name-btn', 'display-name', 'form-name');
    setupEditToggle('edit-username-btn', 'display-username', 'form-username');
    setupEditToggle('edit-password-btn', 'display-password', 'form-password');

    // --- Dark Mode Toggle (Non-functional, but interactive) ---
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function() {
            this.classList.toggle('bg-green-500'); // Toggles between gray and green
            const circle = this.querySelector('div');
            circle.classList.toggle('translate-x-6'); // Slides the circle
        });
    }

    // ==========================================================
    //  SECTION 2: Logic for Modals That Actually Exist
    // ==========================================================

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
        document.querySelectorAll('.close-user-modal').forEach(btn => { btn.addEventListener('click', () => userModal.classList.add('hidden')); });
    }
    const deleteModal = document.getElementById('delete-user-modal');
    if(deleteModal) { document.querySelectorAll('.delete-user-btn').forEach(btn => { btn.addEventListener('click', e => { document.getElementById('delete-user-id').value = e.currentTarget.dataset.userid; document.getElementById('delete-username').textContent = e.currentTarget.dataset.username; deleteModal.classList.remove('hidden'); }); }); document.querySelectorAll('.close-delete-modal').forEach(btn => { btn.addEventListener('click', () => deleteModal.classList.add('hidden')); }); }
    const resetPwModal = document.getElementById('reset-pw-modal');
    if(resetPwModal) { document.querySelectorAll('.reset-pw-btn').forEach(btn => { btn.addEventListener('click', e => { document.getElementById('reset-pw-id').value = e.currentTarget.dataset.userid; document.getElementById('reset-pw-username').textContent = e.currentTarget.dataset.username; resetPwModal.classList.remove('hidden'); }); }); document.querySelectorAll('.close-reset-modal').forEach(btn => { btn.addEventListener('click', () => resetPwModal.classList.add('hidden')); }); }
    
    // --- Commercial Command Modal ---
    const commandModal = document.getElementById('command-modal');
    if (commandModal) {
        const modalTitle = document.getElementById('modal-title');
        const commandForm = document.getElementById('command-form');
        const formAction = document.getElementById('form-action');
        const formCommandId = document.getElementById('form-command-id');
        const closeCommandModal = () => commandModal.classList.add('hidden');
        document.getElementById('open-create-modal-btn')?.addEventListener('click', () => { commandForm.reset(); modalTitle.textContent = 'Create New Command'; formAction.value = 'create'; formCommandId.value = ''; commandModal.classList.remove('hidden'); });
        document.querySelectorAll('.edit-command-btn').forEach(btn => { btn.addEventListener('click', (e) => { const commandData = JSON.parse(e.currentTarget.dataset.command); commandForm.reset(); modalTitle.textContent = 'Modify & Resend Command'; formAction.value = 'update'; formCommandId.value = commandData.id; document.getElementById('form-type').value = commandData.type; document.getElementById('form-dimensions').value = commandData.dimensions; document.getElementById('form-quantity').value = commandData.quantity; document.getElementById('form-delivery-date').value = commandData.delivery_date; document.getElementById('form-client-name').value = commandData.client_name; document.getElementById('form-client-phone').value = commandData.client_phone; document.getElementById('form-additional-notes').value = commandData.additional_notes || ''; commandModal.classList.remove('hidden'); }); });
        document.querySelectorAll('.close-command-modal').forEach(btn => { btn.addEventListener('click', closeCommandModal); });
    }

    // --- Chef Decline Modal ---
    const declineModal = document.getElementById('decline-modal');
    if (declineModal) {
        const title = document.getElementById('decline-modal-title');
        const commandIdInput = document.getElementById('decline-command-id');
        document.querySelectorAll('.open-decline-modal').forEach(btn => { btn.addEventListener('click', e => { const commandId = e.currentTarget.dataset.commandId; const commandUid = e.currentTarget.dataset.commandUid; title.textContent = `Decline Command ${commandUid}`; commandIdInput.value = commandId; declineModal.classList.remove('hidden'); }); });
        document.querySelectorAll('.close-decline-modal').forEach(btn => { btn.addEventListener('click', () => declineModal.classList.add('hidden')); });
    }

    const profileSettingsBtn = document.getElementById('profile-settings-btn');
const profileSettingsForms = document.getElementById('profile-settings-forms');

if (profileSettingsBtn && profileSettingsForms) {
    profileSettingsBtn.addEventListener('click', function() {
        profileSettingsForms.classList.toggle('hidden');
    });
}
});

