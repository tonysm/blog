#!/bin/bash

rm -rf build_production

npm run production
./vendor/bin/jigsaw build production

cd build_production

git init
git add .
git commit -m "deploy"
git remote add blog git@github.com:tonysm/tonysm.github.io.git
git push --force blog master:master

rm -rf ./.git/
cd ../
