#!/bin/bash

rm -rf dist/

yarn build

cd dist/

git init
git add .
git commit -m "deploy"
git remote add blog git@github.com:tonysm/blog.git
git push --force blog master:gh-pages

rm -rf ./.git/
cd ../
