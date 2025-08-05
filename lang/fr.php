<?php
// lang/fr.php
return [
    // Titre de la Page
    'login_page_title' => 'Connexion BRIMAK',

    // Contenu du Formulaire
    'login_form_title' => 'Connexion',
    'login_form_welcome' => 'Content de vous revoir, veuillez vous connecter.',
    'login_username_placeholder' => 'Nom d\'utilisateur',
    'login_password_placeholder' => 'Mot de passe',
    'login_remember_me' => 'Se souvenir de moi',
    'login_button_text' => 'Se connecter',

    // Titre de la Page
    'page_title' => 'BRIMAK | Suivi de Commande',

    // Menu
    'menu_title' => 'Menu',
    'menu_dashboard' => 'Tableau de bord',
    'menu_settings' => 'Paramètres',
    'menu_user_management' => 'Gestion des utilisateurs',
    'menu_command_history' => 'Historique des commandes',
    'menu_logout' => 'Déconnexion',

    // Pied de page
    'footer_address_title' => 'BRIMAK',
    'footer_contact_title' => 'Contact',
    'footer_fax_title' => 'Fax',
    'footer_rights_reserved' => 'Tous droits réservés',
    'footer_developed_by' => 'Développé par',

    // Modals de Suppression d'Historique
    'modal_delete_command_title' => 'Supprimer la Commande',
    'modal_delete_command_confirm' => 'Êtes-vous sûr de vouloir supprimer définitivement la commande',
    'modal_delete_all_title' => 'Supprimer tout l\'historique',
    'modal_delete_all_confirm' => 'Êtes-vous sûr de vouloir supprimer DÉFINITIVEMENT TOUT l\'historique des commandes ? Cette action est irréversible.',
    'modal_button_no' => 'Non, conserver',
    'modal_button_yes_delete' => 'Oui, supprimer',
    'modal_button_yes_delete_all' => 'Oui, tout supprimer',

    // Carte de Commande & Tiroir d'Historique
    'command_id' => 'ID Commande',
    'status' => 'Statut',
    'reason_for_decline' => 'Raison du refus',
    'type' => 'Type',
    'quantity' => 'Quantité',
    'quantity_unit' => 'briques',
    'dimensions' => 'Dimensions',
    'delivery_date' => 'Date de livraison',
    'client_name' => 'Nom du client',
    'client_phone' => 'Téléphone du client',
    'additional_notes' => 'Notes supplémentaires',
    'delete_from_history' => 'Supprimer de mon historique',

    // Barre de Progression
    'progress_waiting_for' => 'En attente de',
    'progress_finished' => 'Terminé',
    'progress_declined' => 'Refusé',
    'progress_of_steps' => 'sur',
    'progress_steps_complete' => 'terminé',

     // Admin - Gestion des utilisateurs
    'admin_users_title' => 'Gestion des utilisateurs',
    'admin_users_heading' => 'Utilisateurs',
    'admin_users_add_button' => 'Ajouter un utilisateur',
    'admin_users_status_active' => 'Actif',
    'admin_users_status_inactive' => 'Inactif',
    'admin_users_action_edit' => 'Modifier',
    'admin_users_action_reset_pw' => 'Réinitialiser MdP',
    'admin_users_action_delete' => 'Supprimer',
    'admin_users_section' => 'Section',
    'admin_users_modal_add_title' => 'Ajouter un nouvel utilisateur',
    'admin_users_modal_full_name' => 'Nom complet',
    'admin_users_modal_username' => 'Nom d\'utilisateur',
    'admin_users_modal_password' => 'Mot de passe',
    'admin_users_modal_role' => 'Rôle',
    'admin_users_modal_section' => 'Section',
    'admin_users_modal_section_none' => 'Aucune',
    'admin_users_modal_button_cancel' => 'Annuler',
    'admin_users_modal_button_save' => 'Enregistrer',
    'admin_users_modal_delete_confirm' => 'Êtes-vous sûr ?',
    'admin_users_modal_delete_text' => 'Voulez-vous vraiment supprimer l\'utilisateur',
    'admin_users_modal_reset_confirm' => 'Réinitialiser le mot de passe ?',
    'admin_users_modal_reset_text' => 'Cela réinitialisera le mot de passe pour',
    'admin_users_modal_reset_default' => 'au mot de passe par défaut "password".',

    // Admin - Historique des commandes
    'admin_history_title' => 'Historique des commandes',
    'admin_history_search_placeholder' => 'Rechercher par ID, Nom ou Tél. client...',
    'admin_history_delete_all' => 'Supprimer tout mon historique',
    'admin_history_none_found' => 'Aucun historique de commande trouvé.',

    // Admin - Commandes en direct
    'admin_live_title' => 'Commandes en direct',
    'admin_live_search_placeholder' => 'Rechercher par nom de client...',
    'admin_live_search_button' => 'Rechercher',
    'admin_live_status_all' => 'Tout',
    'admin_live_archive_title' => 'Archiver',
    'admin_live_none_found' => 'Aucune commande en direct trouvée pour les critères de filtre actuels.',
        'modal_undone_warning' => 'Cette action est irréversible.',

     // --- NOUVEAU: Page d'historique Admin/Commercial/Ouvrier ---
    'history_title_admin' => 'Historique des Commandes',
    'history_title_personal' => 'Votre Historique Personnel',
    'history_search_placeholder_admin' => 'Rechercher par ID, Nom ou Tél. client...',
    'history_search_placeholder_worker' => 'Rechercher par ID de commande...',
    'history_delete_all_button' => 'Supprimer tout mon historique',
    'history_none_found_admin' => 'Aucun historique de commande trouvé.',
    'history_none_found_personal' => 'Vous n\'avez aucune commande dans votre historique.',

    // --- NOUVEAU: Composant de tiroir d'historique ---
    'history_drawer_type' => 'Type',
    'history_drawer_delete_button' => 'Supprimer de mon historique',
    'history_drawer_status_completed' => 'Terminé',
    'history_drawer_status_declined' => 'Refusé',
     // ** THIS IS THE NEW, COMPLETE STATUSES SECTION **
    'status_all' => 'Tout',
    'status_pendingapproval' => 'En attente',
    'status_inprogress' => 'En cours',
    'status_paused' => 'En pause',
    'status_completed' => 'Terminé',
    'status_declined' => 'Refusé',
    'status_archived' => 'Archivé',

    // --- NOUVEAU: Pages de Profil & Paramètres ---
    'profile_page_title' => 'Gérer le Profil - BRIMAK',
    'profile_page_heading' => 'Profil',
    'profile_role' => 'Rôle',
    'profile_section' => 'Section',
    'profile_display_name' => 'Nom',
    'profile_display_username' => 'Nom d\'utilisateur',
    'profile_display_password' => 'Mot de passe',
    'profile_logout_button' => 'Déconnexion',

    // Modals pour la Page de Profil
    'profile_modal_change_name_title' => 'Changer le nom complet',
    'profile_modal_new_name_label' => 'Nouveau nom complet',
    'profile_modal_change_user_title' => 'Changer le nom d\'utilisateur',
    'profile_modal_current_user_label' => 'Nom d\'utilisateur actuel',
    'profile_modal_new_user_label' => 'Nouveau nom d\'utilisateur',
    'profile_modal_change_pass_title' => 'Changer le mot de passe',
    'profile_modal_current_pass_label' => 'Mot de passe actuel',
    'profile_modal_new_pass_label' => 'Nouveau mot de passe',
    'profile_modal_button_cancel' => 'Annuler',
    'profile_modal_button_save' => 'Enregistrer',
    'profile_modal_button_update' => 'Mettre à jour',

    // Messages de Succès & d'Erreur
    'profile_success_name_updated' => 'Nom complet mis à jour avec succès !',
    'profile_error_name_empty' => 'Le nom complet ne peut pas être vide.',
    'profile_success_user_updated' => 'Nom d\'utilisateur mis à jour avec succès !',
    'profile_error_user_empty' => 'Le nouveau nom d\'utilisateur ne peut pas être vide.',
    'profile_error_user_taken' => 'Ce nom d\'utilisateur est déjà pris.',
    'profile_success_pass_updated' => 'Mot de passe mis à jour avec succès !',
    'profile_error_pass_empty' => 'Veuillez remplir tous les champs de mot de passe.',
    'profile_error_pass_incorrect' => 'Votre mot de passe actuel est incorrect.',

    // --- NOUVEAU: Page des Paramètres ---
    'settings_page_heading' => 'Paramètres',
    'settings_page_subheading' => 'Gérez votre compte et vos préférences',
    'settings_display_name' => 'Nom',
    'settings_display_username' => 'Nom d\'utilisateur',
    'settings_display_password' => 'Mot de passe',
    'settings_action_edit' => 'Modifier',
    'settings_form_button_cancel' => 'Annuler',
    'settings_form_button_save' => 'Enregistrer',
    'settings_form_current_pass_label' => 'Mot de passe actuel',
    'settings_form_new_pass_label' => 'Nouveau mot de passe',
    'settings_appearance_heading' => 'Apparence',
    'settings_language_label' => 'Langue',
    'settings_language_value' => 'Français (EN à venir)',
    'settings_dark_mode_label' => 'Mode Sombre',

    // --- NOUVEAU: Tableau de Bord Commercial ---
    'commercial_live_title' => 'Commandes en Direct',
    'commercial_create_button' => 'Créer une Nouvelle Commande',
    'commercial_search_placeholder' => 'Rechercher par nom de client...',
    'commercial_none_found' => 'Aucune commande ne correspond au filtre actuel.',

    // Modal de Création/Édition de Commande
    'commercial_modal_create_title' => 'Créer une Nouvelle Commande',
    'commercial_modal_edit_title' => 'Modifier & Renvoyer la Commande',
    'commercial_modal_type_label' => 'Type',
    'commercial_modal_type_a' => 'BRIMAK A',
    'commercial_modal_type_b' => 'BRIMAK B',
    'commercial_modal_dimensions_label' => 'Dimensions',
    'commercial_modal_dimensions_placeholder' => 'ex: 20cm x 10cm x 5cm',
    'commercial_modal_quantity_label' => 'Quantité',
    'commercial_modal_delivery_date_label' => 'Date de livraison',
    'commercial_modal_client_name_label' => 'Nom du Client',
    'commercial_modal_client_phone_label' => 'Téléphone du Client',
    'commercial_modal_notes_label' => 'Notes Supplémentaires',
    'commercial_modal_button_cancel' => 'Annuler',
    'commercial_modal_button_save' => 'Enregistrer la Commande',
    'commercial_modal_action_modify' => 'Modifier & Renvoyer',
        'admin_users_modal_edit_title' => 'Modifier l\'Utilisateur',

     // --- NOUVEAU: Tableau de Bord de Production ---
    'production_dashboard_title_suffix' => 'Tableau de bord',
    'production_tasks_to_complete' => 'Vous avez {count} tâche(s) à accomplir.',
    'production_task_complete_button' => 'Tâche Terminée',
    'production_no_pending_tasks' => 'Aucune tâche en attente pour le moment. Excellent travail !',

    // --- NOUVEAU: Tableau de Bord du Chef ---
    'chef_dashboard_title' => 'Tableau de Bord du Chef - Section {section}',
    'chef_commands_to_approve' => 'Vous avez {count} nouvelle(s) commande(s) en attente d\'approbation.',
    'chef_no_new_commands' => 'Aucune nouvelle commande à examiner.',
    'chef_action_decline' => 'Refuser',
    'chef_action_accept' => 'Accepter',

    // Modal de Refus
    'chef_modal_decline_title' => 'Refuser la Commande',
    'chef_modal_decline_reason_label' => 'Raison du refus',
    'chef_modal_decline_reason_placeholder' => 'ex: Spécifications incorrectes...',
    'chef_modal_button_cancel' => 'Annuler',
    'chef_modal_button_decline' => 'Refuser la Commande',
    
    'status_cancelled' => 'Annulé',
    'commercial_modal_product_label' => 'Nom du Produit',
    'commercial_modal_arrival_date_label' => 'Date d\'Arrivée',
    'commercial_modal_deadline_date_label' => 'Date d\'Échéance',

        // --- Traductions des Rôles ---
    'role_admin' => 'Administrateur',
    'role_commercial' => 'Commercial',
    'role_chef' => 'Chef de Section',
    'role_producer' => 'Producteur',
    'role_dryer' => 'Sécheur',
    'role_cooker' => 'Cuisinier',
    'role_presser' => 'Presseur',
    'role_packer' => 'Emballeur',

    //window view wprogress
    'menu_view_progress' => 'Voir la Progression',
    'menu_cancel_command' => 'Annuler la Commande',

    'command_create_success' => 'Commande créée avec succès !',
    'command_update_success' => 'Commande mise à jour avec succès !',

        // --- Modal d'Annulation de Commande ---
    'modal_cancel_command_title' => 'Annuler la Commande',
    'modal_cancel_command_text' => 'Êtes-vous sûr de vouloir annuler la commande',
    'modal_button_no_keep' => 'Non, conserver',
    'modal_button_yes_cancel' => 'Oui, annuler',

];
