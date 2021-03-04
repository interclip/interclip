#!/bin/bash
rclone copy --fast-list "$1" iclip: && rm $1
