<?php

/** autoload */
require '../vendor/autoload.php';

require __DIR__ . '/bootstrap.php';

if (!$_ENV['DB_HOST']) {
    die('Env not loaded correctly');
}

/** Need to implement db migrations, look at phinx? */
use db\LiveSeederLanding;
use db\LiveSeederContact;
use db\LiveSeederPortfolio;
use db\LiveSeederUser;
use db\LiveSeederLogging;

echo "Seeding logging page tables...\n";

$logging = new LiveSeederLogging();

$logging->dropTable();

$logging->createLoggingTable();

//$landing->addLandingContent();

echo "Seeded logging page content... \n";


echo "Seeding landing page tables...\n";

$landing = new LiveSeederLanding();

$landing->dropTable();

$landing->createLandingTable();

$landing->addLandingContent();

echo "Seeded landing page content... \n";

/** --------------------------------- */

echo "Seeding portfolio page table...\n";

$portfolio = new LiveSeederPortfolio();

$portfolio->dropTable();

$portfolio->createPortfolioTable();

$portfolio->addPortfolioItems();

echo "Seeded portfolio page content... \n";

/** --------------------------------- */

echo "Seeding contact form table...\n";

$contact = new LiveSeederContact();

$contact->dropTable();

$contact->createContactTable();

$contact->addContactItems();

echo "Seeded contact form content... \n";

/** --------------------------------- */

echo "Creating users table...\n";

$users = new LiveSeederUser();

$users->dropTable();

$users->createUsersTable();

echo "Created users table... \n";

echo "Done \n\n";