<?php

return [
    'client-id' => '112968205291330435185',
    'client-email' => 'qubo-499@ion-television.iam.gserviceaccount.com',
	'private-key-password' => ' notasecret ',
    'certificate-path' => storage_path('google-analytics/qubo-45a96de65ac3.p12'),
	'scopes' => [
				 'https://www.googleapis.com/auth/admin.reports.audit.readonly',
				 'https://www.googleapis.com/auth/admin.reports.usage.readonly',
				 'https://www.googleapis.com/auth/analytics',	/* View and manage your Google Analytics data*/
				 'https://www.googleapis.com/auth/analytics.edit', /*	Edit Google Analytics management entities*/
				 'https://www.googleapis.com/auth/analytics.manage.users', /*	Manage Google Analytics Account users by email address*/
				 'https://www.googleapis.com/auth/analytics.manage.users.readonly', /*	View Google Analytics user permissions*/
				 'https://www.googleapis.com/auth/analytics.provision', /*	Create a new Google Analytics account along with its default property and view */
				 'https://www.googleapis.com/auth/analytics.readonly' /* View your Google Analytics Data */
				]
];
