<a href="{{ route('audit.change_log', ['table' => $model->getAuditTable(), 'id' => $model->id]) }}"
    class="btn btn-sm btn-default changeLog {{ isset($without_text_button) ? 'btn-xs' : 'mb-2' }}"
    title="@lang('audit.change_log')">
    <i class="fas fa-retweet text-center {{ isset($without_text_button) ? 'fa-fw' : 'fa-lg' }}"></i>

    @if (!isset($without_text_button) || !$without_text_button)
    &nbsp;@lang('audit.change_log')
    @endif
</a>

<div class="modal fade" tabindex="-1" id="change_modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><b>@lang('audit.change_log')</b></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start"></div>
        </div>
    </div>
</div>