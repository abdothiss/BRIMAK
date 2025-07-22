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
    //  SECTION 2: Profile Page Modal Logic (NEW & CORRECT)
    // ==========================================================
    const openNameBtn = document.getElementById('open-name-modal-btn');
    const openUsernameBtn = document.getElementById('open-username-modal-btn');
    const openPasswordBtn = document.getElementById('open-password-modal-btn');
    
    const nameModal = document.getElementById('change-name-modal');
    const usernameModal = document.getElementById('change-username-modal');
    const passwordModal = document.getElementById('change-password-modal');

    openNameBtn?.addEventListener('click', () => nameModal?.classList.remove('hidden'));
    openUsernameBtn?.addEventListener('click', () => usernameModal?.classList.remove('hidden'));
    openPasswordBtn?.addEventListener('click', () => passwordModal?.classList.remove('hidden'));

    // A single function to close ALL profile modals
    document.querySelectorAll('.close-modal-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            nameModal?.classList.add('hidden');
            usernameModal?.classList.add('hidden');
            passwordModal?.classList.add('hidden');
        });
    });
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

