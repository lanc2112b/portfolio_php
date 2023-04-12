<?php

namespace db;

use Core\Model;
use PDO;

class LiveSeederPortfolio extends Model
{

    public function dropTable()
    {
        $db = static::getDB();
        $stmt = $db->query("DROP TABLE IF EXISTS portfolio_items");
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function createPortfolioTable()
    {
        $db = static::getDB();

        $sql = "CREATE TABLE IF NOT EXISTS portfolio_items(
            id INT AUTO_INCREMENT PRIMARY KEY,
            title TINYTEXT NOT NULL,
            description TEXT NOT NULL,
            hosted_url TINYTEXT,
            github_url TINYTEXT,
            image_url TINYTEXT,
            video_url TINYTEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP
            )";

        $stmt = $db->query($sql);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function describeTable()
    {
        $db = static::getDB();

        $stmt = $db->prepare("DESCRIBE portfolio_items");

        $stmt->execute();

        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $results;
    }

    public function addPortfolioItems()
    {
        $db = static::getDB();

        $sql = "INSERT INTO portfolio_items
                (title, description, hosted_url, github_url)
                VALUES
                ('NC-News', 'A very lightweight Reddit like service. The backend is built using Node, Express, & Axios. TDD / Tested using Jest & supertest', 'https://news.muninn.co.uk/', 'https://github.com/lanc2112b/nc-news-fe'),
                ('PlaidPal', 'The final project at Northcoders, a team effort. This is a bank account aggregator using OpenBanking via Plaid\'s API', 'https://plaidpal.nidhoggr.co.uk/', 'https://github.com/lanc2112b/react_plaidpal')";

        if ($db->exec($sql)) {
            return true;
        }
        return false;
    }

    public function getPortfolioItems()
    {
        $db = static::getDB();

        $stmt = $db->query("SELECT * FROM portfolio_items");

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }
}