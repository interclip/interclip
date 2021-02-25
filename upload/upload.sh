#!/bin/bash
rclone copy "$1" iclip: && rm $1