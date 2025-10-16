<?php
namespace App\Repositories;


use App\Models\Contact;


class ContactRepository
{
public function findByPhoneOrEmail(string $phone = null, string $email = null): ?Contact
{
$q = Contact::query();
if ($phone) $q->where('phone', preg_replace('/\D+/', '', $phone));
if ($email) $q->orWhere('email', $email);
return $q->first();
}
}