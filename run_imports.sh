#!/bin/bash

cd "`dirname $0`"

if [ -f "storage/import/products.csv" ]; then
	php artisan import:products
fi

if [ -f "storage/import/discounts.csv" ]; then
	php artisan import:discounts
fi
