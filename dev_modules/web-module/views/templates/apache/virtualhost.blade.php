<Directory {{ $siteDir }}>
    AllowOverride None
    Require all denied
</Directory>

@if($hasHttpsVhost ?? false)
<IfModule mod_ssl.c>
    SSLStaplingCache shmcb:/var/run/ocsp(128000)
</IfModule>
@endif

@foreach($ips as $ipPort => $httpsEnabled)
<VirtualHost {{ $ipPort }}>

    ServerName {{ $domain }}
    ServerAlias www.{{ $domain }}
    ServerAdmin webmaster{{ "@$domain" }}

    @if($httpsEnabled ?? false)
    Protocols h2 http/1.1
    SSLProtocol All -SSLv2 -SSLv3
    SSLCipherSuite 'EECDH+ECDSA+AESGCM EECDH+aRSA+AESGCM EECDH+ECDSA+SHA384 EECDH+ECDSA+SHA256 EECDH+aRSA+SHA384 EECDH+aRSA+SHA256 EECDH+aRSA+RC4 EECDH EDH+aRSA !RC4 !aNULL !eNULL !LOW !3DES !MD5 !EXP !PSK !SRP !DSS'

    <IfModule mod_ssl.c>
        SSLEngine on
        SSLProtocol All -SSLv2 -SSLv3
        SSLHonorCipherOrder on
        SSLCertificateFile {{ $httpsCrtPath }}
        SSLCertificateKeyFile {{ $httpsKeyPath }}
        SSLUseStapling on
        SSLStaplingResponderTimeout 5
        SSLStaplingReturnResponderErrors off
    </IfModule>
    @endif

    DocumentRoot {{ $documentRoot }}
    <Directory {{ $documentRoot }}>
        <FilesMatch ".+\.ph(p[345]?|t|tml)$">
            SetHandler None
        </FilesMatch>
        Options +SymlinksIfOwnerMatch -Indexes
        AllowOverride All
        Require all granted
    </Directory>

    <IfModule mod_suexec.c>
        SuexecUserGroup {{ $user }} {{ $group }}
    </IfModule>


    @if($phpMode === 'fpm')
    <IfModule mod_proxy_fcgi.c>
        <Directory {{ $documentRoot }}>
            <FilesMatch "\.php[345]?$">
                SetHandler {{ $fpmHandlers }}
            </FilesMatch>
        </Directory>
    </IfModule>
    @endif

    ErrorLog {{ $logDir }}error.log
    CustomLog {{ $logDir }}access.log combined
</VirtualHost>
@endforeach
