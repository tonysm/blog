#!/bin/bash

rm -rf build_production

./vendor/bin/jigsaw build production

cd build_production

git init
git add .
git commit -m "deploy"
git remote add homepage git@github.com:tonysm/tonysm.github.io.git
git remote add blog git@github.com:tonysm/tonysm.github.io.git
git push --force homepage master
git push --force blog master:gh-pages

rm -rf ./.git/
cd ../
