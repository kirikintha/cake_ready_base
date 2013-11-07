#!/bin/bash

SCHEMA='qns_sdk'

TEST_SCHEMA=$SCHEMA"_test"

#Remove old files.
rm -rf $SCHEMA
rm -rf $TEST_SCHEMA

#Dump from Mongo.
mongodump -v --db $SCHEMA --out install/
mongodump -v --db $TEST_SCHEMA --out install/

#Compress and clean up directories.
cd install

tar cvzf $SCHEMA.tar.gz $SCHEMA/
tar cvzf $TEST_SCHEMA.tar.gz $TEST_SCHEMA/

rm -rf $SCHEMA
rm -rf $TEST_SCHEMA
