<form class="form-horizontal" role="form" method="POST" action="{{ route('roles.permissions.update', $role->id) }}"
    id='save_form'>
    @csrf
    <div class="row">
        <section class="col col-12">
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped">
                    <tr class="background_color">
                        <th style="text-align: center">{{Lang::get('base_lang.module')}}</th>
                        @foreach($permissions as $perm =>$p)
                        <th style="text-align: center">{{ $p }}</th>
                        @endforeach
                    </tr>
                    @foreach($modules as $mod =>$m)
                    <tr role="row" class="odd" id="">
                        <td>{{ $m }}</td>
                        @foreach($permissions as $perm =>$p)
                        <?php $name_permission = $perm . '_' . $mod; ?>
                        <td style="text-align: center">
                            <input type="checkbox" id="{{$name_permission}}" name="{{$name_permission}}" value="1"
                                {{$role->hasPermissionTo($name_permission) ? 'checked' : ''}}>
                        </td>
                        @endforeach
                    </tr>
                    @endforeach

                </table>
            </div>
        </section>
    </div>
    <div class="row pb-3">
        <div class="col col-12 text-right">
            <section class="col col-12">
                <button type="submit" class="btn btn-sm btn-primary">
                    @lang('base_lang.save')
                </button>
                <a href="{{ url('/roles') }}" class="btn btn-sm btn-primary">
                    @lang('base_lang.cancel')
                </a>
            </section>
        </div>
    </div>
</form>