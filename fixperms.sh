#!/bin/sh

s3cmd ls --recursive s3://manager.dockerwordpress.com | grep \\.html | cut -c 30- | xargs s3cmd modify --add-header=Cache-Control:max-age=86400 -m text/html

s3cmd ls --recursive s3://manager.dockerwordpress.com | grep \\.css | cut -c 30- | xargs s3cmd modify --add-header=Cache-Control:max-age=86400 -m text/css

s3cmd ls --recursive s3://manager.dockerwordpress.com | grep \\.js | cut -c 30- | xargs s3cmd modify --add-header=Cache-Control:max-age=86400 -m application/javascript

s3cmd ls --recursive s3://manager.dockerwordpress.com | grep \\.json | cut -c 30- | xargs s3cmd modify --add-header=Cache-Control:max-age=86400 -m application/javascript

s3cmd ls --recursive s3://manager.dockerwordpress.com | grep \\.gif | cut -c 30- | xargs s3cmd modify --add-header=Cache-Control:max-age=86400 -m image/gif

s3cmd ls --recursive s3://manager.dockerwordpress.com | grep \\.png | cut -c 30- | xargs s3cmd modify --add-header=Cache-Control:max-age=86400 -m image/png

s3cmd ls --recursive s3://manager.dockerwordpress.com | grep \\.jpg | cut -c 30- | xargs s3cmd modify --add-header=Cache-Control:max-age=86400 -m image/jpg




