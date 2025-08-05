<?php
// lang/en.php
return [
    // Page Title
    'login_page_title' => 'BRIMAK Login',

    // Form Content
    'login_form_title' => 'Login',
    'login_form_welcome' => 'Welcome back, please login to your account.',
    'login_username_placeholder' => 'User Name',
    'login_password_placeholder' => 'Password',
    'login_remember_me' => 'Remember me',
    'login_button_text' => 'Login',

    // Page Title
    'page_title' => 'BRIMAK | Command Tracking',

    // Menu
    'menu_title' => 'Menu',
    'menu_dashboard' => 'Dashboard',
    'menu_settings' => 'Settings',
    'menu_user_management' => 'User Management',
    'menu_command_history' => 'Command History',
    'menu_logout' => 'Logout',

    // Footer
    'footer_address_title' => 'BRIMAK',
    'footer_contact_title' => 'Contact',
    'footer_fax_title' => 'Fax',
    'footer_rights_reserved' => 'All Rights Reserved',
    'footer_developed_by' => 'Developed by',

    // Delete History Modals
    'modal_delete_command_title' => 'Delete Command',
    'modal_delete_command_confirm' => 'Are you sure you want to permanently delete command',
    'modal_delete_all_title' => 'Delete All History',
    'modal_delete_all_confirm' => 'Are you sure you want to PERMANENTLY delete ALL command history? This action cannot be undone.',
    'modal_button_no' => 'No, keep it',
    'modal_button_yes_delete' => 'Yes, delete it',
    'modal_button_yes_delete_all' => 'Yes, delete all',
    'modal_undone_warning' => 'This action cannot be undone.',

    // Command Card & Drawer
    'command_id' => 'Command ID',
    'status' => 'Status',
    'reason_for_decline' => 'Reason for Decline',
    'type' => 'Type',
    'quantity' => 'Quantity',
    'quantity_unit' => 'bricks',
    'dimensions' => 'Dimensions',
    'delivery_date' => 'Delivery Date',
    'client_name' => 'Client Name',
    'client_phone' => 'Client Phone',
    'additional_notes' => 'Additional Notes',
    'delete_from_history' => 'Delete from my History',

    // Progress Bar
    'progress_waiting_for' => 'Waiting for',
    'progress_finished' => 'Finished',
    'progress_declined' => 'Declined',
    'progress_of_steps' => 'of',
    'progress_steps_complete' => 'complete',

    // Admin Dashboard - User Management
    'admin_users_title' => 'User Management',
    'admin_users_heading' => 'Users',
    'admin_users_add_button' => 'Add User',
    'admin_users_status_active' => 'Active',
    'admin_users_status_inactive' => 'Inactive',
    'admin_users_action_edit' => 'Edit',
    'admin_users_action_reset_pw' => 'Reset PW',
    'admin_users_action_delete' => 'Delete',
    'admin_users_section' => 'Section',
    'admin_users_modal_add_title' => 'Add New User',
    'admin_users_modal_full_name' => 'Full Name',
    'admin_users_modal_username' => 'Username',
    'admin_users_modal_password' => 'Password',
    'admin_users_modal_role' => 'Role',
    'admin_users_modal_section' => 'Section',
    'admin_users_modal_section_none' => 'None',
    'admin_users_modal_button_cancel' => 'Cancel',
    'admin_users_modal_button_save' => 'Save User',
    'admin_users_modal_delete_confirm' => 'Are you sure?',
    'admin_users_modal_delete_text' => 'Do you really want to delete the user',
    'admin_users_modal_reset_confirm' => 'Reset Password?',
    'admin_users_modal_reset_text' => 'This will reset the password for',
    'admin_users_modal_reset_default' => 'to the default "password".',
        'admin_users_modal_edit_title' => 'Edit User',

    // Admin Dashboard - Command History
    'admin_history_title' => 'Command History',
    'admin_history_search_placeholder' => 'Search by ID, Client Name, or Phone...',
    'admin_history_delete_all' => 'Delete All My History',
    'admin_history_none_found' => 'No command history found.',

    // Admin Dashboard - Live Commands
    'admin_live_title' => 'Live Commands',
    'admin_live_search_placeholder' => 'Search by client name...',
    'admin_live_search_button' => 'Search',
    'admin_live_status_all' => 'All',
    'admin_live_archive_title' => 'Archive',
    'admin_live_none_found' => 'No live commands found for the current filter criteria.',

    'history_title_admin' => 'Command History',
    'history_title_personal' => 'Your Personal Command History',
    'history_search_placeholder_admin' => 'Search by ID, Client Name, or Phone...',
    'history_search_placeholder_worker' => 'Search by Command ID...',
    'history_delete_all_button' => 'Delete All My History',
    'history_none_found_admin' => 'No command history found.',
    'history_none_found_personal' => 'You have no commands in your history yet.',

    // --- NEW: History Drawer Component ---
    'history_drawer_type' => 'Type',
    'history_drawer_delete_button' => 'Delete from my History',
    'history_drawer_status_completed' => 'Completed',
    'history_drawer_status_declined' => 'Declined',
    // ** THIS IS THE NEW, COMPLETE STATUSES SECTION **
    'status_all' => 'All',
    'status_pendingapproval' => 'Pending Approval',
    'status_inprogress' => 'In Progress',
    'status_paused' => 'Paused',
    'status_completed' => 'Completed',
    'status_declined' => 'Declined',
    'status_archived' => 'Archived',

    // --- NEW: Profile & Settings Pages ---
    'profile_page_title' => 'Manage Profile - BRIMAK',
    'profile_page_heading' => 'Profile',
    'profile_role' => 'Role',
    'profile_section' => 'Section',
    'profile_display_name' => 'Name',
    'profile_display_username' => 'Username',
    'profile_display_password' => 'Password',
    'profile_logout_button' => 'Logout',

    // Modals for Profile Page
    'profile_modal_change_name_title' => 'Change Full Name',
    'profile_modal_new_name_label' => 'New Full Name',
    'profile_modal_change_user_title' => 'Change Username',
    'profile_modal_current_user_label' => 'Current Username',
    'profile_modal_new_user_label' => 'New Username',
    'profile_modal_change_pass_title' => 'Change Password',
    'profile_modal_current_pass_label' => 'Current Password',
    'profile_modal_new_pass_label' => 'New Password',
    'profile_modal_button_cancel' => 'Cancel',
    'profile_modal_button_save' => 'Save',
    'profile_modal_button_update' => 'Update Password',

    // Success & Error Messages
    'profile_success_name_updated' => 'Full name successfully updated!',
    'profile_error_name_empty' => 'Full name cannot be empty.',
    'profile_success_user_updated' => 'Username successfully updated!',
    'profile_error_user_empty' => 'New username cannot be empty.',
    'profile_error_user_taken' => 'That username is already taken.',
    'profile_success_pass_updated' => 'Password successfully updated!',
    'profile_error_pass_empty' => 'Please fill in all password fields.',
    'profile_error_pass_incorrect' => 'Your current password was incorrect.',

    // --- NEW: Settings Page ---
    'settings_page_heading' => 'Settings',
    'settings_page_subheading' => 'Manage your account and preferences',
    'settings_display_name' => 'Name',
    'settings_display_username' => 'Username',
    'settings_display_password' => 'Password',
    'settings_action_edit' => 'Edit',
    'settings_form_button_cancel' => 'Cancel',
    'settings_form_button_save' => 'Save',
    'settings_form_current_pass_label' => 'Current Password',
    'settings_form_new_pass_label' => 'New Password',
    'settings_appearance_heading' => 'Appearance',
    'settings_language_label' => 'Language',
    'settings_language_value' => 'English (FR coming soon)',
    'settings_dark_mode_label' => 'Dark Mode',

    // --- NEW: Commercial Dashboard ---
    'commercial_live_title' => 'Live Commands',
    'commercial_create_button' => 'Create New Command',
    'commercial_search_placeholder' => 'Search by client name...',
    'commercial_none_found' => 'No commands match the current filter.',
    
    // Create/Edit Command Modal
    'commercial_modal_create_title' => 'Create 11New Command',
    'commercial_modal_edit_title' => 'Modify & Resend Command',
    'commercial_modal_type_label' => 'Type',
    'commercial_modal_type_a' => 'BRIMAK A',
    'commercial_modal_type_b' => 'BRIMAK B',
    'commercial_modal_dimensions_label' => 'Dimensions',
    'commercial_modal_dimensions_placeholder' => 'e.g. 20cm x 10cm x 5cm',
    'commercial_modal_quantity_label' => 'Quantity',
    'commercial_modal_delivery_date_label' => 'Delivery Date',
    'commercial_modal_client_name_label' => 'Client Name',
    'commercial_modal_client_phone_label' => 'Client Phone',
    'commercial_modal_notes_label' => 'Additional Notes',
    'commercial_modal_button_cancel' => 'Cancel',
    'commercial_modal_button_save' => 'Save Command',
    'commercial_modal_action_modify' => 'Modify & Resend',

     // --- NEW: Production Worker Dashboard ---
    'production_dashboard_title_suffix' => 'Dashboard',
    'production_tasks_to_complete' => 'You have {count} task(s) to complete.',
    'production_task_complete_button' => 'Task Complete',
    'production_no_pending_tasks' => 'No pending tasks at the moment. Great job!',

     // --- NEW: Chef Dashboard ---
    'chef_dashboard_title' => 'Chef Dashboard - Section {section}',
    'chef_commands_to_approve' => 'You have {count} new command(s) awaiting approval.',
    'chef_no_new_commands' => 'No new commands to review.',
    'chef_action_decline' => 'Decline',
    'chef_action_accept' => 'Accept',
    
    // Decline Modal
    'chef_modal_decline_title' => 'Decline Command',
    'chef_modal_decline_reason_label' => 'Reason for Declining',
    'chef_modal_decline_reason_placeholder' => 'e.g., Wrong specs...',
    'chef_modal_button_cancel' => 'Cancel',
    'chef_modal_button_decline' => 'Decline Command',
    
    // in lang/en.php
    'status_cancelled' => 'Cancelled',
    
    // product name stuff
    'commercial_modal_product_label' => 'Product Name',
    'commercial_modal_arrival_date_label' => 'Arrival Date',
    'commercial_modal_deadline_date_label' => 'Deadline Date',

    // --- Role Translations ---
    'role_admin' => 'Admin',
    'role_commercial' => 'Commercial',
    'role_chef' => 'Chef',
    'role_producer' => 'Producer',
    'role_dryer' => 'Dryer',
    'role_cooker' => 'Cooker',
    'role_presser' => 'Presser',
    'role_packer' => 'Packer',

    //window view wprogress
    'menu_view_progress' => 'View Progress',
    'menu_cancel_command' => 'Cancel Command',

        'command_create_success' => 'Command successfully created!',
    'command_update_success' => 'Command successfully updated!',

    // --- Cancel Command Modal ---
    'modal_cancel_command_title' => 'Cancel Command',
    'modal_cancel_command_text' => 'Are you sure you want to cancel command',
    'modal_button_no_keep' => 'No, keep it',
    'modal_button_yes_cancel' => 'Yes, cancel it'
];