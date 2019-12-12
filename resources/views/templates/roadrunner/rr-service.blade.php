[Unit]
After=network.target

[Service]
Type=simple
User={{ $user }}
Group={{ $group }}
WorkingDirectory={{ $workingPath }}
ExecStart={{ $rrPath }} serve -d
ExecReload={{ $rrPath }} http:reset
ExecStop=/bin/kill -s TERM $MAINPID
Restart=always

[Install]
WantedBy=multi-user.target
