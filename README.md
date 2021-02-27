# Scrapping test

## Demo

[![demo](https://img.youtube.com/vi/4RnSUlV1FDE/0.jpg)](https://www.youtube.com/watch?v=4RnSUlV1FDE)

## How to use

Getting recommendations: 
```bash
php artisan scrapping:amazon {keywords?} {fileName?}
```

Example:
```bash
php artisan scrapping:amazon "Ice Cream Scoop, Insulated Tumbler,  blah blah"
```

Path to CSV files:
```bash
public/csv/*
```

## Install
```bash
composer install
```

## Requirements
- PHP >=7.4

