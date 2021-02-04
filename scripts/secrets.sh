# Generate salt file

salt=$(date +"%s")
echo "<?php" > includes/salt.php
echo '$salt = "'$salt'";' >> includes/salt.php

# Generate DB file

echo "<?php" > includes/db.php
echo '$servername = "localhost:3306";'>> includes/db.php
echo '$DBName = "iclip";' >> includes/db.php
echo '$username = "root";' >> includes/db.php
echo '$password = "";' >> includes/db.php