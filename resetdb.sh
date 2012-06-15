#!/bin/bash
set -e

# Please set this
dbuser=""
dbpass=""
dbname=""

# Check if we have all the infos
if [ -z "$dbuser" ]; then
  echo "Please fill the dbuser field in the script."
  exit 1
fi

if [ -z "$dbpass" ]; then
  echo "Please fill the dbpass field in the script."
  exit 1
fi

if [ -z "$dbname" ]; then
  echo "Please fill the dbname field in the script."
  exit 1
fi

# Drop database
echo "Dropping database."
echo "DROP DATABASE IF EXISTS $dbname" | mysql -u "$dbuser" --password="$dbpass"

# Create database
echo "Creating database."
echo "CREATE DATABASE $dbname" | mysql -u "$dbuser" --password="$dbpass"

# Import stuff
echo "Importing .sql files"
mysql -u "$dbuser" --password="$dbpass" "$dbname" < _des/sql/cococo3.sql
mysql -u "$dbuser" --password="$dbpass" "$dbname" < _des/sql/sproc.sql
