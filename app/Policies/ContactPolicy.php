<?php
namespace App\Policies;


use App\Models\Contact;
use App\Models\User;


class ContactPolicy
{
public function view(User $user, Contact $contact): bool
{ return $user->hasRole(['Admin','Executive','MarketingManager','Marketer','SalesRep']); }


public function update(User $user, Contact $contact): bool
{ return $user->hasRole(['Admin','MarketingManager']); }


public function call(User $user, Contact $contact): bool
{ return $user->hasRole(['Admin','MarketingManager','Marketer']); }
}