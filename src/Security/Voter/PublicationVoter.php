<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Article;

class PublicationVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['draft', 'review', 'reject', 'publish', 'archive'])
            && $subject instanceof Article;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /*  $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if ($attribute === 'review' && $subject->getStatus() === Article::ARTICLE_STATUS_DRAFT) {
            return true;
        }
        if ($attribute === 'archive' && $subject->getStatus() === Article::ARTICLE_STATUS_DRAFT) {
            return true;
        }
        if ($attribute === 'reject' && $subject->getStatus() === Article::ARTICLE_STATUS_DRAFT) {
            return true;
        }
        if ($attribute === 'publish' && $subject->getStatus() === Article::ARTICLE_STATUS_TO_REVIEW) {
            return true;
        }
        if ($attribute === 'archive' && $subject->getStatus() === Article::ARTICLE_STATUS_TO_REVIEW) {
            return true;
        }
        if ($attribute === 'reject' && $subject->getStatus()  === Article::ARTICLE_STATUS_TO_REVIEW) {
            return true;
        }
        if ($attribute === 'archive' && $subject->getStatus() === Article::ARTICLE_STATUS_PUBLISHED) {
            return true;
        }
        if ($attribute === 'reject' && $subject->getStatus() === Article::ARTICLE_STATUS_PUBLISHED) {
            return true;
        }
        if ($attribute === 'publish' && $subject->getStatus() === Article::ARTICLE_STATUS_ARCHIVED) {
            return true;
        }
        if ($attribute === 'reject' && $subject->getStatus() === Article::ARTICLE_STATUS_ARCHIVED) {
            return true;
        }
        if ($attribute === 'draft' && $subject->getStatus() === Article::ARTICLE_STATUS_REJECTED) {
            return true;
        }
        if ($attribute === 'archive' && $subject->getStatus() === Article::ARTICLE_STATUS_REJECTED) {
            return true;
        } */

        return match ($attribute) {
            'draft' => $subject->getStatus() == Article::ARTICLE_STATUS_REJECTED,
            'review' => $subject->getStatus() === Article::ARTICLE_STATUS_DRAFT,
            'reject' => ($subject->getStatus() === Article::ARTICLE_STATUS_TO_REVIEW || $subject->getStatus() === Article::ARTICLE_STATUS_PUBLISHED || $subject->getStatus() === Article::ARTICLE_STATUS_ARCHIVED || $subject->getStatus() === Article::ARTICLE_STATUS_DRAFT),
            'archive' => ($subject->getStatus() === Article::ARTICLE_STATUS_PUBLISHED ||
                $subject->getStatus() === Article::ARTICLE_STATUS_REJECTED || $subject->getStatus() === Article::ARTICLE_STATUS_TO_REVIEW || $subject->getStatus() === Article::ARTICLE_STATUS_DRAFT),
            'publish' => ($subject->getStatus() === Article::ARTICLE_STATUS_TO_REVIEW || $subject->getStatus() === Article::ARTICLE_STATUS_ARCHIVED),
            default => false,
        };


        /*   // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'POST_EDIT':
                // logic to determine if the user can EDIT
                // return true or false
                break;
            case 'POST_VIEW':
                // logic to determine if the user can VIEW
                // return true or false
                break;
        } */

        return false;
    }
}
