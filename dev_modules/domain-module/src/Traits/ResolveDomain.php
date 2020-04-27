<?php

namespace Modules\Domain\Traits;

use Modules\Domain\Models\Domain;

trait ResolveDomain
{
    public function getFullDomain(Domain $domain)
    {
        $subs = '';
        while ($domain->parent_domain !== null) {
            $subs .= $domain->name.'.';
            $domain = $domain->parent_domain()->first();
        }

        return $subs.$domain->name.'.'.$domain->extension;
    }

    public function getTopParentDomain(Domain $domain)
    {
        while ($domain->parent_domain !== null) {
            $domain = $domain->parent_domain()->first();
        }

        return $domain;
    }
}
