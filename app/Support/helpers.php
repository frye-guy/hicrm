<?php
if (! function_exists('dial_link')) {
  function dial_link(string $phone): string {
    $tpl = config('app.dial_template') ?? env('INTEGRATIONS_3CX_LINK_TEMPLATE');
    $e164 = preg_replace('/\D+/', '', $phone);
    return $tpl ? str_replace('{E164}', $e164, $tpl) : 'tel:'.$e164;
  }
}
