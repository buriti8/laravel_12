<?php

namespace App\Http\Controllers\ProjectControllers;

use App\Audit\IsAuditable;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class AuditController extends Controller
{
    /**
     * @param string|null $table
     * @param int|null $id
     * @return string
     */
    public function changeLog(?string $table,?int $id)
    {
        $model = $this->getModelByTable($table);

        if(!$model) {
            return '<p class="alert alert-danger">' . __('audit.invalid_table') . '</p>';
        } else if(!$model->auditTableExists()) {
            return '<p class="alert alert-danger">' . __('audit.table_not_exists') . '</p>';
        }

        $per_page = request()->get('per_page', 20);
        $record = $model->newQuery()->findOrFail($id);
        $audit = $record->audits()->paginate($per_page);
        $audit->appends(['per_page' => $per_page]);

        if (request()->ajax()) {
            return view('audit.change_log', compact('audit', 'model'))->render();
        }

        return view('audit.change_log', compact('audit', 'model'));
    }

    /**
     * @param string $table
     * @return IsAuditable|Model
     */
    private function getModelByTable(string $table)
    {
        return app()->make('auditableModels')->filter(function(IsAuditable $model) use($table) {
            return $model->getAuditTable() === $table;
        })->first();
    }
}
