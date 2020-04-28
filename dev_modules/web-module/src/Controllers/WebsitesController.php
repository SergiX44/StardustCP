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
use Modules\Web\Jobs\CreateWebspace;
use Modules\Web\Models\Webspace;
use Modules\Web\WebModule;

class WebsitesController extends Controller
{
    use ResolveDomain;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('web::websites.index');
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

        $ipv6 = IP::select('id', 'address')
            ->where('type', 'ipv6')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => $item->address];
            });

        $ipv6->prepend('(Not set)', '');

        $domainsSld = Domain::where('is_sld', true)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => "$item->name.$item->extension"];
            });

        return view('web::websites.create', [
            'ipv4' => $ipv4,
            'ipv6' => $ipv6,
            'domains' => $domainsSld
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        /** @var ValidateDomain $domainValidator */
        $domainValidator = app()->make(ValidateDomain::class);

        $this->validate($domainValidator, [
            'ipv4' => 'required|exists:system_ips,id',
            'ipv6' => 'present'
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
            $webspace->web_root = WebModule::WEB_BASE_DIR.$topParent->name.'.'.$topParent->extension.DIRECTORY_SEPARATOR;

            if ($request->get('parent_domain') !== null) {
                $webspace->document_root = $webspace->web_root.$domain->name.DIRECTORY_SEPARATOR;
            } else {
                $webspace->document_root = $webspace->web_root.WebModule::WEB_SITE_DOCROOT_DIR;
            }

            $webspace->save();

            $this->dispatch(new CreateSystemUser($systemUser));
            $this->dispatch(new CreateWebspace($webspace));
        });
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
