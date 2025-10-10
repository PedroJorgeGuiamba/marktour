<?php
/**
 * Stubs for Intelephense / static analyzers.
 *
 * This file provides minimal, non-executing declarations for legacy
 * Google_Service_* classes so IDEs (Intelephense) don't report undefined
 * type errors when the vendor package is vendored without Composer.
 *
 * It is safe to keep in the repository; it will not be loaded at runtime
 * unless explicitly required. If you prefer, you can add `stubs/` to
 * your .gitignore or exclude it in the IDE settings.
 */

namespace {
    // Legacy alias used by older examples: Google_Service_Oauth2
    if (!class_exists('Google_Service_Oauth2')) {
        class Google_Service_Oauth2
        {
            public $userinfo;

            public function __construct($client = null)
            {
                // no runtime behaviour — this is a static analysis stub
            }
        }
    }

    // Provide a stub for the nested resource type to satisfy docblocks
    if (!class_exists('Google_Service_Oauth2_Userinfo')) {
        class Google_Service_Oauth2_Userinfo
        {
            public function get()
            {
                return (object) ['email' => '', 'name' => ''];
            }
        }
    }
}
