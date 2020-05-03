<?php

declare(strict_types=1);

namespace coppolafab\MicroMySqlDriver;

use Illuminate\Database\MySqlConnection as BaseMysqlConnection;

class MicroMySqlConnection extends BaseMysqlConnection
{
    public function recordsHaveBeenModified($value = true)
    {
	parent::recordsHaveBeenModified($value);

        if ($this->recordsModified && $this->readPdo !== null && $this->readPdo !== $this->pdo) {
            $this->setReadPdo(null);
        }
    }
}

