[{{ $webspace }}]

@if ($listenMode === 'unix')
listen = {{ $unixSocketPath }}
listen.owner = {{ $unixSocketOwner }}
listen.group = {{ $unixSocketGroup }}
listen.mode = 0660
@else
listen = 127.0.0.1:{{ $tcpPort }}
listen.allowed_clients = 127.0.0.1
@endif

user = {{ $user }}
group = {{ $group }}

pm = dynamic
pm.max_children = 10
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 5
pm.max_requests = 0

chdir = /

env[HOSTNAME] = $HOSTNAME
env[TMP] = {{ $tempDir }}
env[TMPDIR] ={{ $tempDir }}
env[TEMP] = {{ $tempDir }}
env[PATH] = /usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
