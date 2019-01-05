#!/bin/bash

rm -rf build_production

./vendor/bin/jigsaw build production

cd build_production

git init
git add .
git commit -m "deploy"
git remote add blog git@github.com:tonysm/blog.git
git push --force blog master:gh-pages

rm -rf ./.git/
cd ../
