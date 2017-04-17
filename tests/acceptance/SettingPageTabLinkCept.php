<?php

declare(strict_types=1);

namespace WPCFG;

$I = new AcceptanceTester($scenario);
$I->wantTo('setting page has tab links');

$I->amOnWPCFGSettingPage();

$I->seeElement('#wpcfg_cloudflare-tab');
$I->seeElement('#wpcfg_bad_login-tab');

$I->click('#wpcfg_bad_login-tab');

$I->waitForText('WP Cloudflare Guard - Bad Login', 10, 'h1');
$I->seeInCurrentUrl('/wp-admin/admin.php?page=wpcfg_bad_login');
$I->seeElement('#wpcfg_cloudflare-tab');
$I->seeElement('#wpcfg_bad_login-tab');

$I->click('#wpcfg_cloudflare-tab');
$I->waitForText('WP Cloudflare Guard', 10, 'h1');
$I->seeInCurrentUrl('/wp-admin/admin.php?page=wpcfg_cloudflare');
