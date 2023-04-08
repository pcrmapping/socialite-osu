<?php

namespace PcrMapping\SocialiteOsu;

use SocialiteProviders\Manager\SocialiteWasCalled;

class OsuExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(socialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('osu', Provider::class);
    }
}
