<?php

namespace Modules\Web\Controllers;

use Core\Http\Controllers\Controller;
use Core\Jobs\CreateSystemUser;
use Core\Models\IP;
use Core\Models\SystemUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Domain\Models\Domain;
use Modules\Domain\Requests\ValidateDomain;
use Modules\Domain\Traits\ResolveDomain;
use Modules\Web\Jobs\BuildPhpConfiguration;
use Modules\Web\Jobs\BuildSslConfiguration;
use Modules\Web\Jobs\BuildWebspace;
use Modules\Web\Models\Webspace;
use Modules\Web\WebModule;

class WebsitesController extends Controller
{
    use ResolveDomain;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $webspace = Webspace::paginate(25);

        //TODO: magic

        return view('web::websites.index', [
            'webspaces' => $webspace
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $ipv4 = IP::select('id', 'address')
            ->where('type', 'ipv4')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => $item->address];
            });
        $ipv4Selected = $ipv4->keys()->first();
        $ipv4->prepend('(Not set)', '');

        $ipv6 = IP::select('id', 'address')
            ->where('type', 'ipv6')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => $item->address];
            });
        $ipv6Selected = $ipv6->keys()->first();
        $ipv6->prepend('(Not set)', '');

        $domainsSld = Domain::where('is_sld', true)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => "$item->name.$item->extension"];
            });

        return view('web::websites.create', [
            'ipv4' => $ipv4,
            'ipv4Selected' => $ipv4Selected,
            'ipv6' => $ipv6,
            'ipv6Selected' => $ipv6Selected,
            'domains' => $domainsSld
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        /** @var ValidateDomain $domainValidator */
        $domainValidator = app()->make(ValidateDomain::class);

        $this->validate($domainValidator, [
            'ipv4_id' => 'required|exists:system_ips,id',
            'ipv6_id' => 'present'
        ]);

        $parsedDomain = explode('.', $request->get('domain'));

        DB::transaction(function () use (&$parsedDomain, &$request) {
            $domain = new Domain();
            $domain->user_id = auth()->id();
            $domain->extension = $request->get('parent_domain') === null ? $parsedDomain[array_key_last($parsedDomain)] : null;
            $domain->name = $parsedDomain[0];
            $domain->is_sld = count($parsedDomain) === 2;
            $domain->used_count = 1;

            if ($request->get('parent_domain') !== null) {
                $domain->parent_domain = $request->get('parent_domain');
            }
            $domain->save();

            $systemUser = SystemUser::new('/var/www/'.$request->get('domain'));
            $systemUser->save();

            $webspace = new Webspace();
            $webspace->fill($request->all());

            $topParent = $this->getTopParentDomain($domain);

            $webspace->system_user_id = $systemUser->id;
            $webspace->domain_id = $domain->id;
            $webspace->web_root = config('web-module.base_dir').$topParent->name.'.'.$topParent->extension.DIRECTORY_SEPARATOR;

            if ($request->get('parent_domain') !== null) {
                $webspace->document_root = $webspace->web_root.$domain->name.DIRECTORY_SEPARATOR;
            } else {
                $webspace->document_root = $webspace->web_root.config('web-module.docroot_dir');
            }

            $webspace->save();

            $this->dispatch(new CreateSystemUser($systemUser));
            $this->dispatch(new BuildWebspace($webspace));

            if ($request->get('php_enabled')) {
                $this->dispatch(new BuildPhpConfiguration($webspace));
            }

            if ($request->get('ssl_enabled')) {
                $this->dispatch(new BuildSslConfiguration($webspace));
            }
        });

        session()->flash('status', ['success' => 'The website is now queued for creation.']);

        return redirect()->route('web.websites.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
