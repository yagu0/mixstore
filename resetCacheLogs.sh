#!/bin/sh
#Clean cache and logs

if [ -d app/cache ]; then
	rm -rf app/cache/*
fi
if [ -d app/logs ]; then
	rm -rf app/logs/*
fi
chmod 777 app/{cache,logs}
