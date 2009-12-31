#!/bin/sh

cp -r ~/Workspace/ZendFramework/library/Zend/Filter/ImageSize* Zend/Filter/
find Zend/Filter/ -iname .svn | xargs rm -r

