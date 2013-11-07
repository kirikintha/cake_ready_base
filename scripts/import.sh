#!/bin/bash

SCHEMA='qns_sdk'

# import data
echo "Importing data into $SCHEMA"

# update test schema for cakephp unit testing framework
TEST_SCHEMA=$SCHEMA"_test"

#Start import, clean up afterwards.
echo "Importing data to $SCHEMA and $TESTSCHEMA..."

cd install

tar xvf $SCHEMA.tar.gz
mongorestore -v --drop $SCHEMA

echo "$SCHEMA import complete"

tar xvf $TEST_SCHEMA.tar.gz
mongorestore -v --drop $TEST_SCHEMA

echo "$TEST_SCHEMA import complete"

rm -rf $SCHEMA
rm -rf $TEST_SCHEMA
