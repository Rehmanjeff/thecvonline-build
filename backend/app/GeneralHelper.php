<?php
function currentUser()
{
    if (\Auth::check()) {
        return \Auth::user();
    }

    return null;
}

function currentUserId()
{
    if (\Auth::check()) {
        return \Auth::id();
    }

    return null;
}