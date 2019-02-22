<?php namespace ProcessWire;

/*
 * IMPORTANT: DO NOT CUSTOMISE THIS FILE
 * IT WILL BE DELETED WHEN THE PUSHALERT MODULE IS UNINSTALLED
 *
 * Make a new template file and use it as the "Alternate template file" instead
 */

// only save subscription ids for logged in users
if ($user->isLoggedin()) {

    # Get JSON as a string
    $json_str = @file_get_contents('php://input');
    if (empty($json_str)) return;

    # Get as an object
    $json_obj = json_decode($json_str);

    $paMod = $modules->get("PushAlert");

    $subscribers = $page->children("title=$json_obj->subscriber_id");
    if ($subscribers->count > 0)
        return;

        $p = new Page();
        $p->of(false);
        $p->template = 'pushalert_subscription';
        $p->parent   = $page->id;
        $p->title    = $json_obj->subscriber_id;
        $p->pushalert_user_id = $user->id;
        $p->save();

    http_response_code(200); // PHP 5.4 or greater
}
