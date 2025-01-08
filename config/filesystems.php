<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. A "local" driver, as well as a variety of cloud
    | based drivers are available for your choosing. Just store away!
    |
    | Supported: "local", "ftp", "s3", "rackspace"
    |
    */

    'default' => 'local',

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => 'rackspace',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root'   => storage_path('app'),
        ],

        'ftp' => [
            'driver'   => 'ftp',
            'host'     => 'ftp.example.com',
            'username' => 'your-username',
            'password' => 'your-password',

            // Optional FTP Settings...
            // 'port'     => 21,
            // 'root'     => '',
            // 'passive'  => true,
            // 'ssl'      => true,
            // 'timeout'  => 30,
        ],

        's3' => [
            'driver' => 's3',
            'key'    => 'your-key',
            'secret' => 'your-secret',
            'region' => 'your-region',
            'bucket' => 'your-bucket',
        ],


	    'rackspace' => [
            'driver'    => 'rackspace',
            'username'  => 'iontv1',
            'key'       => '2120c1730a208d514e52af24aced15e7',
            'container' => 'qubo',
            'endpoint'  => 'https://identity.api.rackspacecloud.com/v2.0/',
            'region'    => 'IAD',
            'url_type'  => 'publicURL',
            'public_url' => 'http://a635427c6763d1201e63-79da6faba5b566b0b230640b13bfaff7.r39.cf5.rackcdn.com',
            'public_url_ssl' => 'https://a86ad5ac63c1cca559d1-79da6faba5b566b0b230640b13bfaff7.ssl.cf5.rackcdn.com'
        ],
        'corporate' => [
            'driver'    => 'rackspace',
            'username'  => 'iontv1',
            'key'       => '2120c1730a208d514e52af24aced15e7',
            'container' => 'ion_media_networks',
            'endpoint'  => 'https://identity.api.rackspacecloud.com/v2.0/',
            'region'    => 'IAD',
            'url_type'  => 'publicURL',
            'public_url' => 'http://e00da315a735a8fe3a1d-53dd2d41f5e8f71ab7be7bde4d129736.r42.cf5.rackcdn.com',
            'public_url_ssl' => 'https://ef4053d636c8c3c65fe5-53dd2d41f5e8f71ab7be7bde4d129736.ssl.cf5.rackcdn.com'
        ],

    ],

];
