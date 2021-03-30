<?php

declare(strict_types=1);

namespace coppolafab\MicroMySqlDriver;

use Illuminate\Database\Connectors\MySqlConnector;

class MicroMySqlConnector extends MySqlConnector
{
    public function connect(array $config)
    {
        $dsn = $this->getDsn($config);
        $options = $this->getOptions($config);
        $connection = $this->createConnection($dsn, $config, $options);
        $this->configureEncoding($connection, $config);
        $this->configureTimezone($connection, $config);
        $this->setModes($connection, $config);
        return $connection;
    }

    protected function getDsn(array $config)
    {
        $dsn = parent::getDsn($config);

        if (isset($config['charset'])) {
            $dsn .= ';charset=' . $config['charset'];
        }

        return $dsn;
    }

    protected function configureEncoding($connection, array $config)
    {
        if (!isset($config['charset'])) {
            return;
        }

        $collation = $this->getCollation($config);

        if ($collation) {
            $connection->exec("set names '{$config['charset']}'" . $collation);
        }
    }
}
