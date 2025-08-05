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
                    const progressModal = document.getElementById('view-progress-modal');
                    const iconCheckTemplate = document.getElementById('template-icon-check')?.firstElementChild;
                    const iconClockTemplate = document.getElementById('template-icon-clock')?.firstElementChild;

                    if (!progressModal || !iconCheckTemplate || !iconClockTemplate) return;

                    // 1. Get ALL data attributes from the button
                    const workflow = JSON.parse(btn.dataset.workflow || '[]');
                    const completed = JSON.parse(btn.dataset.completed || '[]');
                    const translatedWorkflow = JSON.parse(btn.dataset.translatedWorkflow || '[]'); // The new data
                    const container = document.getElementById('progress-steps-container');

                    if (container) {
                        container.innerHTML = ''; // Clear previous content
                        
                        // 2. Loop through the ORIGINAL workflow for logic
                        workflow.forEach((step, index) => {
                            // 3. Check if the ORIGINAL step is in the COMPLETED array
                            const isDone = completed.includes(step);
                            const icon = (isDone ? iconCheckTemplate.cloneNode(true) : iconClockTemplate.cloneNode(true));
                            const textClass = isDone ? 'font-semibold text-gray-900' : 'text-gray-500';
                            
                            // 4. Get the corresponding TRANSLATED step name for display
                            const translatedStepName = translatedWorkflow[index] || step;

                            const stepHtml = `
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">${icon.outerHTML}</div>
                                    <p class="${textClass}">${translatedStepName}</p>
                                </div>
                            `;
                            container.innerHTML += stepHtml;
                        });
                    }
                    progressModal.classList.remove('hidden');
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

    // --- Admin User Management Modals ---
    // --- Admin User Management Modals ---
const userModal = document.getElementById('user-modal');
if (userModal) {
    // --- Get All Modal Elements ---
    const deleteUserModal = document.getElementById('delete-user-modal'); // <-- MISSING LINE
    const resetPwModal = document.getElementById('reset-pw-modal');       // <-- MISSING LINE

    const userForm = document.getElementById('user-form');
    const userModalTitle = document.getElementById('user-modal-title');
    const userFormAction = document.getElementById('user-form-action');
    const userFormId = document.getElementById('user-form-id');
    const passwordContainer = document.getElementById('password-field-container');
    const passwordInput = document.getElementById('user-form-password');
    const roleSelect = document.getElementById('user-form-role');

    // Get translated titles from the data attributes
    const addTitle = userModal.dataset.addTitle;
    const editTitle = userModal.dataset.editTitle;

    // Get the translated roles object from our hidden template
    const roleTranslations = JSON.parse(document.getElementById('role-translations')?.textContent || '{}');

    // --- Function to populate the role dropdown with translated roles ---
    function populateRoleSelect() {
        if (!roleSelect) return; // Add a safety check
        roleSelect.innerHTML = ''; // Clear existing options
        for (const role in roleTranslations) {
            const option = document.createElement('option');
            option.value = role; // The value is always the English key
            option.textContent = roleTranslations[role]; // The display text is translated
            roleSelect.appendChild(option);
        }
    }

    // Populate the dropdown when the page loads
    populateRoleSelect(); // Corrected function name


    // --- Event Listeners ---
    document.getElementById('add-user-btn')?.addEventListener('click', () => {
        userForm.reset();
        userModalTitle.textContent = addTitle; // USE TRANSLATED TITLE
        userFormAction.value = 'add_user';
        userFormId.value = '';
        passwordContainer.style.display = 'block';
        passwordInput.required = true;
        userModal.classList.remove('hidden');
    });

    document.querySelectorAll('.edit-user-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            const userData = JSON.parse(e.currentTarget.dataset.user);
            userForm.reset();
            userModalTitle.textContent = editTitle; // USE TRANSLATED TITLE
            userFormAction.value = 'edit_user';
            userFormId.value = userData.id;
            document.getElementById('user-form-name').value = userData.name;
            document.getElementById('user-form-username').value = userData.username;
            
            // Set the dropdown to the correct role
            if(roleSelect) roleSelect.value = userData.role;

            document.getElementById('user-form-section').value = userData.section || 'null';
            passwordContainer.style.display = 'none';
            passwordInput.required = false;
            userModal.classList.remove('hidden');
        });
    });

    // --- Logic for Delete Modal ---
    document.querySelectorAll('.delete-user-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            if (deleteUserModal) {
                document.getElementById('delete-user-id').value = e.currentTarget.dataset.userid;
                document.getElementById('delete-username').textContent = e.currentTarget.dataset.username;
                deleteUserModal.classList.remove('hidden');
            }
        });
    });

    // --- Logic for Reset Password Modal ---
    document.querySelectorAll('.reset-pw-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            if (resetPwModal) {
                document.getElementById('reset-pw-id').value = e.currentTarget.dataset.userid;
                document.getElementById('reset-pw-username').textContent = e.currentTarget.dataset.username;
                resetPwModal.classList.remove('hidden');
            }
        });
    });
}
    
    // --- Commercial Command Modal ---
    // --- Commercial Command Modal (Corrected for Translation) ---
        const commandModal = document.getElementById('command-modal');
        if (commandModal) {
            const modalTitle = document.getElementById('modal-title');
            const commandForm = document.getElementById('command-form');
            // ... other variables ...

            // Get the translated titles FROM the HTML data attributes
            const createTitle = commandModal.dataset.createTitle;
            const editTitle = commandModal.dataset.editTitle;

            // Logic for "Create" button
            document.getElementById('open-create-modal-btn')?.addEventListener('click', () => {
                commandForm.reset();
                modalTitle.textContent = createTitle; // <-- USES THE TRANSLATED VARIABLE
                // ... rest of the create logic ...
                commandModal.classList.remove('hidden');
            });

            // Logic for "Edit" buttons
            document.querySelectorAll('.edit-command-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    // ...
                    modalTitle.textContent = editTitle; // <-- USES THE TRANSLATED VARIABLE
                    // ... rest of the edit logic ...
                    commandModal.classList.remove('hidden');
                });
            });
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

    // --- NEW: Scroll to Top Button Logic ---
        const scrollTopBtn = document.getElementById('scroll-to-top-btn');

        // First, check if the button exists on the current page
        if (scrollTopBtn) {
            // Show or hide the button based on scroll position
            window.addEventListener('scroll', () => {
                if (window.scrollY > 300) { // Show the button after scrolling down 300px
                    scrollTopBtn.classList.remove('opacity-0', 'translate-y-4');
                } else { // Hide it when near the top
                    scrollTopBtn.classList.add('opacity-0', 'translate-y-4');
                }
            });

            // Handle the click event to scroll smoothly to the top
            scrollTopBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
        });