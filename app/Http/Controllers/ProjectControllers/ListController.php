<?php

namespace App\Http\Controllers\ProjectControllers;

use App\Http\Requests\UpdateListRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\CreateListRequest;
use App\Models\Plist;

class ListController extends Controller
{
    /**
     * @param Request $request
     * @param PList|null $list
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->has('q')) {
            $search = $request->has('q') ? $request->get('q') : [];
        } else {
            if ($request->has('page')) {
                $search = get_last_user_search('lists', []);
            } else {
                $search = [];
            }
        }

        set_last_user_search('lists', $search);

        $per_page = module_per_page('lists', 20);
        $lists_options = Plist::search($search)->paginate($per_page);
        $lists_options->appends($search + ['per_page' => $per_page]);

        return view('lists.index', [
            'lists_options' => $lists_options,
            'search' => $search,
        ] + Plist::getArrayList());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(PList $list)
    {
        return view('lists.create', [
            'list' => $list,
        ] + Plist::getArrayList());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateListRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateListRequest $request)
    {
        $option = new PList(['status' => 1] + $request->validated());

        if ($option->save()) {
            Session::flash('success', __('lists.created', ['name' => $option->option]));
        } else {
            Session::flash('error', __('lists.created', ['name' => $option->option]));
        }

        return redirect()->route('lists.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PList $list)
    {
        return view('lists.edit', [
            'list' => $list,
        ] + Plist::getArrayList());
    }

    /**
     * @param int $id
     * @param UpdateListRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PList $list, UpdateListRequest $request)
    {
        $list->fill($request->validated());

        if ($list->save()) {
            Session::flash('success', __('lists.updated', ['name' => $list->option]));
        } else {
            Session::flash('error', __('lists.updated', ['name' => $list->option]));
        }

        return redirect()->route('lists.index');
    }
}
