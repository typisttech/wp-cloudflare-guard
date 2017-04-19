<?php

declare(strict_types=1);

namespace WPCFG;

$I = new AcceptanceTester($scenario);
$I->wantTo('setting page has tab links');

$I->amOnWPCFGSettingPage();

$I->seeElement('#wpcfg-cloudflare-tab');
$I->seeElement('#wpcfg-bad-login-tab');

$I->click('#wpcfg-bad-login-tab');

$I->waitForText('WP Cloudflare Guard - Bad Login', 10, 'h1');
$I->seeInCurrentUrl('/wp-admin/admin.php?page=wpcfg-bad-login');
$I->seeElement('#wpcfg-cloudflare-tab');
$I->seeElement('#wpcfg-bad-login-tab');

$I->click('#wpcfg-cloudflare-tab');
$I->waitForText('WP Cloudflare Guard', 10, 'h1');
$I->seeInCurrentUrl('/wp-admin/admin.php?page=wpcfg-cloudflare');
