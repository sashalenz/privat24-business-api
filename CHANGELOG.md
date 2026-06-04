# Changelog

All notable changes to `privat24-business-api` will be documented in this file.

## 1.0.2 - 2026-06-05

### What's Changed

* Make bank-descriptor fields on `Transaction` (`AUT_MY_*` / `AUT_CNTR_*`) nullable — Privat24 returns `null` for them on transaction types without counterparty bank info (card payments, fees, withdrawals), which previously threw a `TypeError`

## 1.0.1 - 2026-04-30

### What's Changed

* Bump dependabot/fetch-metadata from 2.5.0 to 3.1.0 by @dependabot[bot] in https://github.com/sashalenz/privat24-business-api/pull/12

**Full Changelog**: https://github.com/sashalenz/privat24-business-api/compare/1.0.0...1.0.1

## 1.0.0 - 2026-04-30

### What's Changed

* Bump aglipanci/laravel-pint-action from 2.5 to 2.6 by @dependabot[bot] in https://github.com/sashalenz/privat24-business-api/pull/5
* Bump dependabot/fetch-metadata from 2.4.0 to 2.5.0 by @dependabot[bot] in https://github.com/sashalenz/privat24-business-api/pull/9

**Full Changelog**: https://github.com/sashalenz/privat24-business-api/compare/0.3.0...1.0.0

## 0.3.0 - 2025-06-02

### What's Changed

* Bump dependabot/fetch-metadata from 2.3.0 to 2.4.0 by @dependabot in https://github.com/sashalenz/privat24-business-api/pull/3

**Full Changelog**: https://github.com/sashalenz/privat24-business-api/compare/0.2.0...0.3.0

## 0.1.2 - 2025-01-16

**Full Changelog**: https://github.com/sashalenz/privat24-business-api/compare/0.1.0...0.1.2

## 0.1.1 - 2025-01-16

**Full Changelog**: https://github.com/sashalenz/privat24-business-api/commits/0.1.1

## 0.1.0 - 2025-01-15

**Full Changelog**: https://github.com/sashalenz/privat24-business-api/commits/0.1.0
