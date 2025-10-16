public function delete(User $user, Note $note): bool
{
    return $user->id === $note->user_id || $user->hasRole('Admin');
}
