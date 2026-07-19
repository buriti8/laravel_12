<?php

namespace App\Audit;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface IsAuditable
{
    /**
     * @return HasMany|null
     */
    public function audits(): ? HasMany;

    /**
     * @return string
     */
    public function auditLangFile(): string;

    /**
     * @return string
     */
    public function getAuditTable(): string;

    /**
     * @return array
     */
    public function getAuditFields(): array;

    /**
     * @return bool
     */
    public function auditTableExists(): bool;

    /**
     * @return void
     */
    public function createAuditTable();

    /**
     * @return void
     */
    public function dropAuditTable();
}