# catch all virtualhosts

<VirtualHost _default_:80>
    DocumentRoot /var/www/default
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>

<IfModule mod_ssl.c>
    <VirtualHost _default_:443>
        DocumentRoot /var/www/default

        SSLEngine on
        SSLCertificateFile /etc/ssl/certs/ssl-cert-snakeoil.pem
        SSLCertificateKeyFile /etc/ssl/private/ssl-cert-snakeoil.key

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
    </VirtualHost>
</IfModule>

@foreach($ips as $ip)
    <VirtualHost {{ $ip->type === 'ipv6' ? "[$ip->address]" : $ip->address }}:80>
        DocumentRoot /var/www/default
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
    </VirtualHost>

    <IfModule mod_ssl.c>
        <VirtualHost {{ $ip->type === 'ipv6' ? "[$ip->address]" : $ip->address }}:443>
            DocumentRoot /var/www/default

            SSLEngine on
            SSLCertificateFile /etc/ssl/certs/ssl-cert-snakeoil.pem
            SSLCertificateKeyFile /etc/ssl/private/ssl-cert-snakeoil.key

            ErrorLog ${APACHE_LOG_DIR}/error.log
            CustomLog ${APACHE_LOG_DIR}/access.log combined
        </VirtualHost>
    </IfModule>
@endforeach
