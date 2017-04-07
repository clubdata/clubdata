<?php
use Clubdata\Application;

$nav = Application::instance()->getNavigation();

// Main
$nav->addRoute('main', 'main');
$nav->addRoute('main-copyright', 'main', 'Copyright');
$nav->addRoute('main-impressum', 'main', 'Impressum');
$nav->addRoute('main-logoff', 'main', 'Logoff');

// Members
$nav->addRoute('members', 'members');
$nav->addRoute('members-insert', 'members', null, 'INSERT');
$nav->addRoute('members-member', 'members', 'Member');
$nav->addRoute('members-conferences', 'members', 'Conferences');
$nav->addRoute('members-overview', 'members', 'Overview');

// Search
$nav->addRoute('search', 'search', 'Search');
$nav->addRoute('search-email', 'search', 'Email');
$nav->addRoute('search-infoletter', 'search', 'Infoletter');
$nav->addRoute('search-invoice', 'search', 'Invoice');
$nav->addRoute('search-payments', 'search', 'Payments');
$nav->addRoute('search-fees', 'search', 'Fees');
$nav->addRoute('search-conferecens', 'search', 'Conferences');

// Queries
$nav->addRoute('queries', 'queries', 'Queries');
$nav->addRoute('queries-member-summary', 'queries', 'MemberSummary');
$nav->addRoute('queries-statistics', 'queries', 'Statistics');
$nav->addRoute('queries-address-lists', 'queries', 'AddressLists');

// Jobs
$nav->addRoute('jobs', 'jobs', 'Jobs');
$nav->addRoute('jobs-end-of-year', 'jobs', 'EndOfYear');

// Conferences
$nav->addRoute('conferences', 'conferences', 'Conferences');
$nav->addRoute('conferences-list', 'conferences', 'List');
$nav->addRoute('conferences-add', 'conferences', 'Add');

// Settings
$nav->addRoute('settings', 'settings', 'Settings');
$nav->addRoute('settings-columns', 'settings', 'Columns');
$nav->addRoute('settings-personal', 'settings', 'Personal');

// Admin
$nav->addRoute('admin', 'admin', 'Admin');
$nav->addRoute('admin-users', 'admin', 'Users');
$nav->addRoute('admin-configuration', 'admin', 'Configuration');
$nav->addRoute('admin-database', 'admin', 'Database');
$nav->addRoute('admin-log', 'admin', 'Log');
$nav->addRoute('admin-backup', 'admin', 'Backup');
$nav->addRoute('admin-detail', 'admin', 'Detail');
$nav->addRoute('admin-edit', 'admin', 'Edit');
$nav->addRoute('admin-list-delete', 'admin', 'Edit');

// Help
$nav->addRoute('help-context', 'help', 'Context');
