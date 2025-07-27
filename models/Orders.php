<?php

class OrderModel {
    private PDO $pdo;
    private string $table = 'pedidos';

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    private function tableExists(): bool {
        $stmt = $this->pdo->prepare("SHOW TABLES LIKE :table");
        $stmt->execute([':table' => $this->table]);
        return $stmt->rowCount() > 0;
    }

    private function columnExists(string $column): bool {
        $stmt = $this->pdo->prepare("SHOW COLUMNS FROM `{$this->table}` LIKE :column");
        $stmt->execute([':column' => $column]);
        return $stmt->rowCount() > 0;
    }

    public function ensureColumnsExist(): array {
        $report = [
            'table_created' => false,
            'columns_added' => [],
            'columns_existing' => [],
        ];


        if (!$this->tableExists()) {
            $sql = "
                CREATE TABLE `{$this->table}` (
                    id INT AUTO_INCREMENT PRIMARY KEY
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ";
            $this->pdo->exec($sql);
            $report['table_created'] = true;
        }

        $columns = [
            'cliente_nome' => "VARCHAR(255) NOT NULL",
            'descricao' => "TEXT",
            "status" => "ENUM('aberto', 'processando', 'enviado', 'entregue', 'cancelado')",
            'data_criacao' => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
            'data_atualizacao' => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
        ];

        foreach ($columns as $colName => $colDefinition) {
            if (!$this->columnExists($colName)) {
                $sql = "ALTER TABLE `{$this->table}` ADD COLUMN `$colName` $colDefinition";
                $this->pdo->exec($sql);
                $report['columns_added'][] = $colName;
            } else {
                $report['columns_existing'][] = $colName;
            }
        }

        return $report;
    }
}


?>
