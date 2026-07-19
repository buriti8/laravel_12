<div>
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="w-100 title-module">
                    @lang('audit.fields_audit')
                </div>

                <p>
                    @foreach($model->getAuditFields() as $f)
                    {{ $f->label }}@if(! $loop->last), @else.@endif
                    @endforeach
                </p>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-striped">
                        <thead class="thead-gray">
                            <tr class="">
                                <th class="text-center" style="width:20%">@lang('audit.field')</th>
                                <th class="text-center" style="width:25%">@lang('audit.before_value')</th>
                                <th class="text-center" style="width:25%">@lang('audit.after_value')</th>
                                <th class="text-center" style="width:15%">@lang('audit.changed_by')</th>
                                <th class="text-center" style="width:15%">@lang('audit.change_date')</th>
                            </tr>
                        </thead>
                        @forelse($audit as $s)
                        <tr>
                            <td>{{ __($model->auditLangFile() . '.'.$s->field_name) }}</td>
                            <td>{{ $s->before_value }}</td>
                            <td>{{ $s->after_value }}</td>
                            <td>{{ $s->user->name }}</td>
                            <td class="text-center">@formatDateTime($s->created_at)</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11">
                                <em>@lang('base_lang.no_records')</em>
                            </td>
                        </tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>